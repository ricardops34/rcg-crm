import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { seed } from './seed';
import { DataSource } from 'typeorm';

async function bootstrap() {
  console.log('🚀 Iniciando contexto para Seeding...');
  const app = await NestFactory.createApplicationContext(AppModule);
  
  try {
    const dataSource = app.get(DataSource);
    await seed(dataSource);
  } catch (error) {
    console.error('❌ Erro durante o seeding:', error);
  } finally {
    await app.close();
    console.log('👋 Contexto fechado.');
  }
}

bootstrap();
