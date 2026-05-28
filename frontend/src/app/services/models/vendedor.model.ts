export interface VendedorFilial {
  id?: number;
  razao?: string;
}

export interface Vendedor {
  id?: number;
  codErp?: string;
  nome: string;
  email?: string;
  celular?: string;
  status?: 'A' | 'B';
  vendedor?: 'S' | 'N';
  supervisor?: 'S' | 'N';
  desligado?: 'S' | 'N';
  dashboard?: 'S' | 'N';
  filial?: VendedorFilial;
  filialRazao?: string;
  [key: string]: unknown;
}

export interface VendedorListResponse {
  items: Vendedor[];
  total: number;
  page: number;
  limit: number;
  totalPages?: number;
}

export interface VendedorFilters {
  status?: string;
  dashboard?: string;
  supervisor?: string;
  order?: string;
  [key: string]: string | number | boolean | undefined | null;
}
