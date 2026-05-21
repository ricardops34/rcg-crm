import { ApiProperty, PartialType } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional, IsNumber, IsArray } from 'class-validator';

export class CreateNegociacaoDto {
  @ApiProperty({ example: 1, description: 'ID do Cliente' })
  @IsNumber()
  @IsNotEmpty()
  clienteId: number;

  @ApiProperty({ example: 12, description: 'ID do Vendedor' })
  @IsNumber()
  @IsNotEmpty()
  vendedorId: number;

  @ApiProperty({ example: [101, 102], description: 'Lista de IDs de Títulos para renegociar' })
  @IsArray()
  @IsNotEmpty()
  titulos: number[];

  @ApiProperty({ example: 'Proposta de parcelamento em 3x', description: 'Observações' })
  @IsString()
  @IsOptional()
  observacao?: string;
}

export class NegociacaoResponseDto {
  @ApiProperty({ example: 105 })
  id: number;

  @ApiProperty({ example: '2026-05-21', description: 'Data da negociação' })
  dtNegociacao: string;

  @ApiProperty({ example: 'P', description: 'Status (P-Pendente, A-Aprovada, R-Rejeitada)' })
  status: string;
}
