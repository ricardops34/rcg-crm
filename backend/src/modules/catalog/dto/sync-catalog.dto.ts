import { ApiProperty } from '@nestjs/swagger';
import { IsArray, IsNotEmpty, IsString, IsOptional, IsIn } from 'class-validator';

// ==========================================
// CATEGORIA
// ==========================================
export class CategoriaSyncItemDto {
  @ApiProperty({ example: 'CAT01', description: 'Código identificador da categoria no ERP de origem' })
  @IsString()
  @IsNotEmpty()
  cod_erp: string;

  @ApiProperty({ example: 'Rações e Alimentos', description: 'Descrição/Nome da categoria' })
  @IsString()
  @IsNotEmpty()
  descricao: string;

  @ApiProperty({ example: 'A', description: 'Status da categoria (A - Ativo, I - Inativo)', required: false, default: 'A' })
  @IsOptional()
  @IsString()
  @IsIn(['A', 'I'])
  status?: string;

  @ApiProperty({ example: 'S', description: 'Indica se a categoria é usada no sistema (S - Sim / N - Não)', required: false, default: 'S' })
  @IsOptional()
  @IsString()
  @IsIn(['S', 'N'])
  usado?: string;

  @ApiProperty({ example: 'S', description: 'Indica se a categoria é exibida no site/e-commerce (S - Sim / N - Não)', required: false, default: 'N' })
  @IsOptional()
  @IsString()
  @IsIn(['S', 'N'])
  site?: string;

  @ApiProperty({ example: 'FIL01', description: 'Código ERP da filial/unidade organizacional vinculada', required: false })
  @IsOptional()
  @IsString()
  system_unit?: string;
}

export class SyncCategoriasDto {
  @ApiProperty({
    description: 'Lote de categorias para sincronização em lote',
    type: [CategoriaSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: CategoriaSyncItemDto[];
}

// ==========================================
// SUB-CATEGORIA
// ==========================================
export class SubCategoriaSyncItemDto {
  @ApiProperty({ example: 'SUBCAT01', description: 'Código identificador da subcategoria no ERP' })
  @IsString()
  @IsNotEmpty()
  cod_erp: string;

  @ApiProperty({ example: 'Ração Premium para Cães', description: 'Descrição da subcategoria' })
  @IsString()
  @IsNotEmpty()
  descricao: string;

  @ApiProperty({ example: 'A', description: 'Status da subcategoria (A - Ativo, I - Inativo)', required: false, default: 'A' })
  @IsOptional()
  @IsString()
  @IsIn(['A', 'I'])
  status?: string;

  @ApiProperty({ example: 'CAT01', description: 'Código ERP da categoria pai vinculada' })
  @IsString()
  @IsNotEmpty()
  categoria: string;
}

export class SyncSubCategoriasDto {
  @ApiProperty({
    description: 'Lote de subcategorias para sincronização em lote',
    type: [SubCategoriaSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: SubCategoriaSyncItemDto[];
}

// ==========================================
// FABRICANTE
// ==========================================
export class FabricanteSyncItemDto {
  @ApiProperty({ example: 'FAB01', description: 'Código identificador do fabricante no ERP' })
  @IsString()
  @IsNotEmpty()
  cod_erp: string;

  @ApiProperty({ example: 'Nestlé Purina', description: 'Nome/Razão Social do fabricante' })
  @IsString()
  @IsNotEmpty()
  descricao: string;

  @ApiProperty({ example: 'FIL01', description: 'Código ERP da filial/unidade organizacional vinculada', required: false })
  @IsOptional()
  @IsString()
  system_unit?: string;
}

export class SyncFabricantesDto {
  @ApiProperty({
    description: 'Lote de fabricantes para sincronização em lote',
    type: [FabricanteSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: FabricanteSyncItemDto[];
}

// ==========================================
// ARMAZEM (Depósito / Estoque)
// ==========================================
export class ArmazemSyncItemDto {
  @ApiProperty({ example: 'ARM01', description: 'Código identificador do armazém no ERP' })
  @IsString()
  @IsNotEmpty()
  cod_erp: string;

  @ApiProperty({ example: 'Depósito Central RCG', description: 'Descrição/Nome do armazém' })
  @IsString()
  @IsNotEmpty()
  descricao: string;

  @ApiProperty({ example: 'A', description: 'Status do armazém (A - Ativo, I - Inativo)', required: false, default: 'A' })
  @IsOptional()
  @IsString()
  @IsIn(['A', 'I'])
  status?: string;

  @ApiProperty({ example: 'FIL01', description: 'Código ERP da filial/unidade organizacional vinculada (mapeado para system_unit_id)', required: false })
  @IsOptional()
  @IsString()
  system_unit?: string;
}

export class SyncArmazensDto {
  @ApiProperty({
    description: 'Lote de armazéns/depósitos para sincronização em lote',
    type: [ArmazemSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: ArmazemSyncItemDto[];
}
