import { ApiProperty } from '@nestjs/swagger';

export class UserResponseDto {
  @ApiProperty({ example: 1 })
  id: number;

  @ApiProperty({ example: 'Ricardo Engenheiro' })
  name: string;

  @ApiProperty({ example: 'ricardo.admin' })
  login: string;

  @ApiProperty({ example: 'financeiro@rcg.com.br' })
  email: string;

  @ApiProperty({ example: 'Y', enum: ['Y', 'N'] })
  active: string;

  @ApiProperty({ example: 1, description: 'ID da Unidade padrão' })
  systemUnitId: number;

  @ApiProperty({ example: 'S', description: 'Aceite de Termos', enum: ['Y', 'N'] })
  acceptedTermPolicy: string;
}
