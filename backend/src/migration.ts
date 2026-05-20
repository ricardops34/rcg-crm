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

    try {
      await ds.query("SET session_replication_role = 'replica'");
      console.log('🔓 Verificações de integridade desabilitadas.');
    } catch (e) {
      console.warn('⚠️ Falha ao desabilitar FKs.');
    }

    const logPath = path.join(path.dirname(absolutePath), 'migration_errors.log');
    const logStream = fs.createWriteStream(logPath, { flags: 'a' });
    logStream.write(`\n\n--- INÍCIO DA MIGRAÇÃO: ${new Date().toISOString()} ---\n`);

    const fileStream = fs.createReadStream(absolutePath);
    const rl = readline.createInterface({
      input: fileStream,
      crlfDelay: Infinity,
    });

    console.log(`Iniciando: ${filePath}...`);
    
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

        // TRATAMENTO INTELIGENTE DE INSERTs
        if (pgQuery.toUpperCase().startsWith('INSERT INTO')) {
          let convertedQuery = pgQuery
            .replace(/`/g, '"') 
            .replace(/\\'/g, "''") 
            .replace(/\\"/g, '"') 
            .replace(/\\r\\n/g, '\n')
            .replace(/\\n/g, '\n');

          // CORREÇÃO DE ASPAS EM NÚMEROS LONGOS (CNPJ/CPF que o MySQL aceita sem aspas)
          // Procura por valores puramente numéricos com mais de 10 dígitos após uma vírgula ou parêntese e coloca aspas
          convertedQuery = convertedQuery.replace(/([,\(])\s*(\d{11,})\s*([,\)])/g, "$1'$2'$3");

          try {
            await ds.query(convertedQuery);
            insertCount++;
            if (insertCount % 500 === 0) console.log(`${insertCount} registros...`);
          } catch (err) {
            const msg = err.message.toLowerCase();
            if (msg.includes('does not exist') || msg.includes('duplicate key') || msg.includes('already exists')) {
               logStream.write(`Linha ${lineCount}: Ignorado - ${err.message}\n`);
            } else {
               logStream.write(`Linha ${lineCount}: ERRO REAL - ${err.message}\n`);
            }
          }
        }
        queryBuffer = '';
      }
    }

    try {
      await ds.query("SET session_replication_role = 'origin'");
      console.log('🔒 Verificações reabilitadas.');
    } catch (e) {}

    logStream.write(`--- FIM DA MIGRAÇÃO: ${new Date().toISOString()} ---\n`);
    logStream.end();
    console.log(`Finalizado: ${insertCount} registros.`);
    await this.fixSequences(ds);
  }

  async fixSequences(targetDataSource?: DataSource) {
    const ds = targetDataSource || this.dataSource;
    const tables = await ds.query(`
      SELECT table_name FROM information_schema.tables 
      WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
    `);

    for (const table of tables) {
      const tableName = table.table_name;
      try {
        await ds.query(`
          SELECT setval(pg_get_serial_sequence('"${tableName}"', 'id'), coalesce(max(id), 1), true) FROM "${tableName}";
        `);
      } catch (e) {}
    }
  }
}
