import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { TituloReceber } from './entities/titulo-receber.entity';
import { Natureza } from './entities/natureza.entity';
import { MasterDataModule } from '../master-data/master-data.module';
import { AdminModule } from '../admin/admin.module';
import { SyncFinanceService } from './services/sync-finance/sync-finance.service';
import { SyncFinanceController } from './controllers/sync-finance/sync-finance.controller';
@Module({
  imports: [TypeOrmModule.forFeature([TituloReceber, Natureza]), MasterDataModule, AdminModule],
  exports: [TypeOrmModule],
  providers: [SyncFinanceService],
  controllers: [SyncFinanceController],
})
export class FinanceModule {}
