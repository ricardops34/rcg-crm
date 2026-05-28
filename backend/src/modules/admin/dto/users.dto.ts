import { ApiProperty, PartialType } from '@nestjs/swagger';
import {
  IsString,
  IsOptional,
  IsEmail,
  IsNumber,
  IsArray,
  MinLength,
  IsIn,
} from 'class-validator';

export class SaveTermsDto {
  @ApiProperty({
    example: 'Este é o texto dos novos termos de uso...',
    description: 'Texto completo dos termos de uso',
  })
  @IsString()
  text: string;

  @ApiProperty({
    example: '1.2',
    description: 'Versão do termo de uso',
  })
  @IsString()
  version: string;
}

export class CreateUserDto {
  @ApiProperty({
    example: 'João da Silva',
    description: 'Nome completo do usuário',
    minLength: 3,
  })
  @IsString()
  @MinLength(3, { message: 'O nome deve ter no mínimo 3 caracteres' })
  name: string;

  @ApiProperty({
    example: 'joao.silva',
    description: 'Login de acesso ao sistema',
    minLength: 3,
  })
  @IsString()
  @MinLength(3, { message: 'O login deve ter no mínimo 3 caracteres' })
  login: string;

  @ApiProperty({
    example: 'senha123',
    description: 'Senha de acesso',
    minLength: 6,
  })
  @IsString()
  @MinLength(6, { message: 'A senha deve ter no mínimo 6 caracteres' })
  password: string;

  @ApiProperty({
    example: 'joao.silva@empresa.com.br',
    description: 'E-mail principal do usuário',
    required: false,
  })
  @IsOptional()
  @IsEmail({}, { message: 'Insira um e-mail válido' })
  email?: string;

  @ApiProperty({
    example: 1,
    description: 'ID do programa de tela inicial padrão do usuário',
    required: false,
  })
  @IsOptional()
  @IsNumber()
  frontpageId?: number;

  @ApiProperty({
    example: 1,
    description: 'ID da unidade de sistema ativa/principal do usuário',
    required: false,
  })
  @IsOptional()
  @IsNumber()
  systemUnitId?: number;

  @ApiProperty({
    example: 'Y',
    description: 'Indica se o usuário está ativo no sistema (S - Sim / N - Não)',
    enum: ['Y', 'N'],
    required: false,
    default: 'Y',
  })
  @IsOptional()
  @IsString()
  @IsIn(['Y', 'N'], { message: 'O campo active deve ser Y ou N' })
  active?: string;

  @ApiProperty({
    example: 'N',
    description: 'Indica se a autenticação em duas etapas (2FA) está habilitada (S - Sim / N - Não)',
    enum: ['Y', 'N'],
    required: false,
    default: 'N',
  })
  @IsOptional()
  @IsString()
  @IsIn(['Y', 'N'], { message: 'O campo twoFactorEnabled deve ser Y ou N' })
  twoFactorEnabled?: string;

  @ApiProperty({
    example: 'totp',
    description: 'Tipo de autenticação de dois fatores utilizada',
    required: false,
  })
  @IsOptional()
  @IsString()
  twoFactorType?: string;

  @ApiProperty({
    example: 'JBSWY3DPEHPK3PXP',
    description: 'Segredo TOTP criptografado ou em texto claro para autenticação 2FA',
    required: false,
  })
  @IsOptional()
  @IsString()
  twoFactorSecret?: string;

  @ApiProperty({
    example: 'data:image/png;base64,iVBORw0KGgoAAAANS...',
    description: 'Imagem do avatar em formato Base64 ou URL de imagem',
    required: false,
  })
  @IsOptional()
  @IsString()
  avatar?: string;

  @ApiProperty({
    example: [1, 2],
    description: 'Lista de IDs dos grupos de permissão associados ao usuário',
    type: [Number],
    required: false,
  })
  @IsOptional()
  @IsArray()
  @IsNumber({}, { each: true })
  groups?: number[];

  @ApiProperty({
    example: [1],
    description: 'Lista de IDs das unidades de sistema que o usuário tem acesso',
    type: [Number],
    required: false,
  })
  @IsOptional()
  @IsArray()
  @IsNumber({}, { each: true })
  units?: number[];

  @ApiProperty({
    example: [3, 4],
    description: 'Lista de IDs dos programas/telas permitidos para acesso direto do usuário',
    type: [Number],
    required: false,
  })
  @IsOptional()
  @IsArray()
  @IsNumber({}, { each: true })
  programs?: number[];
}

export class UpdateUserDto extends PartialType(CreateUserDto) {}
