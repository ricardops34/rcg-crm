import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { SyncFinanceService } from '../../services/sync-finance/sync-finance.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('sync/finance')
@UseGuards(JwtAuthGuard)
export class SyncFinanceController {
  constructor(private readonly syncService: SyncFinanceService) {}

  @Post('titulos')
  async syncTitulos(@Body() dto: SyncBatchDto) {
    return this.syncService.syncTitulos(dto.conteudo);
  }
}
