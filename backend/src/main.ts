import { NestFactory } from '@nestjs/core';
import { ValidationPipe } from '@nestjs/common';
import { DocumentBuilder, SwaggerModule } from '@nestjs/swagger';
import { json, urlencoded } from 'express';
import { AppModule } from './app.module';
import * as express from 'express';
import * as path from 'path';

async function bootstrap() {
  const app = await NestFactory.create(AppModule);

  // Servir arquivos físicos de uploads de forma estática
  app.use('/uploads', express.static(path.resolve(process.cwd(), 'uploads')));

  // Aumentar o limite do corpo da requisição para aceitar imagens em Base64 (upload de logos/favicons)
  app.use(json({ limit: '10mb' }));
  app.use(urlencoded({ limit: '10mb', extended: true }));

  // Habilitar CORS para o Frontend (Local, Docker e Produção)
  app.enableCors({
    origin: true, // Reflete dinamicamente a origem que fez a requisição, permitindo múltiplos subdomínios com credentials
    methods: 'GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS',
    credentials: true,
  });

  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      forbidNonWhitelisted: true,
      transform: true,
    }),
  );

  const swaggerConfig = new DocumentBuilder()
    .setTitle('RCG CRM API')
    .setDescription(
      'Documentação OpenAPI dos módulos administrativo, comercial, CRM, financeiro e analítico.',
    )
    .setVersion('1.0.0')
    .addBearerAuth(
      {
        type: 'http',
        scheme: 'bearer',
        bearerFormat: 'JWT',
        description: 'Informe o token JWT recebido no login.',
      },
      'bearer',
    )
    .build();

  const swaggerDocument = SwaggerModule.createDocument(app, swaggerConfig, {
    deepScanRoutes: true,
  });
  swaggerDocument.security = [{ bearer: [] }];

  SwaggerModule.setup('docs', app, swaggerDocument, {
    swaggerOptions: {
      persistAuthorization: true,
      docExpansion: 'none',
      tagsSorter: 'alpha',
      operationsSorter: 'alpha',
    },
    customSiteTitle: 'RCG CRM API Docs',
  });

  await app.listen(3000);
}
bootstrap();
