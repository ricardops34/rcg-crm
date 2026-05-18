import { NestFactory } from '@nestjs/core';
import { ValidationPipe } from '@nestjs/common';
import { getDataSourceToken } from '@nestjs/typeorm';
import { AppModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);

  // Habilitar CORS para o Frontend (Local e Produção)
  app.enableCors({
    origin: ['http://localhost:4200', 'https://crm.bjsoft.com.br'],
    methods: 'GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS',
    credentials: true,
  });

  app.useGlobalPipes(new ValidationPipe({
    whitelist: true,
    forbidNonWhitelisted: true,
    transform: true,
  }));

  if (process.env.DB_SEED === 'true') {
    try {
      const dataSource = app.get(getDataSourceToken());
      const { seed } = require('../seed');
      await seed(dataSource);
    } catch (e) {
      console.error('⚠️ Falha ao executar Seed:', e.message);
    }
  }

  await app.listen(3000);
}
bootstrap();

