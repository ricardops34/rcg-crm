import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Vendedor } from './entities/vendedor.entity';
import { Supervisor } from './entities/supervisor.entity';
import { SupervisorVendedor } from './entities/supervisor-vendedor.entity';
import { Transportadora } from './entities/transportadora.entity';
import { MotivoBloqueio } from './entities/motivo-bloqueio.entity';
import { SituacaoCadastral } from './entities/situacao-cadastral.entity';
import { RegiaoCliente } from './entities/regiao-cliente.entity';
import { Segmento } from './entities/segmento.entity';
import { TipoCliente } from './entities/tipo-cliente.entity';
import { VendedorAtendimento } from './entities/vendedor-atendimento.entity';
import { ClienteCondicao } from './entities/cliente-condicao.entity';
import { Cliente } from './entities/cliente.entity';
import { CondicaoPagamento } from './entities/condicao-pagamento.entity';
import { TabelaPreco } from './entities/tabela-preco.entity';
import { TabelaPrecoItem } from './entities/tabela-preco-item.entity';
import { TipoContato } from './entities/tipo-contato.entity';
import { ClienteContato } from './entities/cliente-contato.entity';
import { MetaVendedorMes } from './entities/meta-vendedor-mes.entity';
import { MetaVendedorCategoria } from './entities/meta-vendedor-categoria.entity';
import { ClienteService } from './services/cliente/cliente.service';
import { ClienteDetailsService } from './services/cliente/cliente-details.service';
import { ClienteController } from './controllers/cliente/cliente.controller';
import { SyncCommercialService } from './services/sync-commercial/sync-commercial.service';
import { SyncCommercialController } from './controllers/sync-commercial/sync-commercial.controller';
import { MasterDataModule } from '../master-data/master-data.module';
import { VendedorService } from './services/vendedor/vendedor.service';
import { VendedorController } from './controllers/vendedor/vendedor.controller';
import { MetaVendedorService } from './services/meta-vendedor/meta-vendedor.service';
import { TabelaPrecoService } from './services/tabela-preco/tabela-preco.service';
import { MetaVendedorController } from './controllers/meta-vendedor/meta-vendedor.controller';
import { TabelaPrecoController } from './controllers/tabela-preco/tabela-preco.controller';
import { AdminModule } from '../admin/admin.module';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      Vendedor,
      Supervisor,
      SupervisorVendedor,
      Transportadora,
      MotivoBloqueio,
      SituacaoCadastral,
      RegiaoCliente,
      Segmento,
      TipoCliente,
      VendedorAtendimento,
      ClienteCondicao,
      Cliente,
      CondicaoPagamento,
      TabelaPreco,
      TabelaPrecoItem,
      TipoContato,
      ClienteContato,
      MetaVendedorMes,
      MetaVendedorCategoria,
    ]),
    MasterDataModule,
    AdminModule,
  ],
  exports: [
    TypeOrmModule,
    ClienteService,
    VendedorService,
    TabelaPrecoService,
  ],
  providers: [
    ClienteService,
    ClienteDetailsService,
    SyncCommercialService,
    VendedorService,
    MetaVendedorService,
    TabelaPrecoService,
  ],
  controllers: [
    ClienteController,
    SyncCommercialController,
    VendedorController,
    MetaVendedorController,
    TabelaPrecoController,
  ],
})
export class CommercialModule {}
