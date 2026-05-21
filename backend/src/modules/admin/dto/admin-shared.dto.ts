import { ApiProperty } from '@nestjs/swagger';

export class AdminModuleResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'comercial' })
  name: string;
}

export class AdminProgramResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'ClienteList' })
  controller: string;

  @ApiProperty({ example: 'Listagem de Clientes' })
  name: string;
}

export class AdminGroupResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'Vendedores' })
  name: string;
}

export class AdminUnitResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'RCG - Matriz' })
  name: string;
}
