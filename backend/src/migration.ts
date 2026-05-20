import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';
import * as fs from 'fs';
import * as readline from 'readline';
import * as path from 'path';

@Injectable()
export class MigrationDataService {
  constructor(private dataSource: DataSource) {}

  async migrateMySQLtoPG(filePath: string) {
    const absolutePath = path.resolve(filePath);
    if (!fs.existsSync(absolutePath)) {
      console.error(`Arquivo não encontrado: ${absolutePath}`);
      return;
    }

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
      
      // Ignorar comentários, linhas vazias e comandos de estrutura
      if (
        trimmedLine === '' || 
        trimmedLine.startsWith('--') || 
        trimmedLine.startsWith('/*') ||
        trimmedLine.toUpperCase().startsWith('CREATE TABLE') ||
        trimmedLine.toUpperCase().startsWith('DROP TABLE') ||
        trimmedLine.toUpperCase().startsWith('ALTER TABLE') ||
        trimmedLine.toUpperCase().startsWith('SET ') ||
        trimmedLine.toUpperCase().startsWith('LOCK TABLES') ||
        trimmedLine.toUpperCase().startsWith('UNLOCK TABLES')
      ) {
        continue;
      }

      // Adicionar linha ao buffer
      queryBuffer += ' ' + line;

      // Se a linha termina com ponto e vírgula, processar a query completa
      if (trimmedLine.endsWith(';')) {
        let pgQuery = queryBuffer
          .replace(/`/g, '"') // Trocar backticks por aspas duplas
          .replace(/\\'/g, "''") // Escapar aspas simples (MySQL style para PG)
          .replace(/\\"/g, '"') 
          .replace(/\\r\\n/g, '\n')
          .replace(/\\n/g, '\n')
          .trim();

        try {
          // Remover o ponto e vírgula final se necessário (o driver costuma não precisar, mas PG aceita)
          await this.dataSource.query(pgQuery);
          insertCount++;
          if (insertCount % 500 === 0) {
            console.log(`${insertCount} comandos SQL processados...`);
          }
        } catch (err) {
          // Ignorar erros comuns de duplicidade ou estrutura se desejar, mas vamos logar
          if (!err.message.includes('already exists')) {
             console.error(`Erro na linha ${lineCount}: ${err.message}`);
          }
        }

        // Limpar buffer para a próxima query
        queryBuffer = '';
      }
    }

    console.log('Migração de dados finalizada!');
    await this.fixSequences();
  }

  async fixSequences() {
    console.log('Sincronizando sequences do PostgreSQL...');
    const tables = await this.dataSource.query(`
      SELECT table_name 
      FROM information_schema.tables 
      WHERE table_schema = 'public' 
      AND table_type = 'BASE TABLE'
    `);

    for (const table of tables) {
      const tableName = table.table_name;
      try {
        // Tentar resetar a sequence assumindo o padrão "tabela_id_seq"
        await this.dataSource.query(`
          SELECT setval(pg_get_serial_sequence('"${tableName}"', 'id'), coalesce(max(id), 1)) FROM "${tableName}";
        `);
      } catch (e) {
        // Algumas tabelas podem não ter ID serial ou sequence padrão
      }
    }
    console.log('Sequences sincronizadas com sucesso!');
  }
}
