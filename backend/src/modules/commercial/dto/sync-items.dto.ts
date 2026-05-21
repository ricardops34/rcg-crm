import { ApiProperty } from '@nestjs/swagger';

export class SyncItemClienteDto {
  @ApiProperty({ example: 'C00542', description: 'Código identificador do cliente no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'AUTO POSTO RCG LTDA', description: 'Razão Social' })
  razao: string;

  @ApiProperty({ example: 'POSTO RCG', description: 'Nome Fantasia' })
  fantasia: string;

  @ApiProperty({ example: '00.123.456/0001-01', description: 'CNPJ ou CPF' })
  cnpj_cpf: string;

  @ApiProperty({ example: 'V012', description: 'Código ERP do Vendedor' })
  vendedor: string;

  @ApiProperty({ example: 'MS', description: 'UF' })
  uf: string;

  @ApiProperty({ example: '500308', description: 'Código IBGE do Município (sem o prefixo da UF)', required: false })
  cod_ibge?: string;

  @ApiProperty({ example: 'TAB01', description: 'Código ERP da Tabela de Preço', required: false })
  tabela_preco?: string;

  @ApiProperty({ example: 'C01', description: 'Código ERP da Condição de Pagamento', required: false })
  cond_pgto?: string;

  @ApiProperty({ example: 'SEG01', description: 'Código ERP do Segmento', required: false })
  seguimento?: string;

  @ApiProperty({ example: 'REG01', description: 'Código ERP da Região', required: false })
  regiao?: string;

  @ApiProperty({ example: 'Ativa', description: 'Situação Cadastral (Ativa, Bloqueada, etc)', required: false })
  situacao_cadastral?: string;

  @ApiProperty({ example: 'origem_erp', description: 'Identificador do sistema de origem', required: false })
  origem?: string;

  @ApiProperty({ example: 5000.00, description: 'Limite de crédito', required: false })
  limite?: number;

  @ApiProperty({ example: 'M', description: 'Risco de crédito', required: false })
  risco?: string;
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
