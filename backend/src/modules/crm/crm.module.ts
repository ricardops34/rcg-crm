import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AtendimentoTipo } from './entities/atendimento-tipo.entity';
import { Atendimento } from './entities/atendimento.entity';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      AtendimentoTipo,
      Atendimento
    ])
  ],
  exports: [TypeOrmModule],
})
export class CrmModule {}
