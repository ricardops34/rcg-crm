import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AtendimentoTipo } from './entities/atendimento-tipo.entity';
import { Atendimento } from './entities/atendimento.entity';
import { CrmService } from './services/crm.service';
import { CrmController } from './controllers/crm.controller';
import { AdminModule } from '../admin/admin.module';
import { AgendaComercialService } from './services/agenda-comercial.service';
import { AgendaComercialController } from './controllers/agenda-comercial.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature([AtendimentoTipo, Atendimento]), 
    AdminModule
  ],
  exports: [TypeOrmModule, CrmService, AgendaComercialService],
  providers: [CrmService, AgendaComercialService],
  controllers: [CrmController, AgendaComercialController],
})
export class CrmModule {}
