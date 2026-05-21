import { ApiProperty, PartialType } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional, IsNumber, IsEmail } from 'class-validator';

export class CreateVendedorDto {
  @ApiProperty({ example: 'V012', description: 'Código identificador no ERP' })
  @IsString()
  @IsNotEmpty()
  codErp: string;

  @ApiProperty({ example: 'João da Silva', description: 'Nome completo' })
  @IsString()
  @IsNotEmpty()
  nome: string;

  @ApiProperty({ example: 'João Vendedor', description: 'Nome reduzido', required: false })
  @IsString()
  @IsOptional()
  nomeReduzido?: string;

  @ApiProperty({ example: 'joao.vendedor@rcg.com.br', description: 'E-mail de contato', required: false })
  @IsEmail()
  @IsOptional()
  email?: string;

  @ApiProperty({ example: '11999998888', description: 'Telefone celular', required: false })
  @IsString()
  @IsOptional()
  celular?: string;

  @ApiProperty({ example: 'A', description: 'Status (A-Ativo, I-Inativo, D-Desligado)', default: 'A' })
  @IsString()
  @IsOptional()
  status?: string;

  @ApiProperty({ example: 1, description: 'ID da Filial vinculada', required: false })
  @IsNumber()
  @IsOptional()
  filialId?: number;

  @ApiProperty({ example: 105, description: 'ID do Usuário de sistema vinculado', required: false })
  @IsNumber()
  @IsOptional()
  systemUsersId?: number;
}

export class UpdateVendedorDto extends PartialType(CreateVendedorDto) {}

export class VendedorResponseDto extends CreateVendedorDto {
  @ApiProperty({ example: 12, description: 'ID interno do vendedor' })
  id: number;

  @ApiProperty({ example: '2026-05-21T15:00:00Z', description: 'Data de inclusão', readOnly: true })
  dtInclusao: Date;
}

export class PaginatedVendedorResponseDto {
  @ApiProperty({ type: [VendedorResponseDto] })
  items: VendedorResponseDto[];

  @ApiProperty({ example: 50 })
  total: number;

  @ApiProperty({ example: false })
  hasNext: boolean;
}
