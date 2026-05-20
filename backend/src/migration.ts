import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';
import * as fs from 'fs';
import * as readline from 'readline';
import * as path from 'path';

@Injectable()
export class MigrationDataService {
  constructor(private dataSource: DataSource) {}

  async migrateMySQLtoPG(filePath: string, targetDataSource?: DataSource) {
    const ds = targetDataSource || this.dataSource;
    const absolutePath = path.resolve(filePath);
    if (!fs.existsSync(absolutePath)) {
      console.error(`Arquivo não encontrado: ${absolutePath}`);
      return;
    }

    // DESABILITAR TUDO: Foreign Keys, Triggers e Constraints para esta sessão
    // Isso é o segredo para migrar dados fora de ordem ou com inconsistências históricas
    try {
      await ds.query("SET session_replication_role = 'replica'");
      console.log('🔓 Verificações de integridade desabilitadas temporariamente.');
    } catch (e) {
      console.warn('⚠️ Falha ao desabilitar FKs. O usuário do banco precisa ser superuser ou owner.');
    }

    const logPath = path.join(path.dirname(absolutePath), 'migration_errors.log');
    const logStream = fs.createWriteStream(logPath, { flags: 'a' });
    logStream.write(`\n\n--- INÍCIO DA MIGRAÇÃO: ${new Date().toISOString()} ---\n`);
    logStream.write(`Arquivo: ${filePath}\n`);

    const fileStream = fs.createReadStream(absolutePath);
    const rl = readline.createInterface({
      input: fileStream,
      crlfDelay: Infinity,
    });

    console.log(`Iniciando migração de: ${filePath}...`);
    
    let queryBuffer = '';
    let lineCount = 0;
    let insertCount = 0;

    for await (const line of rl) {
      lineCount++;
      const trimmedLine = line.trim();
      
      if (trimmedLine === '' || trimmedLine.startsWith('--') || trimmedLine.startsWith('/*')) {
        continue;
      }

      queryBuffer += ' ' + line;

      if (trimmedLine.endsWith(';')) {
        const pgQuery = queryBuffer.trim();

        // APENAS INSERTs SÃO PROCESSADOS
        if (pgQuery.toUpperCase().startsWith('INSERT INTO')) {
          let convertedQuery = pgQuery
            .replace(/`/g, '"') 
            .replace(/\\'/g, "''") 
            .replace(/\\"/g, '"') 
            .replace(/\\r\\n/g, '\n')
            .replace(/\\n/g, '\n');

          try {
            await ds.query(convertedQuery);
            insertCount++;
            if (insertCount % 500 === 0) {
              console.log(`${insertCount} comandos de INSERT processados...`);
            }
          } catch (err) {
            const msg = err.message.toLowerCase();
            // Ignorar erros que não impedem a carga básica
            if (msg.includes('does not exist') || msg.includes('duplicate key') || msg.includes('already exists')) {
               logStream.write(`Linha ${lineCount}: Ignorado (Estrutura ou Duplicidade) - ${err.message}\n`);
            } else {
               // Erros reais de sintaxe ou dados corrompidos
               console.error(`Erro na linha ${lineCount}: ${err.message}`);
               logStream.write(`Linha ${lineCount}: ERRO REAL - ${err.message}\n`);
            }
          }
        }
        queryBuffer = '';
      }
    }

    // REABILITAR TUDO
    try {
      await ds.query("SET session_replication_role = 'origin'");
      console.log('🔒 Verificações de integridade reabilitadas.');
    } catch (e) {}

    logStream.write(`--- FIM DA MIGRAÇÃO: ${new Date().toISOString()} ---\n`);
    logStream.end();
    console.log(`Migração finalizada. ${insertCount} registros inseridos.`);
    await this.fixSequences(ds);
  }

  async fixSequences(targetDataSource?: DataSource) {
    const ds = targetDataSource || this.dataSource;
    console.log('Sincronizando sequences...');
    const tables = await ds.query(`
      SELECT table_name 
      FROM information_schema.tables 
      WHERE table_schema = 'public' 
      AND table_type = 'BASE TABLE'
    `);

    for (const table of tables) {
      const tableName = table.table_name;
      try {
        // Resetar sequences para que os próximos IDs automáticos não deem erro
        await ds.query(`
          SELECT setval(pg_get_serial_sequence('"${tableName}"', 'id'), coalesce(max(id), 1), true) FROM "${tableName}";
        `);
      } catch (e) {}
    }
    console.log('Sequences sincronizadas!');
  }
}
