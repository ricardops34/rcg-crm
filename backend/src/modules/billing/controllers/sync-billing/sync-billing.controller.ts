import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { SyncBillingService } from '../../services/sync-billing/sync-billing.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

import { ApiTags, ApiOperation, ApiBearerAuth, ApiBody, ApiResponse } from '@nestjs/swagger';
import { SyncBillingService } from '../../services/sync-billing/sync-billing.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { SyncResultDto } from '../../../commercial/dto/sync-items.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Integrações / Sincronização ERP')
@ApiBearerAuth()
@Controller('sync/billing')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SyncManager')
export class SyncBillingController {
  constructor(private readonly syncService: SyncBillingService) {}

  @Post('notas-saida')
  @ApiOperation({ summary: 'Sincroniza lote de notas fiscais de saída do ERP' })
  @ApiBody({ type: SyncBatchDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncNotasSaida(@Body() dto: SyncBatchDto) {
    return this.syncService.syncNotasSaida(dto.conteudo);
  }
}
