import { ApiProperty } from '@nestjs/swagger';

export class SyncItemClienteDto {
  @ApiProperty({ example: 'C00542', description: 'Código identificador do cliente no ERP de origem' })
  cod_erp: string;

  @ApiProperty({ example: 'AUTO POSTO RCG LTDA', description: 'Razão Social da empresa' })
  razao: string;

  @ApiProperty({ example: 'POSTO RCG', description: 'Nome Fantasia' })
  fantasia: string;

  @ApiProperty({ example: '00.123.456/0001-01', description: 'CNPJ ou CPF formatado' })
  cnpj_cpf: string;

  @ApiProperty({ example: 'V012', description: 'Código do Vendedor vinculado (ERP)' })
  vendedor: string;

  @ApiProperty({ example: 'MS', description: 'UF do endereço principal' })
  uf: string;
}

export class SyncItemProdutoDto {
  @ApiProperty({ example: 'P0122', description: 'Código identificador do produto no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'OLEO LUBRIFICANTE 5W30', description: 'Descrição completa do produto' })
  descricao: string;

  @ApiProperty({ example: 'LITRO', description: 'Unidade de medida' })
  um: string;

  @ApiProperty({ example: 45.90, description: 'Preço de venda padrão' })
  preco: number;
}

export class SyncResultDto {
  @ApiProperty({ example: 'success', enum: ['success', 'error'] })
  status: string;

  @ApiProperty({ example: 'C00542', description: 'Código ERP do registro processado' })
  cod_erp: string;

  @ApiProperty({ example: 'Registro processado com sucesso', required: false })
  message?: string;
}
