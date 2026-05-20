import { NestFactory } from '@nestjs/core';
import { getDataSourceToken } from '@nestjs/typeorm';
import { AppModule } from './src/app.module';
import { MigrationDataService } from './src/migration';
import * as path from 'path';

async function run() {
  console.log('--- Sistema de Migração de Dados (MySQL -> PG) ---');
  
  const app = await NestFactory.createApplicationContext(AppModule);
  const migrationService = app.get(MigrationDataService);
  const securityDataSource = app.get(getDataSourceToken('security'));

  try {
    // 1. Migrar permissões e usuários (Banco Security)
    console.log('\n[1/2] Migrando Permissões e Usuários (Security)...');
    await migrationService.migrateMySQLtoPG(
      path.join(__dirname, '../backup/permissao_mysql.sql'),
      securityDataSource
    );

    // 2. Migrar dados comerciais (Banco Principal - CRM)
    console.log('\n[2/2] Migrando Dados Comerciais (CRM)...');
    await migrationService.migrateMySQLtoPG(
      path.join(__dirname, '../backup/backup_mysql.sql')
    );

    console.log('\n✅ Migração concluída com sucesso!');
    process.exit(0);
  } catch (error) {
    console.error('\n❌ Falha catastrófica na migração:', error);
    process.exit(1);
  }
}

run();
