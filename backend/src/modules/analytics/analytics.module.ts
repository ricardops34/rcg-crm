import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AnalyticsService } from './services/analytics/analytics.service';
import { AnalyticsController } from './controllers/analytics/analytics.controller';
import { Vendedor } from '../commercial/entities/vendedor.entity';
import { VendaMesCliente } from './entities/venda-mes-cliente.entity';
import { VendaMesProduto } from './entities/venda-mes-produto.entity';
import { AdminModule } from '../admin/admin.module';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      Vendedor, 
      VendaMesCliente, 
      VendaMesProduto
    ]), 
    AdminModule
  ],
  providers: [AnalyticsService],
  controllers: [AnalyticsController],
})
export class AnalyticsModule {}
