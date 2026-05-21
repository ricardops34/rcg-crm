import { ApiProperty, PartialType } from '@nestjs/swagger';
import {
  IsString,
  IsOptional,
  IsNumber,
  IsArray,
  IsObject,
  ValidateNested,
  IsBoolean,
} from 'class-validator';
import { Type } from 'class-transformer';

export class GroupProgramActionsDto {
  @ApiProperty({
    example: true,
    description: 'Permissão para visualizar/listar registros da tela',
    required: false,
    default: true,
  })
  @IsOptional()
  @IsBoolean()
  view?: boolean;

  @ApiProperty({
    example: false,
    description: 'Permissão para inserir/criar novos registros na tela',
    required: false,
    default: false,
  })
  @IsOptional()
  @IsBoolean()
  insert?: boolean;

  @ApiProperty({
    example: false,
    description: 'Permissão para alterar/editar registros existentes na tela',
    required: false,
    default: false,
  })
  @IsOptional()
  @IsBoolean()
  update?: boolean;

  @ApiProperty({
    example: false,
    description: 'Permissão para deletar/excluir registros na tela',
    required: false,
    default: false,
  })
  @IsOptional()
  @IsBoolean()
  delete?: boolean;
}

export class GroupProgramDto {
  @ApiProperty({
    example: 1,
    description: 'ID do programa (tela) associado',
  })
  @IsNumber()
  id: number;

  @ApiProperty({
    description: 'Ações e permissões concedidas para este programa',
    type: GroupProgramActionsDto,
  })
  @IsObject()
  @ValidateNested()
  @Type(() => GroupProgramActionsDto)
  actions: GroupProgramActionsDto;
}

export class CreateGroupDto {
  @ApiProperty({
    example: 'Administradores',
    description: 'Nome amigável do grupo de usuários',
  })
  @IsString()
  name: string;

  @ApiProperty({
    example: '550e8400-e29b-41d4-a716-446655440000',
    description: 'UUID de identificação única do grupo',
    required: false,
  })
  @IsOptional()
  @IsString()
  uuid?: string;

  @ApiProperty({
    example: 'admin',
    description: 'Regra de nível de permissão (role)',
    required: false,
  })
  @IsOptional()
  @IsString()
  role?: string;

  @ApiProperty({
    description: 'Lista de programas (telas) e suas respectivas ações permitidas para o grupo',
    type: [GroupProgramDto],
    required: false,
  })
  @IsOptional()
  @IsArray()
  @ValidateNested({ each: true })
  @Type(() => GroupProgramDto)
  programs?: GroupProgramDto[];
}

export class UpdateGroupDto extends PartialType(CreateGroupDto) {}
