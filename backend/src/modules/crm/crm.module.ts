import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AtendimentoTipo } from './entities/atendimento-tipo.entity';
import { Atendimento } from './entities/atendimento.entity';
import { Cliente } from '../commercial/entities/cliente.entity';
import { AdminModule } from '../admin/admin.module';
import { CommercialModule } from '../commercial/commercial.module';
import { AtendimentoService } from './services/atendimento.service';
import { AgendaComercialService } from './services/agenda-comercial.service';
import { WhatsAppService } from './services/whatsapp.service';
import { AtendimentoController } from './controllers/atendimento.controller';
import { AgendaComercialController } from './controllers/agenda-comercial.controller';
import { WhatsAppController } from './controllers/whatsapp.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature([AtendimentoTipo, Atendimento, Cliente]),
    AdminModule,
    CommercialModule,
  ],
  exports: [
    TypeOrmModule,
    AtendimentoService,
    AgendaComercialService,
    WhatsAppService,
  ],
  providers: [AtendimentoService, AgendaComercialService, WhatsAppService],
  controllers: [
    AtendimentoController,
    AgendaComercialController,
    WhatsAppController,
  ],
})
export class CrmModule {}
