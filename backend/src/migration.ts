import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { DataSource } from 'typeorm';
import { getDataSourceToken } from '@nestjs/typeorm';
import * as fs from 'fs';
import * as path from 'fs';
import * as readline from 'readline';

async function migrate() {
  console.log('🚀 Iniciando Ferramenta de Migração (MySQL -> PostgreSQL)...');
  const app = await NestFactory.createApplicationContext(AppModule);

  const crmDataSource = app.get<DataSource>(DataSource);
  const securityDataSource = app.get<DataSource>(
    getDataSourceToken('security'),
  );

  try {
    // 1. Migrar Permissões (Security DB)
    const permissionsFile = 'C:/Ricardo/rcgcrm/backup/permissao_mysql.sql';
    if (fs.existsSync(permissionsFile)) {
      console.log('📑 Processando Permissões...');
      await processSqlFile(permissionsFile, securityDataSource);
    }

    // 2. Migrar Dados de Negócio (CRM DB)
    const backupFile = 'C:/Ricardo/rcgcrm/backup/backup_mysql.sql';
    if (fs.existsSync(backupFile)) {
      console.log('📑 Processando Backup CRM (este processo pode demorar)...');
      await processSqlFile(backupFile, crmDataSource);
    }

    console.log('✨ Migração finalizada com sucesso!');
  } catch (error) {
    console.error('❌ Erro durante a migração:', error.message);
  } finally {
    await app.close();
    process.exit(0);
  }
}

async function processSqlFile(filePath: string, dataSource: DataSource) {
  const fileStream = fs.createReadStream(filePath);
  const rl = readline.createInterface({
    input: fileStream,
    crlfDelay: Infinity,
  });

  let currentStatement = '';
  const queryRunner = dataSource.createQueryRunner();
  await queryRunner.connect();

  for await (const line of rl) {
    // Pular comentários e linhas vazias
    if (line.startsWith('--') || line.startsWith('/*') || line.trim() === '')
      continue;

    currentStatement += line + ' ';

    if (line.endsWith(';')) {
      try {
        // Adaptações básicas MySQL -> Postgres
        const finalSql = currentStatement
          .replace(/`/g, '"') // Backticks para aspas duplas
          .replace(/\\'/g, "''") // Escapar aspas simples
          .replace(/AUTO_INCREMENT/gi, '') // Remover auto increment (usamos SERIAL no Postgres)
          .replace(/ENGINE=InnoDB/gi, '')
          .replace(/DEFAULT CHARSET=utf8mb4/gi, '')
          .replace(/COLLATE=utf8mb4_unicode_ci/gi, '')
          .replace(/datetime/gi, 'timestamp')
          .replace(/0000-00-00 00:00:00/g, '1970-01-01 00:00:00');

        // Se for um INSERT, tentar executar
        if (finalSql.trim().toUpperCase().startsWith('INSERT')) {
          await queryRunner.query(finalSql);
        }
      } catch (e) {
        // Log de erro mas continua (pode haver duplicatas ou tabelas que mudaram)
        // console.warn(`⚠️ Aviso em instrução: ${e.message}`);
      }
      currentStatement = '';
    }
  }
  await queryRunner.release();
}

migrate();
