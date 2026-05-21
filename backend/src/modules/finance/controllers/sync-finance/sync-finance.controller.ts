import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { SyncFinanceService } from '../../services/sync-finance/sync-finance.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
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
  async syncTitulos(@Body() dto: SyncBatchDto) {
    return this.syncService.syncTitulos(dto.conteudo);
  }
}
