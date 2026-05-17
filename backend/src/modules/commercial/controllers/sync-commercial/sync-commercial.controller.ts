import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { SyncCommercialService } from '../../services/sync-commercial/sync-commercial.service';
import { SyncBatchDto } from '../../dto/sync-batch.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('sync/commercial')
@UseGuards(JwtAuthGuard)
export class SyncCommercialController {
  constructor(private readonly syncService: SyncCommercialService) {}

  @Post('clientes')
  async syncClientes(@Body() syncBatchDto: SyncBatchDto) {
    return this.syncService.syncClientes(syncBatchDto.conteudo);
  }
}
