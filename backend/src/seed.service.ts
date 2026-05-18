import { Injectable, OnApplicationBootstrap, Inject } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { getDataSourceToken } from '@nestjs/typeorm';
import { DataSource } from 'typeorm';
import { seed } from './seed';

@Injectable()
export class SeedService implements OnApplicationBootstrap {
  constructor(
    private configService: ConfigService,
    @Inject(getDataSourceToken())
    private crmDataSource: DataSource,
    @Inject(getDataSourceToken('security'))
    private securityDataSource: DataSource,
  ) {}

  async onApplicationBootstrap() {
    console.log(`🔍 Verificando DB_SEED: ${this.configService.get('DB_SEED')}`);
    if (this.configService.get('DB_SEED') === 'true') {
      try {
        console.log('🚀 Iniciando processo de Seed Multi-DB...');
        await seed(this.crmDataSource, this.securityDataSource);
        console.log('✨ Seed finalizado com sucesso via SeedService');
      } catch (e) {
        console.error('⚠️ Falha ao executar Seed no startup:', e.message);
      }
    }
  }
}
