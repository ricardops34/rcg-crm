import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiBody, ApiResponse } from '@nestjs/swagger';
import { SyncFinanceService } from '../../services/sync-finance/sync-finance.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { SyncResultDto } from '../../../commercial/dto/sync-items.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Integrações / Sincronização ERP')
@ApiBearerAuth()
@Controller('sync/finance')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SyncManager')
export class SyncFinanceController {
  constructor(private readonly syncService: SyncFinanceService) {}

  @Post('titulos')
  @ApiOperation({ summary: 'Sincroniza lote de títulos a receber do ERP' })
  @ApiBody({ type: SyncBatchDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncTitulos(@Body() dto: SyncBatchDto) {
    return this.syncService.syncTitulos(dto.conteudo);
  }
}
