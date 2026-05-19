import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { SyncBillingService } from '../../services/sync-billing/sync-billing.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('sync/billing')
@UseGuards(JwtAuthGuard)
export class SyncBillingController {
  constructor(private readonly syncService: SyncBillingService) {}

  @Post('notas-saida')
  async syncNotasSaida(@Body() dto: SyncBatchDto) {
    return this.syncService.syncNotasSaida(dto.conteudo);
  }
}
