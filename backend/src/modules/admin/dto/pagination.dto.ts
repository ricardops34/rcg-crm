import { ApiProperty } from '@nestjs/swagger';

export class PaginatedResponseDto<T> {
  @ApiProperty({ description: 'Lista de registros da página atual' })
  items: T[];

  @ApiProperty({ example: 100, description: 'Total de registros encontrados' })
  total: number;

  @ApiProperty({ example: true, description: 'Indica se existe uma próxima página' })
  hasNext: boolean;
}
