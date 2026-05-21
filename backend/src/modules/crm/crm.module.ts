import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AtendimentoTipo } from './entities/atendimento-tipo.entity';
import { Atendimento } from './entities/atendimento.entity';
import { AdminModule } from '../admin/admin.module';
import { AtendimentoService } from './services/atendimento.service';
import { AgendaComercialService } from './services/agenda-comercial.service';
import { AtendimentoController } from './controllers/atendimento.controller';
import { AgendaComercialController } from './controllers/agenda-comercial.controller';

@Module({
  imports: [TypeOrmModule.forFeature([AtendimentoTipo, Atendimento]), AdminModule],
  exports: [TypeOrmModule, AtendimentoService, AgendaComercialService],
  providers: [AtendimentoService, AgendaComercialService],
  controllers: [AtendimentoController, AgendaComercialController],
})
export class CrmModule {}
