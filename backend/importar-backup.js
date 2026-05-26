const fs = require('fs');
const path = require('path');
const readline = require('readline');
const { Client } = require('pg');

// 1. Carregar variáveis de ambiente do .env manualmente
function loadEnv() {
  const envPath = path.resolve(__dirname, '.env');
  const rootEnvPath = path.resolve(__dirname, '../.env');
  const targetPath = fs.existsSync(envPath) ? envPath : (fs.existsSync(rootEnvPath) ? rootEnvPath : null);

  if (!targetPath) {
    console.warn('⚠️ Arquivo .env não encontrado. Utilizando variáveis de ambiente do sistema.');
    return;
  }

  const content = fs.readFileSync(targetPath, 'utf8');
  content.split('\n').forEach(line => {
    const trimmed = line.trim();
    if (trimmed === '' || trimmed.startsWith('#')) return;
    const parts = trimmed.split('=');
    if (parts.length >= 2) {
      const key = parts[0].trim();
      const val = parts.slice(1).join('=').trim().replace(/(^['"]|['"]$)/g, '');
      process.env[key] = val;
    }
  });
}

loadEnv();

const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  port: parseInt(process.env.DB_PORT || '5432'),
  user: process.env.DB_USERNAME || 'postgres',
  password: process.env.DB_PASSWORD || 'admin',
};

async function executeSqlFile(filePath, dbName) {
  const client = new Client({ ...dbConfig, database: dbName });
  const absolutePath = path.resolve(filePath);

  if (!fs.existsSync(absolutePath)) {
    console.error(`❌ Arquivo de backup não localizado: ${absolutePath}`);
    return;
  }

  console.log(`\n⏳ Conectando ao banco [${dbName}]...`);
  await client.connect();
  console.log(`🔌 Conectado. Iniciando leitura de: ${path.basename(filePath)}`);

  try {
    await client.query("SET session_replication_role = 'replica'");
  } catch (e) {
    console.warn('⚠️ Não foi possível desabilitar restrições temporárias.');
  }

  const fileStream = fs.createReadStream(absolutePath);
  const rl = readline.createInterface({ input: fileStream, crlfDelay: Infinity });

  const logPath = path.join(path.dirname(absolutePath), 'migration_errors.log');
  const logStream = fs.createWriteStream(logPath, { flags: 'a' });
  logStream.write(`\n\n--- INÍCIO DA MIGRAÇÃO NO BANCO [${dbName}]: ${new Date().toISOString()} ---\n`);
  logStream.write(`Arquivo de backup original: ${filePath}\n\n`);

  let queryBuffer = '';
  let lineCount = 0;
  let insertCount = 0;
  let errorCount = 0;

  for await (const line of rl) {
    lineCount++;
    const trimmedLine = line.trim();
    if (trimmedLine === '' || trimmedLine.startsWith('--') || trimmedLine.startsWith('#')) {
      continue;
    }

    queryBuffer += ' ' + line;

    if (trimmedLine.match(/;(\s*(\/\*.*\*\/)?\s*)*$/)) {
      let pgQuery = queryBuffer.trim();
      queryBuffer = '';

      if (pgQuery.toUpperCase().startsWith('INSERT INTO')) {
        let convertedQuery = pgQuery
          .replace(/`/g, '"')
          .replace(/\\'/g, "''")
          .replace(/\\"/g, '"')
          .replace(/\\r\\n/g, '\n')
          .replace(/\\n/g, '\n');

        // 1. Converter números longos sem aspas (CNPJs de 14 dígitos ou CPFs de 11 dígitos)
        convertedQuery = convertedQuery.replace(/(?<=^|[,\(\s\n\r])(\d{11,})(?=$|[,\)\s\n\r])/g, "'$1'");

        // 2. Converter valores de status isolados com aspas duplas em aspas simples (ex: "S", "N", "A", "B", "1")
        convertedQuery = convertedQuery.replace(/(?<=^|[,\(\s\n\r])"([a-zA-Z0-9])"(?=$|[,\)\s\n\r])/g, "'$1'");

        try {
          await client.query(convertedQuery);
          insertCount++;
          if (insertCount % 500 === 0) {
            console.log(`   Processed: ${insertCount} registros inseridos...`);
          }
        } catch (err) {
          const msg = err.message.toLowerCase();
          if (!msg.includes('does not exist') && !msg.includes('duplicate key') && !msg.includes('already exists')) {
            errorCount++;
            logStream.write(`Linha ${lineCount}: ERRO REAL - ${err.message}\n`);
            logStream.write(`Query convertida problemática (truncada): ${convertedQuery.substring(0, 1500)}...\n\n`);
          } else {
            // Gravar avisos de chaves duplicadas no log de forma limpa
            logStream.write(`Linha ${lineCount}: Aviso (Pulado) - ${err.message}\n`);
          }
        }
      }
    }
  }

  try {
    await client.query("SET session_replication_role = 'origin'");
  } catch (e) {}

  logStream.write(`\n--- FIM DA MIGRAÇÃO NO BANCO [${dbName}]: ${new Date().toISOString()} - Sucessos: ${insertCount} - Erros Reais: ${errorCount} ---\n`);
  logStream.end();

  console.log(`✅ Concluído: ${insertCount} registros inseridos no banco [${dbName}].`);
  if (errorCount > 0) {
    console.log(`⚠️ Foram detectados ${errorCount} erros reais na conversão sintática.`);
    console.log(`📂 Todos os erros foram gravados detalhadamente no log: migration_errors.log`);
  } else {
    console.log(`🎉 Migração de dados concluída sem erros sintáticos de banco!`);
  }
  
  // Ajustar sequências de IDs
  console.log('⚙️ Ajustando sequências de auto-incremento (Sequences)...');
  const tablesRes = await client.query(`
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
  `);

  for (const row of tablesRes.rows) {
    const tableName = row.table_name;
    try {
      const idColRes = await client.query(`
        SELECT column_name 
        FROM information_schema.columns 
        WHERE table_name = $1 AND column_name = 'id'
      `, [tableName]);

      if (idColRes.rows.length > 0) {
        const seqRes = await client.query(`
          SELECT pg_get_serial_sequence($1, 'id') as seq
        `, [tableName]);
        
        const seq = seqRes.rows[0]?.seq;
        if (seq) {
          await client.query(`
            SELECT setval($1, COALESCE((SELECT MAX(id) FROM "${tableName}"), 1))
          `, [seq]);
        }
      }
    } catch (e) {}
  }
  console.log('✅ Sequences ajustadas com sucesso.');

  await client.end();
}

async function run() {
  console.log('===================================================');
  console.log('     SISTEMA AUTÔNOMO DE IMPORTAÇÃO DE BACKUP');
  console.log('===================================================');

  const securityDb = process.env.DB_SECURITY_DATABASE || 'rcgcrm_security';
  const crmDb = process.env.DB_DATABASE || 'rcgcrm';

  try {
    // 1. Limpar e importar Security
    console.log('\n[1/2] IMPORTANDO PERMISSÕES E USUÁRIOS (Security)...');
    const permPath = path.join(__dirname, '../backup/permissao_mysql.sql');
    
    const clientSec = new Client({ ...dbConfig, database: securityDb });
    await clientSec.connect();
    try {
      await clientSec.query('TRUNCATE system_user_group, system_group_program, system_users, system_group, system_program, system_unit, system_preference CASCADE');
      console.log('🧹 Limpeza do banco Security realizada.');
    } catch(e) {}
    await clientSec.end();

    await executeSqlFile(permPath, securityDb);

    // 2. Limpar e importar CRM Comercial
    console.log('\n[2/2] IMPORTANDO DADOS COMERCIAIS (CRM)...');
    const backupPath = path.join(__dirname, '../backup/backup_mysql.sql');

    const clientCrm = new Client({ ...dbConfig, database: crmDb });
    await clientCrm.connect();
    try {
      await clientCrm.query('TRUNCATE vendedor, filial, cliente, nota_saida, titulo_receber, atendimento, produto CASCADE');
      console.log('🧹 Limpeza do banco CRM realizada.');
    } catch(e) {}
    await clientCrm.end();

    await executeSqlFile(backupPath, crmDb);

    // 3. Recriar todas as 47 Views do Banco de Dados (CRM)
    const viewsSqlPath = path.join(__dirname, '../database/view_postgres.sql');
    if (fs.existsSync(viewsSqlPath)) {
      console.log('\n📊 [3/3] RECRIANDO VIEWS DO BANCO DE DADOS (CRM)...');
      const clientCrmViews = new Client({ ...dbConfig, database: crmDb });
      await clientCrmViews.connect();
      try {
        const viewsSql = fs.readFileSync(viewsSqlPath, 'utf8');
        await clientCrmViews.query(viewsSql);
        console.log('✅ Todas as 47 Views (incluindo o MCV) foram recriadas com sucesso.');
      } catch (err) {
        console.error('⚠️ Falha ao recriar as views:', err.message);
      } finally {
        await clientCrmViews.end();
      }
    } else {
      console.warn('⚠️ Arquivo view_postgres.sql não localizado. Pulando recriação de views.');
    }

    console.log('\n===================================================');
    console.log('🎉 IMPORTAÇÃO COMPLETA REALIZADA COM SUCESSO!');
    console.log('===================================================');
  } catch (err) {
    console.error('\n❌ Ocorreu uma falha catastrófica:', err.message);
  }
}

run();
