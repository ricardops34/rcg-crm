import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Filial } from './entities/filial.entity';
import { Municipio } from './entities/municipio.entity';
import { Estado } from './entities/estado.entity';
import { ErpTranslationService } from './services/erp-translation/erp-translation.service';
import { SyncMasterDataService } from './services/sync-master-data/sync-master-data.service';
import { SyncMasterDataController } from './controllers/sync-master-data/sync-master-data.controller';

@Module({
  imports: [TypeOrmModule.forFeature([Filial, Municipio, Estado])],
  exports: [TypeOrmModule, ErpTranslationService],
  providers: [ErpTranslationService, SyncMasterDataService],
  controllers: [SyncMasterDataController],
})
export class MasterDataModule {}
