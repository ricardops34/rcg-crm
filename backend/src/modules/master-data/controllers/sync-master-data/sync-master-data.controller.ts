import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiBody, ApiResponse, ApiUnauthorizedResponse, ApiForbiddenResponse } from '@nestjs/swagger';
import { SyncMasterDataService } from '../../services/sync-master-data/sync-master-data.service';
import {
  SyncEstadosDto,
  SyncMunicipiosDto,
  SyncFiliaisDto,
  SyncClientesDto,
  SyncProdutosDto,
  SyncVendedoresDto,
  SyncTabelasPrecoDto,
} from '../../dto/sync-detailed.dto';
import { SyncResultDto } from '../../../commercial/dto/sync-items.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Integrações / Sincronização ERP')
@ApiBearerAuth()
@ApiUnauthorizedResponse({ description: 'Token JWT inválido, expirado ou sessão derrubada.' })
@ApiForbiddenResponse({ description: 'O usuário autenticado não possui a permissão "SyncManager".' })
@Controller('sync/master-data')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SyncManager')
export class SyncMasterDataController {
  constructor(private readonly syncService: SyncMasterDataService) {}

  @Post('estados')
  @ApiOperation({ summary: 'Sincroniza lote de estados/UFs do ERP para o CRM' })
  @ApiBody({ type: SyncEstadosDto })
  @ApiResponse({ status: 200, type: [SyncResultDto], description: 'Lote processado. Retorna o status individual de cada registro.' })
  async syncEstados(@Body() dto: SyncEstadosDto) {
    return this.syncService.syncEstados(dto.conteudo);
  }

  @Post('municipios')
  @ApiOperation({ summary: 'Sincroniza lote de municípios do ERP para o CRM' })
  @ApiBody({ type: SyncMunicipiosDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncMunicipios(@Body() dto: SyncMunicipiosDto) {
    return this.syncService.syncMunicipios(dto.conteudo);
  }

  @Post('filiais')
  @ApiOperation({ summary: 'Sincroniza lote de filiais da empresa do ERP para o CRM' })
  @ApiBody({ type: SyncFiliaisDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncFiliais(@Body() dto: SyncFiliaisDto) {
    return this.syncService.syncFiliais(dto.conteudo);
  }

  @Post('clientes')
  @ApiOperation({ summary: 'Sincroniza lote de clientes gerais do ERP para o CRM' })
  @ApiBody({ type: SyncClientesDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncClientes(@Body() dto: SyncClientesDto) {
    return this.syncService.syncClientes(dto.conteudo);
  }

  @Post('produtos')
  @ApiOperation({ summary: 'Sincroniza lote de produtos e itens do ERP para o CRM' })
  @ApiBody({ type: SyncProdutosDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncProdutos(@Body() dto: SyncProdutosDto) {
    return this.syncService.syncProdutos(dto.conteudo);
  }

  @Post('vendedores')
  @ApiOperation({ summary: 'Sincroniza lote de vendedores do ERP para o CRM' })
  @ApiBody({ type: SyncVendedoresDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncVendedores(@Body() dto: SyncVendedoresDto) {
    return this.syncService.syncVendedores(dto.conteudo);
  }

  @Post('tabelas-preco')
  @ApiOperation({ summary: 'Sincroniza lote de tabelas de preço de venda do ERP para o CRM' })
  @ApiBody({ type: SyncTabelasPrecoDto })
  @ApiResponse({ status: 200, type: [SyncResultDto] })
  async syncTabelasPreco(@Body() dto: SyncTabelasPrecoDto) {
    return this.syncService.syncTabelasPreco(dto.conteudo);
  }
}
