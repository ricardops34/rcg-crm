import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { PedidoEstado } from './entities/pedido-estado.entity';
import { Pedido } from './entities/pedido.entity';
import { PedidoItem } from './entities/pedido-item.entity';
import { SyncSalesService } from './services/sync-sales/sync-sales.service';
import { SyncSalesController } from './controllers/sync-sales/sync-sales.controller';
import { MasterDataModule } from '../master-data/master-data.module';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      PedidoEstado,
      Pedido,
      PedidoItem
    ]),
    MasterDataModule
  ],
  exports: [TypeOrmModule],
  providers: [SyncSalesService],
  controllers: [SyncSalesController],
})
export class SalesModule {}
