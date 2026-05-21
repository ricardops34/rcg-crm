import { ApiProperty } from '@nestjs/swagger';

export class AgendaEventDto {
  @ApiProperty({ example: 'attendance-1050', description: 'ID único do evento na agenda' })
  id: string;

  @ApiProperty({ example: 'atendimento', enum: ['atendimento', 'venda'] })
  tipo: string;

  @ApiProperty({ example: 'Visita Técnica', description: 'Título do evento' })
  titulo: string;

  @ApiProperty({ example: '2026-05-21T10:00:00Z', description: 'Início do evento' })
  inicio: Date;

  @ApiProperty({ example: '2026-05-21T11:00:00Z', description: 'Fim do evento' })
  fim: Date;

  @ApiProperty({ example: 1, description: 'ID do Cliente vinculado' })
  clienteId: number;

  @ApiProperty({ example: 'Posto RCG', description: 'Nome do Cliente' })
  clienteNome: string;

  @ApiProperty({ example: '#2563eb', description: 'Cor do evento no calendário' })
  cor: string;

  @ApiProperty({ example: 0, description: 'Valor da venda (se tipo for venda)' })
  valor: number;
}

export class AgendaSummaryDto {
  @ApiProperty({ example: 5, description: 'Total de notas fiscais no período' })
  totalNotas: number;

  @ApiProperty({ example: 25000.50, description: 'Valor total vendido no período' })
  totalValor: number;

  @ApiProperty({ example: 12, description: 'Total de atendimentos no período' })
  totalAtendimentos: number;
}

export class AgendaResponseDto {
  @ApiProperty({ example: 'month', enum: ['month', 'week', 'day'] })
  view: string;

  @ApiProperty({ example: '2026-05-21' })
  referenceDate: string;

  @ApiProperty({ type: AgendaSummaryDto })
  summary: AgendaSummaryDto;

  @ApiProperty({ type: [AgendaEventDto] })
  events: AgendaEventDto[];
}
