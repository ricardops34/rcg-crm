import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiBody, ApiResponse } from '@nestjs/swagger';
import { SyncCatalogService } from '../../services/sync-catalog/sync-catalog.service';
import { SyncCategoriasDto, SyncSubCategoriasDto, SyncFabricantesDto, SyncArmazensDto } from '../../dto/sync-catalog.dto';
import { SyncProdutosDto } from '../../../master-data/dto/sync-detailed.dto';
import { SyncResultDto } from '../../../commercial/dto/sync-items.dto';
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
  @ApiOperation({ summary: 'Sincroniza lote de categorias de produto do ERP para o CRM' })
  @ApiBody({ type: SyncCategoriasDto })
  @ApiResponse({ status: 200, type: [SyncResultDto], description: 'Lote processado. Retorna o status individual de cada registro.' })
  async syncCategorias(@Body() dto: SyncCategoriasDto) {
    return this.syncService.syncCategorias(dto.conteudo);
  }

  @Post('sub-categorias')
  @ApiOperation({ summary: 'Sincroniza lote de subcategorias de produto do ERP para o CRM' })
  @ApiBody({ type: SyncSubCategoriasDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncSubCategorias(@Body() dto: SyncSubCategoriasDto) {
    return this.syncService.syncSubCategorias(dto.conteudo);
  }

  @Post('fabricantes')
  @ApiOperation({ summary: 'Sincroniza lote de fabricantes de produto do ERP para o CRM' })
  @ApiBody({ type: SyncFabricantesDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncFabricantes(@Body() dto: SyncFabricantesDto) {
    return this.syncService.syncFabricantes(dto.conteudo);
  }

  @Post('armazens')
  @ApiOperation({ summary: 'Sincroniza lote de armazéns/depósitos do ERP para o CRM' })
  @ApiBody({ type: SyncArmazensDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncArmazens(@Body() dto: SyncArmazensDto) {
    return this.syncService.syncArmazens(dto.conteudo);
  }

  @Post('produtos')
  @ApiOperation({ summary: 'Sincroniza lote de produtos e itens do ERP para o CRM' })
  @ApiBody({ type: SyncProdutosDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncProdutos(@Body() dto: SyncProdutosDto) {
    return this.syncService.syncProdutos(dto.conteudo);
  }
}

