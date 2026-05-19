import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { seed } from './seed';
import { DataSource } from 'typeorm';
import { getDataSourceToken } from '@nestjs/typeorm';

async function bootstrap() {
  console.log('🚀 Iniciando contexto para Seeding Multi-DB...');
  const app = await NestFactory.createApplicationContext(AppModule);

  try {
    const crmDataSource = app.get(DataSource);
    const securityDataSource = app.get(getDataSourceToken('security'));
    await seed(crmDataSource, securityDataSource);
  } catch (error) {
    console.error('❌ Erro durante o seeding:', error);
  } finally {
    await app.close();
    console.log('👋 Contexto fechado.');
  }
}

bootstrap();
