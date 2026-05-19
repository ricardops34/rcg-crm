import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Filial } from './entities/filial.entity';
import { Municipio } from './entities/municipio.entity';
import { Estado } from './entities/estado.entity';
import { ErpTranslationService } from './services/erp-translation/erp-translation.service';
import { SyncMasterDataService } from './services/sync-master-data/sync-master-data.service';
import { SyncMasterDataController } from './controllers/sync-master-data/sync-master-data.controller';
import { UnitController } from './controllers/unit.controller';

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
      Cliente,
      Vendedor,
      TabelaPreco,
      Produto,
    ]),
  ],
  exports: [TypeOrmModule, ErpTranslationService],
  providers: [ErpTranslationService, SyncMasterDataService],
  controllers: [SyncMasterDataController, UnitController],
})
export class MasterDataModule {}
