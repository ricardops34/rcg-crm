import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { TituloReceber } from './entities/titulo-receber.entity';
import { Natureza } from './entities/natureza.entity';
import { Negociacao } from './entities/negociacao.entity';
import { NegociacaoTituloReceber } from './entities/negociacao-titulo-receber.entity';
import { MasterDataModule } from '../master-data/master-data.module';
import { AdminModule } from '../admin/admin.module';
import { CrmModule } from '../crm/crm.module';
import { SyncFinanceService } from './services/sync-finance/sync-finance.service';
import { SyncFinanceController } from './controllers/sync-finance/sync-finance.controller';
import { NegociacaoService } from './services/negociacao.service';
import { NegociacaoController } from './controllers/negociacao.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      TituloReceber,
      Natureza,
      Negociacao,
      NegociacaoTituloReceber,
    ]),
    MasterDataModule,
    AdminModule,
    CrmModule,
  ],
  exports: [TypeOrmModule, NegociacaoService],
  providers: [SyncFinanceService, NegociacaoService],
  controllers: [SyncFinanceController, NegociacaoController],
})
export class FinanceModule {}
