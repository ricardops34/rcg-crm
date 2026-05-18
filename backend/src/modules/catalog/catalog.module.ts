import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Categoria } from './entities/categoria.entity';
import { SubCategoria } from './entities/sub-categoria.entity';
import { Produto } from './entities/produto.entity';
import { Armazem } from './entities/armazem.entity';
import { Fabricante } from './entities/fabricante.entity';
import { SyncCatalogService } from './services/sync-catalog/sync-catalog.service';
import { ProdutoService } from './services/produto/produto.service';
import { SyncCatalogController } from './controllers/sync-catalog/sync-catalog.controller';
import { ProdutoController } from './controllers/produto/produto.controller';
import { MasterDataModule } from '../master-data/master-data.module';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      Categoria,
      SubCategoria,
      Produto,
      Armazem,
      Fabricante
    ]),
    MasterDataModule
  ],
  exports: [TypeOrmModule, ProdutoService],
  providers: [SyncCatalogService, ProdutoService],
  controllers: [SyncCatalogController, ProdutoController],
})
export class CatalogModule {}
