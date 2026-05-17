import { NestFactory } from '@nestjs/core';
import { ValidationPipe } from '@nestjs/common';
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

  const dataSource = app.get('DataSource');
  if (process.env.DB_SEED === 'true') {
    const { seed } = require('../seed');
    await seed(dataSource);
  }

  await app.listen(3000);
}
bootstrap();
