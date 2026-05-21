import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiBody, ApiResponse } from '@nestjs/swagger';
import { SyncSalesService } from '../../services/sync-sales/sync-sales.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { SyncResultDto } from '../../../commercial/dto/sync-items.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Integrações / Sincronização ERP')
@ApiBearerAuth()
@Controller('sync/sales')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SyncManager')
export class SyncSalesController {
  constructor(private readonly syncService: SyncSalesService) {}

  @Post('pedidos')
  @ApiOperation({ summary: 'Sincroniza lote de pedidos de venda do ERP' })
  @ApiBody({ type: SyncBatchDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncPedidos(@Body() dto: SyncBatchDto) {
    return this.syncService.syncPedidos(dto.conteudo);
  }
}
