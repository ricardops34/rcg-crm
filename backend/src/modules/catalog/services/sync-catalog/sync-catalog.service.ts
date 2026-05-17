import { Injectable, Logger } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { ErpTranslationService } from '../../../master-data/services/erp-translation/erp-translation.service';
import { Categoria } from '../../entities/categoria.entity';
import { SubCategoria } from '../../entities/sub-categoria.entity';
import { Produto } from '../../entities/produto.entity';
import { Armazem } from '../../entities/armazem.entity';
import { Fabricante } from '../../entities/fabricante.entity';

@Injectable()
export class SyncCatalogService {
  private readonly logger = new Logger(SyncCatalogService.name);

  constructor(
    private dataSource: DataSource,
    private erpTranslation: ErpTranslationService,
  ) {}

  async syncBatch(entity: any, conteudo: any[], relations: any = {}) {
    const results = [];
    for (const item of conteudo) {
      const queryRunner = this.dataSource.createQueryRunner();
      await queryRunner.connect();
      await queryRunner.startTransaction();
      try {
        const resolvedData = await this.erpTranslation.resolveRelationIds(item, relations);
        resolvedData.sinc = 'S';
        
        const existing = await queryRunner.manager.findOne(entity, { 
          where: { codErp: resolvedData.cod_erp } 
        });

        let saved;
        if (existing) {
          saved = await queryRunner.manager.save(entity, { ...existing, ...resolvedData });
        } else {
          saved = await queryRunner.manager.save(entity, resolvedData);
        }

        await queryRunner.commitTransaction();
        results.push({ status: 'OK', cod_erp: item.cod_erp, id: saved.id });
      } catch (err) {
        await queryRunner.rollbackTransaction();
        this.logger.error(`Erro ao sincronizar ${entity.name} ${item.cod_erp}: ${err.message}`);
        results.push({ status: 'error', cod_erp: item.cod_erp, message: err.message });
      } finally {
        await queryRunner.release();
      }
    }
    return results;
  }

  async syncCategorias(conteudo: any[]) { return this.syncBatch(Categoria, conteudo); }
  async syncSubCategorias(conteudo: any[]) { return this.syncBatch(SubCategoria, conteudo, { categoria: 'categoria' }); }
  async syncFabricantes(conteudo: any[]) { return this.syncBatch(Fabricante, conteudo); }
  async syncArmazens(conteudo: any[]) { return this.syncBatch(Armazem, conteudo); }
  async syncProdutos(conteudo: any[]) { 
    return this.syncBatch(Produto, conteudo, { 
      categoria: 'categoria', 
      sub_categoria: 'sub_categoria', 
      fabricante: 'fabricante',
      armazem: 'armazem'
    }); 
  }
}
