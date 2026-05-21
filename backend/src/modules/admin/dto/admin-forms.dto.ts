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
}

export class UpdateUnitDto extends PartialType(CreateUnitDto) {}

export class CreateModuleDto {
  @ApiProperty({ example: 'comercial', description: 'Nome identificador do módulo' })
  @IsString()
  @IsNotEmpty()
  name: string;
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
}

export class UpdateProgramDto extends PartialType(CreateProgramDto) {}
