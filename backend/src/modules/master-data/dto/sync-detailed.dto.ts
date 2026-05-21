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

  @ApiProperty({ example: 'SP', description: 'Sigla do estado (UF) ao qual o município pertence' })
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

  @ApiProperty({ example: 1, description: 'ID da unidade de sistema organizacional', required: false })
  system_unit_id?: number;

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

  @ApiProperty({ example: '12345678901', description: 'CPF da filial (se for pessoa física)', required: false })
  cpf?: string;

  @ApiProperty({ example: '01310100', description: 'CEP do endereço da filial', required: false })
  cep?: string;

  @ApiProperty({ example: 'Av. Paulista, 1000', description: 'Endereço da filial', required: false })
  endereco?: string;

  @ApiProperty({ example: 'Apto 12', description: 'Complemento do endereço', required: false })
  complemento?: string;

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

  @ApiProperty({ example: 'F', description: 'Tipo da filial (F/J)', required: false })
  tipo?: string;
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

  @ApiProperty({ example: 'Distribuidora de Alimentos RCG Ltda', description: 'Razão social do cliente' })
  razao: string;

  @ApiProperty({ example: 'RCG Alimentos', description: 'Nome fantasia do cliente', required: false })
  fantasia?: string;

  @ApiProperty({ example: '12345678000100', description: 'CNPJ ou CPF do cliente', required: false })
  cnpj_cpf?: string;

  @ApiProperty({ example: 'A', description: 'Status do cliente (A - Ativo, I - Inativo)', required: false })
  status?: string;

  @ApiProperty({ example: 'J', description: 'Tipo do cliente (F - Física, J - Jurídica)', required: false })
  tipo?: string;

  @ApiProperty({ example: 'Av. Paulista, 1000', description: 'Endereço do cliente', required: false })
  endereco?: string;

  @ApiProperty({ example: 'Apto 12', description: 'Complemento do endereço do cliente', required: false })
  complemento?: string;

  @ApiProperty({ example: 'Bela Vista', description: 'Bairro do endereço do cliente', required: false })
  bairro?: string;

  @ApiProperty({ example: 'São Paulo', description: 'Município do cliente', required: false })
  municipio?: string;

  @ApiProperty({ example: 'SP', description: 'Estado (UF) do cliente', required: false })
  uf?: string;

  @ApiProperty({ example: '01310100', description: 'CEP do cliente', required: false })
  cep?: string;

  @ApiProperty({ example: 'financeiro@rcgalimentos.com.br', description: 'E-mail de contato e faturamento', required: false })
  email?: string;

  @ApiProperty({ example: '1132224444', description: 'Telefone principal de contato', required: false })
  telefone1?: string;

  @ApiProperty({ example: '1132225555', description: 'Telefone secundário de contato', required: false })
  telefone2?: string;

  @ApiProperty({ example: '1132226666', description: 'Número do fax do cliente', required: false })
  fax?: string;

  @ApiProperty({ example: '11999998888', description: 'Celular principal de contato', required: false })
  celular?: string;

  @ApiProperty({ example: '11999997777', description: 'Celular secundário de contato', required: false })
  celular2?: string;

  @ApiProperty({ example: 'VEND001', description: 'Código ERP do vendedor associado ao cliente', required: false })
  vendedor?: string;

  @ApiProperty({ example: 'FIL01', description: 'Código ERP da filial de faturamento vinculada', required: false })
  filial?: string;

  @ApiProperty({ example: 'COND01', description: 'Código ERP da condição de pagamento padrão do cliente', required: false })
  condicao_pagamento?: string;

  @ApiProperty({ example: 'TAB01', description: 'Código ERP da tabela de preço associada', required: false })
  tabela_preco?: string;

  @ApiProperty({ example: 15000.0, description: 'Limite de crédito financeiro concedido', required: false })
  limite?: number;

  @ApiProperty({ example: 'M', description: 'Grau de risco de crédito (A, B, C, D, E, M)', required: false })
  risco?: string;

  @ApiProperty({ example: 'Observações gerais do cliente vindas do ERP', required: false })
  obs?: string;

  @ApiProperty({ example: '123456789012', description: 'Inscrição Estadual do cliente', required: false })
  ie?: string;

  @ApiProperty({ example: '9876543210', description: 'Inscrição Municipal do cliente', required: false })
  im?: string;

  @ApiProperty({ example: 'S', description: 'Identifica se o cliente é contribuinte de ICMS (S/N)', required: false })
  contribuinte?: string;

  @ApiProperty({ example: '123456789', description: 'RG do cliente (se pessoa física)', required: false })
  rg?: string;

  @ApiProperty({ example: '1985-05-20', description: 'Data de nascimento ou fundação', required: false })
  nascimento?: string;

  @ApiProperty({ example: '2026-01-01', description: 'Data da primeira compra realizada', required: false })
  primeira_compra?: string;

  @ApiProperty({ example: '2026-05-20', description: 'Data da última compra realizada', required: false })
  ultima_compra?: string;

  @ApiProperty({ example: '2026-01-01', description: 'Data de cadastro do cliente', required: false })
  data_cadastro?: string;

  @ApiProperty({ example: 'S', description: 'Indica se deve destacar IE nas notas fiscais (S/N)', required: false })
  destaca_ie?: string;

  @ApiProperty({ example: 'SEG01', description: 'Código ERP do segmento de mercado do cliente', required: false })
  seguimento?: string;

  @ApiProperty({ example: 'www.rcgalimentos.com.br', description: 'Site de internet do cliente', required: false })
  site?: string;

  @ApiProperty({ example: 1, description: 'ID da filial em que o cadastro foi efetuado', required: false })
  filial_cadastro?: number;

  @ApiProperty({ example: 'S', description: 'Indica se o registro é de fato um cliente (S/N)', required: false })
  cliente?: string;

  @ApiProperty({ example: -23.55052, description: 'Coordenada geográfica de latitude do endereço', required: false })
  latitude?: number;

  @ApiProperty({ example: -46.633308, description: 'Coordenada geográfica de longitude do endereço', required: false })
  longitude?: number;

  @ApiProperty({ example: '2027-01-01', description: 'Data de vencimento do limite de crédito', required: false })
  vencimento_limite?: string;

  @ApiProperty({ example: 1, description: 'ID da unidade de sistema organizacional', required: false })
  system_unit_id?: number;

  @ApiProperty({ example: 'S', description: 'Indica se o cliente pertence à carteira própria (S/N)', required: false })
  carteira?: string;

  @ApiProperty({ example: 'Cliente com restrições financeiras registradas', description: 'Observações de bloqueio', required: false })
  obs_bloqueio?: string;

  @ApiProperty({ example: '2026-05-20T10:00:00Z', description: 'Data e hora do bloqueio do cliente', required: false })
  dt_bloqueio?: string;

  @ApiProperty({ example: 'B', description: 'Motivo de bloqueio do cliente (caractere)', required: false })
  motivo_bloqueio?: string;

  @ApiProperty({ example: 2, description: 'ID do motivo formal de bloqueio', required: false })
  motivo_bloqueio_id?: number;

  @ApiProperty({ example: '2026-05-21', description: 'Data de reativação do cliente', required: false })
  dt_reativacao?: string;

  @ApiProperty({ example: 'Crédito reanalisado e liberado', description: 'Observações de reativação', required: false })
  obs_reativacao?: string;

  @ApiProperty({ example: 'REG01', description: 'Código ERP da região de atendimento', required: false })
  regiao_cliente?: string;

  @ApiProperty({ example: 'S', description: 'Indica se o registro do cliente está ativo (S/N)', required: false })
  reg_ativo?: string;

  @ApiProperty({ example: '2026-05-20T12:00:00Z', description: 'Data e hora da revisão cadastral', required: false })
  dt_revisao?: string;

  @ApiProperty({ example: 'SIT01', description: 'Código ERP da situação cadastral junto à RFB', required: false })
  situacao_cadastral?: string;

  @ApiProperty({ example: '2026-05-20', description: 'Data da consulta à RFB', required: false })
  data_rfb?: string;
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

  @ApiProperty({ example: 'Ração Premiata Cães Adultos 15kg', description: 'Nome/Descrição do produto' })
  descricao: string;

  @ApiProperty({ example: 129.9, description: 'Último preço ou preço base do produto', required: false })
  ult_preco?: number;

  @ApiProperty({ example: 'UN', description: 'Unidade de medida principal (UN, KG, FD etc.)', required: false })
  um?: string;

  @ApiProperty({ example: 'A', description: 'Status do produto (A - Ativo, I - Inativo)', required: false })
  status?: string;

  @ApiProperty({ example: 'PA', description: 'Tipo do produto (PA - Prod. Acabado, MP - Matéria Prima)', required: false })
  tipo?: string;

  @ApiProperty({ example: 'FIL01', description: 'Código ERP da filial associada ao produto', required: false })
  filial?: string;

  @ApiProperty({ example: 'CAT01', description: 'Código ERP da categoria de produto', required: false })
  categoria?: string;

  @ApiProperty({ example: 'SUBCAT01', description: 'Código ERP da subcategoria de produto', required: false })
  sub_categoria?: string;

  @ApiProperty({ example: 'FAB01', description: 'Código ERP do fabricante do produto', required: false })
  fabricante?: string;

  @ApiProperty({ example: 'ARM01', description: 'Código ERP do armazém de estoque padrão', required: false })
  armazem?: string;

  @ApiProperty({ example: '7891234567890', description: 'Código de barras EAN/GTIN', required: false })
  codigo_barras?: string;

  @ApiProperty({ example: 'FAB-PROD-001', description: 'Código atribuído pelo fabricante', required: false })
  codigo_fabricante?: string;

  @ApiProperty({ example: 1.0, description: 'Quantidade contida na embalagem de venda', required: false })
  qtd_embalagem?: number;

  @ApiProperty({ example: 'Observações de estocagem e manuseio do produto', required: false })
  observacao?: string;

  @ApiProperty({ example: 'http://assets.rcg.com.br/produtos/prod001.jpg', description: 'URL da imagem ou foto do produto', required: false })
  foto?: string;

  @ApiProperty({ example: '23091010', description: 'Classificação NCM (Nomenclatura Comum do Mercosul)', required: false })
  ncm?: string;

  @ApiProperty({ example: '0', description: 'Origem da mercadoria (0 - Nacional, 1 - Importada etc.)', required: false })
  origem?: string;

  @ApiProperty({ example: 15.6, description: 'Peso bruto em quilogramas', required: false })
  peso_bruto?: number;

  @ApiProperty({ example: 15.0, description: 'Peso líquido em quilogramas', required: false })
  peso?: number;

  @ApiProperty({ example: 'Premiata', description: 'Marca comercial do produto', required: false })
  marca?: string;

  @ApiProperty({ example: 'TE01', description: 'Código ERP do Tipo de Entrada padrão', required: false })
  te?: string;

  @ApiProperty({ example: 'TS01', description: 'Código ERP do Tipo de Saída padrão', required: false })
  ts?: string;

  @ApiProperty({ example: 50.0, description: 'Ponto de pedido (estoque mínimo para requisição)', required: false })
  ponto_pedido?: number;

  @ApiProperty({ example: 20.0, description: 'Estoque de segurança mínimo recomendado', required: false })
  estoque_seguranca?: number;

  @ApiProperty({ example: '2026-05-15', description: 'Data da última compra efetuada', required: false })
  dt_ult_compra?: string;

  @ApiProperty({ example: 'Informações de ficha técnica e composição', required: false })
  informacoes_tecnicas?: string;

  @ApiProperty({ example: 'Dados de propriedades químicas e físicas', required: false })
  dados_tecnicos?: string;

  @ApiProperty({ example: 1, description: 'ID da unidade de sistema organizacional', required: false })
  system_unit_id?: number;
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

  @ApiProperty({ example: 'João da Silva Vendedor', description: 'Nome completo do vendedor' })
  nome: string;

  @ApiProperty({ example: 'João Silva', description: 'Nome reduzido/comercial do vendedor', required: false })
  nome_reduzido?: string;

  @ApiProperty({ example: 'joao.silva@rcg.com.br', description: 'E-mail profissional do vendedor', required: false })
  email?: string;

  @ApiProperty({ example: '11', description: 'DDD do telefone', required: false })
  ddd?: string;

  @ApiProperty({ example: '32221111', description: 'Número do telefone fixo', required: false })
  telefone?: string;

  @ApiProperty({ example: '11999998888', description: 'Telefone celular do vendedor', required: false })
  celular?: string;

  @ApiProperty({ example: 'A', description: 'Status do vendedor (A - Ativo, I - Inativo)', required: false })
  status?: string;

  @ApiProperty({ example: 'S', description: 'Identifica se o profissional é um vendedor ativo (S/N)', required: false })
  vendedor?: string;

  @ApiProperty({ example: 'S', description: 'Permite acesso ao dashboard executivo (S/N)', required: false })
  dashboard?: string;

  @ApiProperty({ example: '1980-10-15', description: 'Data de nascimento', required: false })
  dt_nascimento?: string;

  @ApiProperty({ example: 1, description: 'ID da unidade de sistema organizacional', required: false })
  system_unit_id?: number;

  @ApiProperty({ example: 'VEND002', description: 'Código ERP do vendedor supervisor associado', required: false })
  supervisor?: string;

  @ApiProperty({ example: 'N', description: 'Indica se o vendedor foi desligado (S/N)', required: false })
  desligado?: string;

  @ApiProperty({ example: 'FIL01', description: 'Código ERP da filial à qual o vendedor pertence', required: false })
  filial?: string;
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

  @ApiProperty({ example: 'A', description: 'Status da tabela (A - Ativo, I - Inativo)', required: false })
  status?: string;

  @ApiProperty({ example: 'EMP01', description: 'Código ERP da empresa proprietária da tabela', required: false })
  empresa?: string;

  @ApiProperty({ example: 'FIL01', description: 'Código ERP da filial proprietária da tabela', required: false })
  filial?: string;

  @ApiProperty({ example: '2026-01-01', description: 'Data de início de vigência da tabela', required: false })
  dt_inicio?: string;

  @ApiProperty({ example: '2026-12-31', description: 'Data de término de vigência da tabela', required: false })
  dt_fim?: string;

  @ApiProperty({ example: 'S', description: 'Indica se a tabela está atualmente em utilização (S/N)', required: false })
  utiliza?: string;

  @ApiProperty({ example: 1, description: 'ID da unidade de sistema organizacional', required: false })
  system_unit_id?: number;
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
