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
      
      // Ignorar comentários, linhas vazias e comandos de estrutura (CREATE/DROP)
      // assumindo que as tabelas já foram criadas pelo sincronismo do TypeORM ou script SQL.
      if (
        line.startsWith('--') || 
        line.trim() === '' || 
        line.startsWith('/*') ||
        line.toUpperCase().startsWith('CREATE TABLE') ||
        line.toUpperCase().startsWith('DROP TABLE') ||
        line.toUpperCase().startsWith('ALTER TABLE')
      ) {
        continue;
      }

      // Processar apenas comandos INSERT
      if (line.toUpperCase().startsWith('INSERT INTO')) {
        let pgLine = line
          .replace(/`/g, '"') // Trocar backticks por aspas duplas
          .replace(/\\'/g, "''") // Escapar aspas simples
          .replace(/\\"/g, '"') // Ajustar aspas duplas
          .replace(/\\r\\n/g, '\n') // Ajustar quebras de linha
          .replace(/\\n/g, '\n');

        try {
          await this.dataSource.query(pgLine);
          insertCount++;
          if (insertCount % 100 === 0) {
            console.log(`${insertCount} registros importados...`);
          }
        } catch (err) {
          console.error(`Erro na linha ${lineCount}: ${err.message}`);
          // console.error(`Query: ${pgLine.substring(0, 100)}...`);
        }
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
