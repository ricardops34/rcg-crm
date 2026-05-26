export interface ClienteContato {
  id?: number;
  nome: string;
  telefone?: string;
  email?: string;
  tipoContatoId: number;
}

export interface ClienteVendedor {
  id: number;
  nome: string;
}

export interface Cliente {
  id?: number;
  codErp?: string;
  razao?: string;
  fantasia?: string;
  cnpjCpf?: string;
  status?: 'A' | 'B';
  tipo?: 'F' | 'J';
  contribuinte?: 'S' | 'N';
  destacaIe?: 'S' | 'N';
  vendedor?: ClienteVendedor;
  vendedorId?: number;
  municipioId?: number;
  uf?: string;
  contatos?: ClienteContato[];
  [key: string]: unknown;
}

export interface ClienteListResponse {
  items: Cliente[];
  total: number;
  page: number;
  limit: number;
  totalPages?: number;
}

export interface ClienteComodatoItem {
  id?: number;
  nota_fiscal?: string;
  serie_fiscal?: string;
  dt_emissao?: string;
  vlr_mercadoria?: number;
  [key: string]: unknown;
}

export interface ClienteMixItem {
  id?: number;
  produto_nome?: string;
  total_qtd?: number;
  ultima_compra?: string;
  total_valor?: number;
  [key: string]: unknown;
}

export interface ClienteFinanceiroItem {
  id?: number;
  numero?: string;
  parcela?: string;
  vencimento?: string;
  saldo?: number;
  status?: string;
  [key: string]: unknown;
}

export interface ClienteNotaItem {
  id?: number;
  nota_fiscal?: string;
  dt_emissao?: string;
  vlr_bruto?: number;
  vlr_liquido?: number;
  vendedor_nome?: string;
  [key: string]: unknown;
}

export interface ClienteAtendimentoItem {
  id?: number;
  dt_atendimento?: string;
  tipo_atendimento?: string;
  vendedor_nome?: string;
  observacao?: string;
  [key: string]: unknown;
}

export interface ClienteSugestaoItem {
  id?: number;
  produto_nome?: string;
  media_mensal?: number;
  qtd_atual?: number;
  sugestao?: number;
  data_ultima_compra?: string;
  [key: string]: unknown;
}
