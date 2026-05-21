import { ApiProperty } from '@nestjs/swagger';
import { IsArray, IsNotEmpty } from 'class-validator';

export class SyncBatchDto {
  @ApiProperty({
    description: 'Array contendo o lote de registros para sincronização',
    type: 'array',
    items: {
      type: 'object',
    },
    example: [
      {
        id: 1,
        nome: 'Registro Exemplo',
      },
    ],
  })
  @IsArray()
  @IsNotEmpty()
  conteudo: any[];
}
