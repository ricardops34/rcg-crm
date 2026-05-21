import { ApiProperty } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional, IsNumber, IsDateString } from 'class-validator';

export class CreateAtendimentoDto {
  @ApiProperty({ example: 1, description: 'ID do Tipo de Atendimento' })
  @IsNumber()
  @IsNotEmpty()
  atendimentoTipoId: number;

  @ApiProperty({ example: 12, description: 'ID do Vendedor' })
  @IsNumber()
  @IsNotEmpty()
  vendedorId: number;

  @ApiProperty({ example: 1, description: 'ID do Cliente (opcional)', required: false })
  @IsNumber()
  @IsOptional()
  clienteId?: number;

  @ApiProperty({ example: 'Visita Comercial', description: 'Título ou resumo' })
  @IsString()
  @IsNotEmpty()
  titulo: string;

  @ApiProperty({ example: '2026-05-21T10:00:00Z', description: 'Data e hora de início' })
  @IsDateString()
  @IsNotEmpty()
  horarioInicial: string;

  @ApiProperty({ example: '2026-05-21T11:00:00Z', description: 'Data e hora de término' })
  @IsDateString()
  @IsNotEmpty()
  horarioFinal: string;

  @ApiProperty({ example: 'Cliente interessado em novos produtos', description: 'Observações', required: false })
  @IsString()
  @IsOptional()
  observacao?: string;

  @ApiProperty({ example: '#ff0000', description: 'Cor para exibição no calendário', required: false })
  @IsString()
  @IsOptional()
  cor?: string;
}

export class AtendimentoResponseDto extends CreateAtendimentoDto {
  @ApiProperty({ example: 1050, description: 'ID interno do atendimento' })
  id: number;
  
  @ApiProperty({ description: 'Data de inclusão' })
  dtInclusao: Date;
}

export class AtendimentoTipoResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'Visita Técnica' })
  descricao: string;

  @ApiProperty({ example: 'S', description: 'Disponível para atendimento' })
  atendimento: string;
}
