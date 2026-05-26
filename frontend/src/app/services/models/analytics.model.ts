import { PoChartSerie } from '@po-ui/ng-components';

export interface DashboardSummary {
  goal: number;
  realized: number;
  achievement: number;
}

export interface DashboardData {
  summary: DashboardSummary;
  categories: PoChartSerie[];
  sellers?: PoChartSerie[];
}

export interface AnalyticsMvcFilters {
  year?: number;
  vendedorId?: number;
  estadoId?: number;
  municipioId?: number;
  dias?: number;
  situacao?: string;
}

export interface AnalyticsMvcItem {
  cliente_id: number;
  cliente_nome: string;
  codigo: string;
  fantasia?: string;
  municipio_descricao?: string;
  estado_sigla?: string;
  vendedor_id?: number;
  vendedor_reduzido?: string;
  financeiro_status: 'R' | 'B';
  situacao: 'A' | 'B';
  difference: number;
  venda_mes: number;
  average3Months: number;
  dias: number;
}
