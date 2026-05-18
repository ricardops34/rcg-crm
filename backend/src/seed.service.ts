import { Injectable, OnApplicationBootstrap } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { getDataSourceToken } from '@nestjs/typeorm';
import { DataSource } from 'typeorm';
import { ModuleRef } from '@nestjs/core';
import { seed } from '../seed';

@Injectable()
export class SeedService implements OnApplicationBootstrap {
  constructor(
    private configService: ConfigService,
    private moduleRef: ModuleRef,
  ) {}

  async onApplicationBootstrap() {
    if (this.configService.get('DB_SEED') === 'true') {
      try {
        const dataSource = this.moduleRef.get<DataSource>(getDataSourceToken());
        await seed(dataSource);
        console.log('✨ Seed finalizado com sucesso via SeedService');
      } catch (e) {
        console.error('⚠️ Falha ao executar Seed no startup:', e.message);
      }
    }
  }
}
