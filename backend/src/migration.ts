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
    logStream.write(`Arquivo: ${filePath}\n`);

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
      
      // Ignorar linhas vazias e comentários de linha única
      if (trimmedLine === '' || trimmedLine.startsWith('--') || trimmedLine.startsWith('#') || (trimmedLine.startsWith('/*') && trimmedLine.endsWith('*/'))) {
        continue;
      }

      queryBuffer += ' ' + line;

      // Se a linha termina com ; (pode ter espaços ou comentários depois)
      if (trimmedLine.match(/;(\s*(\/\*.*\*\/)?\s*)*$/)) {
        let pgQuery = queryBuffer.trim();
        queryBuffer = ''; 

        // Limpar comentários do início do buffer para detectar o comando
        const cleanQuery = pgQuery.replace(/^\/\*[\s\S]*?\*\/|^\s*--.*?\n|^\s*#.*?\n/gm, '').trim();

        if (cleanQuery.toUpperCase().startsWith('INSERT INTO')) {
          // 1. Nomes de tabelas e colunas
          let convertedQuery = pgQuery.replace(/`/g, '"');

          // 2. Escapes de string
          convertedQuery = convertedQuery.replace(/\\'/g, "''");
          convertedQuery = convertedQuery.replace(/\\"/g, '"');

          // 3. Corrigir números longos SEM ASPAS (CNPJ/CPF)
          // Rodamos em loop até não haver mais mudanças para evitar problemas de overlap
          let lastQuery;
          do {
            lastQuery = convertedQuery;
            convertedQuery = convertedQuery.replace(/([,\(\s])(\d{11,})([,\)\s])/g, "$1'$2'$3");
          } while (convertedQuery !== lastQuery);

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
               logStream.write(`Query (truncada): ${convertedQuery.substring(0, 1000)}\n`);
            }
          }
        }
      }
    }

    try {
      await ds.query("SET session_replication_role = 'origin'");
      console.log('🔒 Verificações reabilitadas.');
    } catch (e) {}

    logStream.write(`--- FIM DA MIGRAÇÃO: ${new Date().toISOString()} - Total Inserções: ${insertCount} ---\n`);
    logStream.end();
    console.log(`Finalizado: ${insertCount} registros inseridos.`);
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
