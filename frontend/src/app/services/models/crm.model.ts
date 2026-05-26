export type AgendaView = 'month' | 'week' | 'day';

export interface CrmTipoAtendimento {
  id: number;
  descricao: string;
  cor?: string;
  [key: string]: unknown;
}

export interface CrmAgendaEvent {
  id: string;
  tipo: 'venda' | 'atendimento';
  titulo: string;
  cor: string;
  clienteNome?: string;
  notaFiscal?: string;
  valor?: number;
  inicio?: string;
  fim?: string;
  data?: string;
  observacao?: string;
  vendedorNome?: string;
  atendimentoTipoDescricao?: string;
  atendimentoTipoId?: number;
  notaSaidaId?: number;
  clienteId?: number;
  [key: string]: unknown;
}

export interface CrmAgendaSummary {
  totalNotas: number;
  totalValor: number;
  totalAtendimentos: number;
}

export interface CrmAgendaResponse {
  summary: CrmAgendaSummary;
  events: CrmAgendaEvent[];
}

export interface CrmAtendimento {
  id?: number;
  clienteId?: number;
  notaSaidaId?: number;
  titulo?: string;
  observacao?: string;
  atendimentoTipoId?: number;
  horarioInicial?: string | Date;
  horarioFinal?: string | Date;
  retorno?: string | Date;
  cor?: string;
  [key: string]: unknown;
}

export interface CrmAtendimentoListItem {
  id?: number;
  [key: string]: unknown;
}
