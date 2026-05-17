import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { SyncSalesService } from '../../services/sync-sales/sync-sales.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('sync/sales')
@UseGuards(JwtAuthGuard)
export class SyncSalesController {
  constructor(private readonly syncService: SyncSalesService) {}

  @Post('pedidos')
  async syncPedidos(@Body() dto: SyncBatchDto) { return this.syncService.syncPedidos(dto.conteudo); }
}
