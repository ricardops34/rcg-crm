import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { TituloReceber } from './entities/titulo-receber.entity';
import { SyncFinanceService } from './services/sync-finance/sync-finance.service';
import { SyncFinanceController } from './controllers/sync-finance/sync-finance.controller';
import { MasterDataModule } from '../master-data/master-data.module';

@Module({
  imports: [TypeOrmModule.forFeature([TituloReceber]), MasterDataModule],
  exports: [TypeOrmModule],
  providers: [SyncFinanceService],
  controllers: [SyncFinanceController],
})
export class FinanceModule {}
