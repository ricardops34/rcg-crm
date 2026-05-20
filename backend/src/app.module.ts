import { Module } from '@nestjs/common';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import { CacheModule } from '@nestjs/cache-manager';
import { ClsModule } from 'nestjs-cls';
import { redisStore } from 'cache-manager-redis-yet';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { MigrationDataService } from './migration';
import { MasterDataModule } from './modules/master-data/master-data.module';
import { CommercialModule } from './modules/commercial/commercial.module';
import { CatalogModule } from './modules/catalog/catalog.module';
import { SalesModule } from './modules/sales/sales.module';
import { BillingModule } from './modules/billing/billing.module';
import { FinanceModule } from './modules/finance/finance.module';
import { CrmModule } from './modules/crm/crm.module';
import { AnalyticsModule } from './modules/analytics/analytics.module';
import { AdminModule } from './modules/admin/admin.module';
import { AuthModule } from './modules/auth/auth.module';

@Module({
  imports: [
    ConfigModule.forRoot({
      isGlobal: true,
      envFilePath: '.env',
    }),
    TypeOrmModule.forRootAsync({
      imports: [ConfigModule],
      inject: [ConfigService],
      useFactory: (configService: ConfigService) => ({
        type: 'postgres',
        host: configService.get<string>('DB_HOST'),
        port: configService.get<number>('DB_PORT'),
        username: configService.get<string>('DB_USERNAME'),
        password: configService.get<string>('DB_PASSWORD'),
        database: configService.get<string>('DB_DATABASE'),
        autoLoadEntities: true,
        synchronize: configService.get<boolean>('DB_SYNC'),
        retryAttempts: 10,
        retryDelay: 3000,
      }),
    }),
    TypeOrmModule.forRootAsync({
      name: 'security',
      imports: [ConfigModule],
      inject: [ConfigService],
      useFactory: (configService: ConfigService) => ({
        type: 'postgres',
        host: configService.get<string>('DB_HOST'),
        port: configService.get<number>('DB_PORT'),
        username: configService.get<string>('DB_USERNAME'),
        password: configService.get<string>('DB_PASSWORD'),
        database:
          configService.get<string>('DB_SECURITY_DATABASE') ||
          configService.get<string>('DB_DATABASE'),
        autoLoadEntities: true,
        synchronize: configService.get<boolean>('DB_SYNC'),
        retryAttempts: 10,
        retryDelay: 3000,
      }),
    }),
    CacheModule.registerAsync({
      isGlobal: true,
      imports: [ConfigModule],
      inject: [ConfigService],
      useFactory: async (configService: ConfigService) => {
        const redisHost = configService.get<string>('REDIS_HOST');
        if (redisHost && redisHost !== 'localhost') {
          // Evitar localhost se não estiver rodando
          try {
            return {
              store: await redisStore({
                socket: {
                  host: redisHost,
                  port: configService.get<number>('REDIS_PORT', 6379),
                },
              }),
            };
          } catch (e) {
            console.warn(
              '⚠️ Falha ao conectar no Redis, usando memory store:',
              e.message,
            );
          }
        }
        return { store: 'memory' };
      },
    }),
    ClsModule.forRoot({
      global: true,
      middleware: { mount: true },
    }),
    MasterDataModule,
    CommercialModule,
    CatalogModule,
    SalesModule,
    BillingModule,
    FinanceModule,
    CrmModule,
    AnalyticsModule,
    AdminModule,
    AuthModule,
  ],
  controllers: [AppController],
  providers: [AppService, MigrationDataService],
})
export class AppModule {}
