import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { NotaSaida } from './entities/nota-saida.entity';
import { NotaSaidaItem } from './entities/nota-saida-item.entity';
import { NotaSaidaXml } from './entities/notasaida-xml.entity';
import { BillingService } from './services/billing.service';
import { BillingController } from './controllers/billing.controller';
import { SyncBillingService } from './services/sync-billing/sync-billing.service';
import { SyncBillingController } from './controllers/sync-billing/sync-billing.controller';
import { MasterDataModule } from '../master-data/master-data.module';
import { AdminModule } from '../admin/admin.module';

@Module({
  imports: [
    TypeOrmModule.forFeature([NotaSaida, NotaSaidaItem, NotaSaidaXml]),
    MasterDataModule,
    AdminModule,
  ],
  exports: [TypeOrmModule, BillingService],
  providers: [SyncBillingService, BillingService],
  controllers: [SyncBillingController, BillingController],
})
export class BillingModule {}
