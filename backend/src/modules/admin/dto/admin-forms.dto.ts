import { ApiProperty, PartialType } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional, IsNumber } from 'class-validator';

export class CreateUnitDto {
  @ApiProperty({ example: 'RCG01', description: 'Código da unidade no ERP' })
  @IsString()
  @IsOptional()
  codErp?: string;

  @ApiProperty({ example: 'Matriz Campo Grande', description: 'Nome da unidade' })
  @IsString()
  @IsNotEmpty()
  name: string;

  @ApiProperty({ example: 'S', description: 'Matriz (S/N)', enum: ['S', 'N'], default: 'N' })
  @IsString()
  @IsOptional()
  matriz?: string;

  @ApiProperty({ example: 'A', description: 'Status (A/I)', enum: ['A', 'I'], default: 'A' })
  @IsString()
  @IsOptional()
  status?: string;

  @ApiProperty({ example: 'erp_prod', description: 'Nome da conexão no ERP', required: false })
  @IsString()
  @IsOptional()
  connectionName?: string;

  @ApiProperty({ example: 'data:image/png;base64,...', description: 'Logo em Base64', required: false })
  @IsString()
  @IsOptional()
  logo?: string;

  @ApiProperty({ example: 'data:image/x-icon;base64,...', description: 'Favicon em Base64', required: false })
  @IsString()
  @IsOptional()
  favicon?: string;

  @ApiProperty({ example: 1000, description: 'Limite de disco da unidade em MB', required: false, default: 1000 })
  @IsNumber()
  @IsOptional()
  limiteDiscoMb?: number;
}

export class UpdateUnitDto extends PartialType(CreateUnitDto) {}

export class CreateModuleDto {
  @ApiProperty({ example: 'comercial', description: 'Nome identificador do módulo' })
  @IsString()
  @IsNotEmpty()
  name: string;

  @ApiProperty({ example: 'po-icon-chat', description: 'Ícone do módulo', required: false })
  @IsString()
  @IsOptional()
  icon?: string;

  @ApiProperty({ example: 1, description: 'Ordem de exibição do módulo', required: false })
  @IsNumber()
  @IsOptional()
  order?: number;
}

export class UpdateModuleDto extends PartialType(CreateModuleDto) {}

export class CreateProgramDto {
  @ApiProperty({ example: 'ClienteList', description: 'Nome do controller/programa' })
  @IsString()
  @IsNotEmpty()
  controller: string;

  @ApiProperty({ example: 'Listagem de Clientes', description: 'Nome legível da tela' })
  @IsString()
  @IsNotEmpty()
  name: string;

  @ApiProperty({ example: 'po-icon-user', description: 'Ícone da rotina', required: false })
  @IsString()
  @IsOptional()
  icon?: string;

  @ApiProperty({ example: 1, description: 'Ordem de exibição da rotina', required: false })
  @IsNumber()
  @IsOptional()
  order?: number;

  @ApiProperty({ example: 1, description: 'ID do módulo associado', required: false })
  @IsNumber()
  @IsOptional()
  systemModuleId?: number;

  @ApiProperty({ example: '{"view":true}', description: 'Ações/permissões padrão', required: false })
  @IsString()
  @IsOptional()
  actions?: string;
}

export class UpdateProgramDto extends PartialType(CreateProgramDto) {}

