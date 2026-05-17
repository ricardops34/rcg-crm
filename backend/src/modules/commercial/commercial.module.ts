import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Vendedor } from './entities/vendedor.entity';
import { Cliente } from './entities/cliente.entity';
import { CondicaoPagamento } from './entities/condicao-pagamento.entity';
import { TabelaPreco } from './entities/tabela-preco.entity';
import { MetaVendedorMes } from './entities/meta-vendedor-mes.entity';
import { MetaVendedorCategoria } from './entities/meta-vendedor-categoria.entity';
import { ClienteService } from './services/cliente/cliente.service';
import { ClienteController } from './controllers/cliente/cliente.controller';
import { SyncCommercialService } from './services/sync-commercial/sync-commercial.service';
import { SyncCommercialController } from './controllers/sync-commercial/sync-commercial.controller';
import { MasterDataModule } from '../master-data/master-data.module';
import { VendedorService } from './services/vendedor/vendedor.service';
import { VendedorController } from './controllers/vendedor/vendedor.controller';
import { MetaVendedorService } from './services/meta-vendedor/meta-vendedor.service';
import { MetaVendedorController } from './controllers/meta-vendedor/meta-vendedor.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      Vendedor, 
      Cliente, 
      CondicaoPagamento, 
      TabelaPreco,
      MetaVendedorMes,
      MetaVendedorCategoria
    ]),
    MasterDataModule
  ],
  exports: [TypeOrmModule, ClienteService, VendedorService],
  providers: [ClienteService, SyncCommercialService, VendedorService, MetaVendedorService],
  controllers: [ClienteController, SyncCommercialController, VendedorController, MetaVendedorController],
})
export class CommercialModule {}
