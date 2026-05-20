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
    const crmDataSource = app.get(getDataSourceToken());

    try {
      console.log('\n🧹 Limpando dados de teste antes da migração...');
      
      // Limpar Security
      await securityDataSource.query('TRUNCATE system_user_group, system_group_program, system_users, system_group, system_program, system_unit, system_preference, system_user_program, system_user_unit CASCADE');
      
      // Limpar CRM (Tabelas Principais)
      await crmDataSource.query('TRUNCATE vendedor, filial, cliente, nota_saida, titulo_receber, atendimento, produto CASCADE');
      
      // 1. Migrar permissões e usuários (Banco Security)
      console.log('\n[1/2] Migrando Permissões e Usuários Reais (Security)...');
      await migrationService.migrateMySQLtoPG(
        path.join(__dirname, '../backup/permissao_mysql.sql'),
        securityDataSource
      );

      // 2. Migrar dados comerciais (Banco Principal - CRM)
      console.log('\n[2/2] Migrando Dados Comerciais Reais (CRM)...');
      await migrationService.migrateMySQLtoPG(
        path.join(__dirname, '../backup/backup_mysql.sql'),
        crmDataSource
      );

    console.log('\n✅ Migração concluída com sucesso!');
    process.exit(0);
  } catch (error) {
    console.error('\n❌ Falha catastrófica na migração:', error);
    process.exit(1);
  }
}

run();
