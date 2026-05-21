import { ApiProperty } from '@nestjs/swagger';
import { SyncItemClienteDto, SyncItemProdutoDto } from './sync-items.dto';

export class SyncBatchClienteDto {
  @ApiProperty({ type: [SyncItemClienteDto], description: 'Lista de clientes para sincronização' })
  conteudo: SyncItemClienteDto[];
}

export class SyncBatchProdutoDto {
  @ApiProperty({ type: [SyncItemProdutoDto], description: 'Lista de produtos para sincronização' })
  conteudo: SyncItemProdutoDto[];
}
