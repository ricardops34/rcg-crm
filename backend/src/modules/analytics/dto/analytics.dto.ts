import { ApiProperty } from '@nestjs/swagger';

export class DashboardStatsDto {
  @ApiProperty({ example: 125000.50, description: 'Total de vendas no período' })
  totalVendas: number;

  @ApiProperty({ example: 150000.00, description: 'Meta total do período' })
  totalMetas: number;

  @ApiProperty({ example: 83.33, description: 'Percentual de atingimento' })
  percentualAtingimento: number;

  @ApiProperty({ 
    description: 'Vendas agrupadas por categoria de produto',
    example: [{ categoria: 'Óleos', valor: 45000.20, meta: 50000.00 }] 
  })
  vendasPorCategoria: any[];
}

export class MvcItemDto {
  @ApiProperty({ example: 1, description: 'ID do Cliente' })
  clienteId: number;

  @ApiProperty({ example: 'Posto RCG', description: 'Nome do Cliente' })
  razao: string;

  @ApiProperty({ example: 15000.00, description: 'Média de compras dos últimos 3 meses' })
  media3Meses: number;

  @ApiProperty({ example: 12000.00, description: 'Valor comprado no mês atual' })
  mesAtual: number;

  @ApiProperty({ example: -3000.00, description: 'Diferença entre atual e média' })
  diferenca: number;

  @ApiProperty({ example: 'QUEDA', enum: ['ALTA', 'QUEDA', 'ESTAVEL'] })
  tendencia: string;
}
