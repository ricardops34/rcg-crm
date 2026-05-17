import { NestFactory } from '@nestjs/core';
import { ValidationPipe } from '@nestjs/common';
import { AppModule } from './app.module';

import { seed } from '../seed';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);
  
  // Habilitar CORS para o Frontend Angular
  app.enableCORS();
  
  // Habilitar validação global via DTOs
  app.useGlobalPipes(new ValidationPipe({
    whitelist: true,
    forbidNonWhitelisted: true,
    transform: true,
  }));

  const dataSource = app.get('DataSource');
  if (process.env.DB_SEED === 'true') await seed(dataSource);
  await app.listen(3000);
}
bootstrap();

