import { NestFactory } from '@nestjs/core';
import { AppModule } from './src/app.module';
import { seed } from './src/seed';
import { DataSource } from 'typeorm';
import { getDataSourceToken } from '@nestjs/typeorm';

async function run() {
  console.log('🚀 Iniciando script de seeding...');
  const app = await NestFactory.createApplicationContext(AppModule);
  
  try {
    const crmDataSource = app.get(DataSource);
    const securityDataSource = app.get(getDataSourceToken('security'));
    await seed(crmDataSource, securityDataSource);
    console.log('✨ Processo de seeding finalizado com sucesso!');
  } catch (error) {
    console.error('❌ Erro durante o seeding:', error);
  } finally {
    await app.close();
    process.exit(0);
  }
}

run();
