import { ApiProperty, PartialType } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional, IsNumber, MaxLength } from 'class-validator';

export class CreateProdutoDto {
  @ApiProperty({ example: 1, description: 'ID da Filial' })
  @IsNumber()
  @IsOptional()
  filialId?: number;

  @ApiProperty({ example: 'PROD001', description: 'Código do produto no ERP', maxLength: 30 })
  @IsString()
  @IsNotEmpty({ message: 'O código é obrigatório' })
  codErp: string;

  @ApiProperty({ example: 'S', description: 'Situação (S-Ativo, N-Bloqueado)', enum: ['S', 'N'], default: 'S' })
  @IsString()
  @IsOptional()
  status?: string;

  @ApiProperty({ example: 'PA', description: 'Tipo do produto', maxLength: 2, required: false })
  @IsString()
  @IsOptional()
  tipo?: string;

  @ApiProperty({ example: 'OLEO LUBRIFICANTE 5W30', description: 'Descrição completa', maxLength: 100 })
  @IsString()
  @IsNotEmpty({ message: 'A descrição é obrigatória' })
  descricao: string;

  @ApiProperty({ example: 'SHELL', description: 'Marca do produto', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  marca?: string;

  @ApiProperty({ example: 'L', description: 'Unidade de Medida', maxLength: 2, required: false })
  @IsString()
  @IsOptional()
  um?: string;

  @ApiProperty({ example: 5, description: 'ID da Categoria', required: false })
  @IsNumber()
  @IsOptional()
  categoriaId?: number;

  @ApiProperty({ example: 10, description: 'ID da Subcategoria', required: false })
  @IsNumber()
  @IsOptional()
  subCategoriaId?: number;

  @ApiProperty({ example: '27101932', description: 'NCM do produto', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  ncm?: string;

  @ApiProperty({ example: 2, description: 'ID do Fabricante', required: false })
  @IsNumber()
  @IsOptional()
  fabricanteId?: number;

  @ApiProperty({ example: 'COD-FAB-99', description: 'Código no fabricante', maxLength: 60, required: false })
  @IsString()
  @IsOptional()
  codigoFabricante?: string;

  @ApiProperty({ example: 1, description: 'ID do Armazém padrão', required: false })
  @IsNumber()
  @IsOptional()
  armazemId?: number;

  @ApiProperty({ example: 1, description: 'ID do Tipo de Saída padrão', required: false })
  @IsNumber()
  @IsOptional()
  tsId?: number;

  @ApiProperty({ example: 1, description: 'ID do Tipo de Entrada padrão', required: false })
  @IsNumber()
  @IsOptional()
  teId?: number;

  @ApiProperty({ example: 1.5, description: 'Peso Bruto', required: false })
  @IsNumber()
  @IsOptional()
  pesoBruto?: number;

  @ApiProperty({ example: 1.4, description: 'Peso Líquido', required: false })
  @IsNumber()
  @IsOptional()
  peso?: number;

  @ApiProperty({ example: 12, description: 'Quantidade por embalagem', required: false })
  @IsNumber()
  @IsOptional()
  qtdEmbalagem?: number;

  @ApiProperty({ example: 'Uso recomendado em motores ciclo Otto', description: 'Observações gerais', required: false })
  @IsString()
  @IsOptional()
  observacao?: string;
}

export class UpdateProdutoDto extends PartialType(CreateProdutoDto) {}

export class ProdutoResponseDto extends CreateProdutoDto {
  @ApiProperty({ example: 1, description: 'ID interno do sistema' })
  id: number;

  @ApiProperty({ example: 100, description: 'Saldo atual em estoque', readOnly: true })
  saldoEstoque: number;

  @ApiProperty({ example: 20, description: 'Ponto de pedido', readOnly: true })
  pontoPedido: number;
}
