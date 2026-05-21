import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { SyncCommercialService } from '../../services/sync-commercial/sync-commercial.service';
import { SyncClientesDto } from '../../../master-data/dto/sync-detailed.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Integrações / Sincronização ERP')
@ApiBearerAuth()
@Controller('sync/commercial')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SyncManager')
export class SyncCommercialController {
  constructor(private readonly syncService: SyncCommercialService) {}

  @Post('clientes')
  @ApiOperation({ summary: 'Sincroniza lote de clientes do ERP para o CRM' })
  async syncClientes(@Body() syncBatchDto: SyncClientesDto) {
    return this.syncService.syncClientes(syncBatchDto.conteudo);
  }
}
