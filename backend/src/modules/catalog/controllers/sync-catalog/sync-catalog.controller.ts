import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { SyncCatalogService } from '../../services/sync-catalog/sync-catalog.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Integrações / Sincronização ERP')
@ApiBearerAuth()
@Controller('sync/catalog')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SyncManager')
export class SyncCatalogController {
  constructor(private readonly syncService: SyncCatalogService) {}

  @Post('categorias')
  async syncCategorias(@Body() dto: SyncBatchDto) {
    return this.syncService.syncCategorias(dto.conteudo);
  }

  @Post('sub-categorias')
  async syncSubCategorias(@Body() dto: SyncBatchDto) {
    return this.syncService.syncSubCategorias(dto.conteudo);
  }

  @Post('fabricantes')
  async syncFabricantes(@Body() dto: SyncBatchDto) {
    return this.syncService.syncFabricantes(dto.conteudo);
  }

  @Post('armazens')
  async syncArmazens(@Body() dto: SyncBatchDto) {
    return this.syncService.syncArmazens(dto.conteudo);
  }

  @Post('produtos')
  async syncProdutos(@Body() dto: SyncBatchDto) {
    return this.syncService.syncProdutos(dto.conteudo);
  }
}
