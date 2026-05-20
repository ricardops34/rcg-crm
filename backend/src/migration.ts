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

    // Desabilitar chaves estrangeiras e triggers temporariamente
    // No PostgreSQL, isso exige privilégios de superusuário ou ser o dono da tabela.
    // O 'session_replication_role' é a forma mais eficaz de ignorar FKs durante migração.
    try {
      await ds.query("SET session_replication_role = 'replica'");
    } catch (e) {
      console.warn('⚠️ Não foi possível desabilitar FKs (sem privilégios?). Erros de constraint podem ocorrer.');
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
      
      // Ignorar comentários, linhas vazias e comandos de estrutura/transação
      if (
        trimmedLine === '' || 
        trimmedLine.startsWith('--') || 
        trimmedLine.startsWith('/*') ||
        trimmedLine.toUpperCase().startsWith('CREATE TABLE') ||
        trimmedLine.toUpperCase().startsWith('DROP TABLE') ||
        trimmedLine.toUpperCase().startsWith('ALTER TABLE') ||
        trimmedLine.toUpperCase().startsWith('SET ') ||
        trimmedLine.toUpperCase().startsWith('LOCK TABLES') ||
        trimmedLine.toUpperCase().startsWith('UNLOCK TABLES') ||
        trimmedLine.toUpperCase().startsWith('START TRANSACTION') ||
        trimmedLine.toUpperCase().startsWith('BEGIN') ||
        trimmedLine.toUpperCase().startsWith('COMMIT') ||
        trimmedLine.toUpperCase().includes('ADD CONSTRAINT') ||
        trimmedLine.toUpperCase().includes('ADD KEY') ||
        trimmedLine.toUpperCase().includes('ADD PRIMARY KEY') ||
        trimmedLine.toUpperCase().includes('ALTER COLUMN')
      ) {
        queryBuffer = ''; // Limpar buffer se cair em estrutura ou transação
        continue;
      }

      // Adicionar linha ao buffer
      queryBuffer += ' ' + line;

      // Se a linha termina com ponto e vírgula, processar a query completa
      if (trimmedLine.endsWith(';')) {
        const pgQuery = queryBuffer.trim();

        // REGRAS DE OURO: Apenas migrar DADOS (INSERT INTO)
        if (pgQuery.toUpperCase().startsWith('INSERT INTO')) {
          let convertedQuery = pgQuery
            .replace(/`/g, '"') // Trocar backticks por aspas duplas
            .replace(/\\'/g, "''") // Escapar aspas simples (MySQL style para PG)
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
            // Ignorar se a tabela/coluna não existir no novo banco
            if (err.message.includes('does not exist')) {
               logStream.write(`Linha ${lineCount}: Tabela/Coluna inexistente - ${err.message}\n`);
               queryBuffer = '';
               continue;
            }

            // Logar erros reais de dados
            if (!err.message.includes('already exists') && !err.message.includes('duplicate key')) {
               console.error(`Erro de Dados na linha ${lineCount}: ${err.message}`);
               logStream.write(`Linha ${lineCount}: ERRO CRÍTICO - ${err.message}\n`);
            } else {
               logStream.write(`Linha ${lineCount}: Já existente - ${err.message}\n`);
            }
          }
        }

        // Limpar buffer para a próxima query
        queryBuffer = '';
      }
    }

    // Reabilitar chaves estrangeiras
    try {
      await ds.query("SET session_replication_role = 'origin'");
    } catch (e) {}

    logStream.write(`--- FIM DA MIGRAÇÃO: ${new Date().toISOString()} ---\n`);
    logStream.end();
    console.log('Migração de dados finalizada!');
    await this.fixSequences(ds);
  }

  async fixSequences(targetDataSource?: DataSource) {
    const ds = targetDataSource || this.dataSource;
    console.log('Sincronizando sequences do PostgreSQL...');
    const tables = await ds.query(`
      SELECT table_name 
      FROM information_schema.tables 
      WHERE table_schema = 'public' 
      AND table_type = 'BASE TABLE'
    `);

    for (const table of tables) {
      const tableName = table.table_name;
      try {
        await ds.query(`
          SELECT setval(pg_get_serial_sequence('"${tableName}"', 'id'), coalesce(max(id), 1)) FROM "${tableName}";
        `);
      } catch (e) {}
    }
    console.log('Sequences sincronizadas com sucesso!');
  }
}
