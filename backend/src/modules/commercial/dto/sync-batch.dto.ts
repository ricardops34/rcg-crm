import { IsArray, IsNotEmpty, IsOptional, IsString } from 'class-validator';

export class SyncBatchDto {
  @IsArray()
  @IsNotEmpty()
  conteudo: any[];
}
