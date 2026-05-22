import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AtendimentoTipo } from './entities/atendimento-tipo.entity';
import { Atendimento } from './entities/atendimento.entity';
import { CrmService } from './services/crm.service';
import { CrmController } from './controllers/crm.controller';
import { AdminModule } from '../admin/admin.module';

@Module({
  imports: [
    TypeOrmModule.forFeature([AtendimentoTipo, Atendimento]), 
    AdminModule
  ],
  exports: [TypeOrmModule, CrmService],
  providers: [CrmService],
  controllers: [CrmController],
})
export class CrmModule {}
