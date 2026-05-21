import { ApiProperty } from '@nestjs/swagger';

export class TabelaPrecoResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'TAB01', description: 'Código ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'Tabela Atacado MS' })
  descricao: string;
}

export class TabelaPrecoItemResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 105, description: 'ID do Produto' })
  produtoId: number;

  @ApiProperty({ example: 45.90, description: 'Preço unitário' })
  preco: number;
}
