import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { NotaSaida } from './entities/nota-saida.entity';
import { NotaSaidaItem } from './entities/nota-saida-item.entity';
import { SyncBillingService } from './services/sync-billing/sync-billing.service';
import { SyncBillingController } from './controllers/sync-billing/sync-billing.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      NotaSaida,
      NotaSaidaItem
    ])
  ],
  exports: [TypeOrmModule],
  providers: [SyncBillingService],
  controllers: [SyncBillingController],
})
export class BillingModule {}
