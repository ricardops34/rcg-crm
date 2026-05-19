import { Controller, Post, Body, UseGuards } from '@nestjs/common';
import { SyncMasterDataService } from '../../services/sync-master-data/sync-master-data.service';
import { SyncBatchDto } from '../../../commercial/dto/sync-batch.dto';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('sync/master-data')
@UseGuards(JwtAuthGuard)
export class SyncMasterDataController {
  constructor(private readonly syncService: SyncMasterDataService) {}

  @Post('estados')
  async syncEstados(@Body() dto: SyncBatchDto) {
    return this.syncService.syncEstados(dto.conteudo);
  }

  @Post('municipios')
  async syncMunicipios(@Body() dto: SyncBatchDto) {
    return this.syncService.syncMunicipios(dto.conteudo);
  }

  @Post('filiais')
  async syncFiliais(@Body() dto: SyncBatchDto) {
    return this.syncService.syncFiliais(dto.conteudo);
  }

  @Post('clientes')
  async syncClientes(@Body() dto: SyncBatchDto) {
    return this.syncService.syncClientes(dto.conteudo);
  }

  @Post('produtos')
  async syncProdutos(@Body() dto: SyncBatchDto) {
    return this.syncService.syncProdutos(dto.conteudo);
  }

  @Post('vendedores')
  async syncVendedores(@Body() dto: SyncBatchDto) {
    return this.syncService.syncVendedores(dto.conteudo);
  }

  @Post('tabelas-preco')
  async syncTabelasPreco(@Body() dto: SyncBatchDto) {
    return this.syncService.syncTabelasPreco(dto.conteudo);
  }
}
