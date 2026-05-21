import { ApiProperty } from '@nestjs/swagger';
import { IsArray, IsNotEmpty } from 'class-validator';

// ==========================================
// ESTADO
// ==========================================
export class EstadoSyncItemDto {
  @ApiProperty({ example: 'SP', description: 'Código de identificação do estado no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'SP', description: 'Sigla da unidade federativa' })
  sigla: string;

  @ApiProperty({ example: 'São Paulo', description: 'Nome completo do estado' })
  descricao: string;

  @ApiProperty({ example: '35', description: 'Código IBGE do estado', required: false })
  codigo_ibge?: string;
}

export class SyncEstadosDto {
  @ApiProperty({
    description: 'Lote de estados para sincronização em lote',
    type: [EstadoSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: EstadoSyncItemDto[];
}

// ==========================================
// MUNICIPIO
// ==========================================
export class MunicipioSyncItemDto {
  @ApiProperty({ example: '3550308', description: 'Código do município no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'São Paulo', description: 'Nome do município' })
  descricao: string;

  @ApiProperty({ example: '3550308', description: 'Código IBGE do município', required: false })
  codigo_ibge?: string;

  @ApiProperty({ example: 'SP', description: 'Sigla do estado ao qual o município pertence' })
  estado: string;
}

export class SyncMunicipiosDto {
  @ApiProperty({
    description: 'Lote de municípios para sincronização em lote',
    type: [MunicipioSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: MunicipioSyncItemDto[];
}

// ==========================================
// FILIAL
// ==========================================
export class FilialSyncItemDto {
  @ApiProperty({ example: 'FIL01', description: 'Código da filial no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'EMP01', description: 'Código da empresa controladora', required: false })
  cod_emp?: string;

  @ApiProperty({ example: 'Filial São Paulo Matriz', description: 'Razão social ou nome da filial' })
  nome: string;

  @ApiProperty({ example: 'RCG - SP', description: 'Nome fantasia da filial', required: false })
  fantasia?: string;

  @ApiProperty({ example: 'RCG Filial SP', description: 'Apelido da filial', required: false })
  apelido?: string;

  @ApiProperty({ example: 'S', description: 'Identifica se é a filial matriz (S/N)', required: false })
  matriz?: string;

  @ApiProperty({ example: '12345678000199', description: 'CNPJ da filial', required: false })
  cnpj?: string;

  @ApiProperty({ example: '01310100', description: 'CEP do endereço da filial', required: false })
  cep?: string;

  @ApiProperty({ example: 'Av. Paulista, 1000', description: 'Endereço da filial', required: false })
  endereco?: string;

  @ApiProperty({ example: 'Bela Vista', description: 'Bairro da filial', required: false })
  bairro?: string;

  @ApiProperty({ example: 'São Paulo', description: 'Município da filial', required: false })
  municipio?: string;

  @ApiProperty({ example: 'SP', description: 'Sigla da UF da filial', required: false })
  uf?: string;

  @ApiProperty({ example: 'contato@rcg.com.br', description: 'E-mail de contato da filial', required: false })
  email?: string;

  @ApiProperty({ example: 'A', description: 'Status da filial (A - Ativo, I - Inativo)', required: false })
  status?: string;
}

export class SyncFiliaisDto {
  @ApiProperty({
    description: 'Lote de filiais para sincronização em lote',
    type: [FilialSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: FilialSyncItemDto[];
}

// ==========================================
// CLIENTE
// ==========================================
export class ClienteSyncItemDto {
  @ApiProperty({ example: 'CLI001', description: 'Código do cliente no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'Distribuidora de Alimentos RCG Ltda', description: 'Razão social' })
  razao: string;

  @ApiProperty({ example: 'RCG Alimentos', description: 'Nome fantasia', required: false })
  fantasia?: string;

  @ApiProperty({ example: '12345678000100', description: 'CNPJ ou CPF', required: false })
  cnpj_cpf?: string;

  @ApiProperty({ example: 'A', description: 'Status (A-Ativo, B-Bloqueado)', enum: ['A', 'B'], default: 'A' })
  status?: string;

  @ApiProperty({ example: 'J', description: 'Tipo (F-Física, J-Jurídica)', enum: ['F', 'J'], default: 'J' })
  tipo?: string;

  @ApiProperty({ example: 'Av. Paulista, 1000', description: 'Endereço', required: false })
  endereco?: string;

  @ApiProperty({ example: 'Apto 12', description: 'Complemento', required: false })
  complemento?: string;

  @ApiProperty({ example: 'Bela Vista', description: 'Bairro', required: false })
  bairro?: string;

  @ApiProperty({ example: 'São Paulo', description: 'Município', required: false })
  municipio?: string;

  @ApiProperty({ example: 'SP', description: 'Estado (UF)', required: false })
  uf?: string;

  @ApiProperty({ example: '01310100', description: 'CEP', required: false })
  cep?: string;

  @ApiProperty({ example: 'financeiro@rcg.com.br', description: 'E-mail', required: false })
  email?: string;

  @ApiProperty({ example: '1132224444', description: 'Telefone 1', required: false })
  telefone1?: string;

  @ApiProperty({ example: '11999998888', description: 'Celular', required: false })
  celular?: string;

  @ApiProperty({ example: 'VEND001', description: 'Código ERP do vendedor', required: false })
  vendedor?: string;

  @ApiProperty({ example: 'COND01', description: 'Código ERP da condição pgto', required: false })
  cond_pgto?: string;

  @ApiProperty({ example: 'TAB01', description: 'Código ERP da tabela preco', required: false })
  tabela_preco?: string;

  @ApiProperty({ example: 'SEG01', description: 'Código ERP do segmento', required: false })
  seguimento?: string;

  @ApiProperty({ example: 'Ativa', description: 'Situação cadastral', required: false })
  situacao_cadastral?: string;

  @ApiProperty({ example: 15000.0, description: 'Limite de crédito', required: false })
  limite?: number;

  @ApiProperty({ example: 'M', description: 'Risco de crédito', required: false })
  risco?: string;
}

export class SyncClientesDto {
  @ApiProperty({
    description: 'Lote de clientes para sincronização em lote',
    type: [ClienteSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: ClienteSyncItemDto[];
}

// ==========================================
// PRODUTO
// ==========================================
export class ProdutoSyncItemDto {
  @ApiProperty({ example: 'PROD001', description: 'Código do produto no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'Ração Premiata Cães Adultos 15kg', description: 'Descrição' })
  descricao: string;

  @ApiProperty({ example: 'S', description: 'Situação (S-Ativo, N-Bloqueado)', enum: ['S', 'N'], default: 'S' })
  status?: string;

  @ApiProperty({ example: 'SHELL', description: 'Marca', required: false })
  marca?: string;

  @ApiProperty({ example: 'UN', description: 'Unidade Medida', required: false })
  um?: string;

  @ApiProperty({ example: 'CAT01', description: 'Código ERP Categoria', required: false })
  categoria?: string;

  @ApiProperty({ example: 'SCAT01', description: 'Código ERP Subcategoria', required: false })
  sub_categoria?: string;

  @ApiProperty({ example: 'FAB01', description: 'Código ERP Fabricante', required: false })
  fabricante?: string;

  @ApiProperty({ example: 'ARMA01', description: 'Código ERP Armazem', required: false })
  armazem?: string;

  @ApiProperty({ example: 'NCM01', description: 'NCM', required: false })
  ncm?: string;

  @ApiProperty({ example: 129.9, description: 'Preço base', required: false })
  preco?: number;
}

export class SyncProdutosDto {
  @ApiProperty({
    description: 'Lote de produtos para sincronização em lote',
    type: [ProdutoSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: ProdutoSyncItemDto[];
}

// ==========================================
// VENDEDOR
// ==========================================
export class VendedorSyncItemDto {
  @ApiProperty({ example: 'VEND001', description: 'Código do vendedor no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'João da Silva Vendedor', description: 'Nome do vendedor' })
  nome: string;

  @ApiProperty({ example: 'joao.silva@rcg.com.br', description: 'E-mail do vendedor', required: false })
  email?: string;

  @ApiProperty({ example: '11999998888', description: 'Telefone celular do vendedor', required: false })
  celular?: string;

  @ApiProperty({ example: 'A', description: 'Status (A - Ativo, I - Inativo)', required: false })
  status?: string;
}

export class SyncVendedoresDto {
  @ApiProperty({
    description: 'Lote de vendedores para sincronização em lote',
    type: [VendedorSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: VendedorSyncItemDto[];
}

// ==========================================
// TABELA DE PREÇO
// ==========================================
export class TabelaPrecoSyncItemDto {
  @ApiProperty({ example: 'TAB01', description: 'Código da tabela de preço no ERP' })
  cod_erp: string;

  @ApiProperty({ example: 'Tabela Atacado Sudeste', description: 'Descrição da tabela de preço' })
  descricao: string;

  @ApiProperty({ example: 'A', description: 'Status (A - Ativo, I - Inativo)', required: false })
  status?: string;
}

export class SyncTabelasPrecoDto {
  @ApiProperty({
    description: 'Lote de tabelas de preço para sincronização em lote',
    type: [TabelaPrecoSyncItemDto],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: TabelaPrecoSyncItemDto[];
}
