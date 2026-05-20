import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Filial } from './entities/filial.entity';
import { Municipio } from './entities/municipio.entity';
import { Estado } from './entities/estado.entity';
import { TipoEntradaSaida } from './entities/tipo-entrada-saida.entity';
import { Cep } from './entities/cep.entity';
import { Empresa } from './entities/empresa.entity';
import { Parametro } from './entities/parametro.entity';
import { ErpTranslationService } from './services/erp-translation/erp-translation.service';
import { SyncMasterDataService } from './services/sync-master-data/sync-master-data.service';
import { SyncMasterDataController } from './controllers/sync-master-data/sync-master-data.controller';
import { UnitController } from './controllers/unit.controller';
import { LocationController } from './controllers/location.controller';
import { AdminModule } from '../admin/admin.module';

import { Cliente } from '../commercial/entities/cliente.entity';
import { Vendedor } from '../commercial/entities/vendedor.entity';
import { TabelaPreco } from '../commercial/entities/tabela-preco.entity';
import { Produto } from '../catalog/entities/produto.entity';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      Filial,
      Municipio,
      Estado,
      TipoEntradaSaida,
      Cep,
      Empresa,
      Parametro,
      Cliente,
      Vendedor,
      TabelaPreco,
      Produto,
    ]),
    AdminModule,
  ],
  exports: [TypeOrmModule, ErpTranslationService],
  providers: [ErpTranslationService, SyncMasterDataService],
  controllers: [SyncMasterDataController, UnitController, LocationController],
})
export class MasterDataModule {}
