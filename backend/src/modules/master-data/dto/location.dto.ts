import { ApiProperty } from '@nestjs/swagger';

export class EstadoResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'MS' })
  sigla: string;

  @ApiProperty({ example: 'Mato Grosso do Sul' })
  descricao: string;
}

export class MunicipioResponseDto {
  @ApiProperty({ example: 1050 })
  id: number;

  @ApiProperty({ example: 'Campo Grande' })
  descricao: string;

  @ApiProperty({ example: 1, description: 'ID do Estado vinculado' })
  estadoId: number;
}
