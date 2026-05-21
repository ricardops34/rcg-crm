import { ApiProperty, PartialType } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional, IsNumber, IsEmail, IsDateString } from 'class-validator';

export class CreateClienteDto {
  @ApiProperty({ example: 'CLI001', description: 'Código identificador do cliente no ERP' })
  @IsString()
  @IsNotEmpty()
  codErp: string;

  @ApiProperty({ example: 'Distribuidora RCG Ltda', description: 'Razão Social' })
  @IsString()
  @IsNotEmpty()
  razao: string;

  @ApiProperty({ example: 'RCG Alimentos', description: 'Nome Fantasia', required: false })
  @IsString()
  @IsOptional()
  fantasia?: string;

  @ApiProperty({ example: '12345678000100', description: 'CNPJ ou CPF', required: false })
  @IsString()
  @IsOptional()
  cnpjCpf?: string;

  @ApiProperty({ example: 'A', description: 'Status (A - Ativo, I - Inativo)', default: 'A' })
  @IsString()
  @IsOptional()
  status?: string;

  @ApiProperty({ example: 'J', description: 'Tipo (F - Física, J - Jurídica)', default: 'J' })
  @IsString()
  @IsOptional()
  tipo?: string;

  @ApiProperty({ example: 'contato@cliente.com.br', description: 'E-mail principal', required: false })
  @IsEmail()
  @IsOptional()
  email?: string;

  @ApiProperty({ example: 12, description: 'ID do Vendedor vinculado', required: false })
  @IsNumber()
  @IsOptional()
  vendedorId?: number;

  @ApiProperty({ example: 1, description: 'ID da Filial de faturamento', required: false })
  @IsNumber()
  @IsOptional()
  filialId?: number;

  @ApiProperty({ example: 5000.00, description: 'Limite de crédito', required: false })
  @IsNumber()
  @IsOptional()
  limite?: number;

  @ApiProperty({ example: 'M', description: 'Risco de crédito (A, B, C, D, E, M)', required: false })
  @IsString()
  @IsOptional()
  risco?: string;
}

export class UpdateClienteDto extends PartialType(CreateClienteDto) {}

export class ClienteResponseDto extends CreateClienteDto {
  @ApiProperty({ example: 1, description: 'ID interno do cliente' })
  id: number;

  @ApiProperty({ example: '2026-05-21T15:00:00Z', description: 'Data de inclusão' })
  dtInclusao: Date;

  @ApiProperty({ example: '2026-05-21T15:00:00Z', description: 'Data da última alteração' })
  dtAlteracao: Date;
}
