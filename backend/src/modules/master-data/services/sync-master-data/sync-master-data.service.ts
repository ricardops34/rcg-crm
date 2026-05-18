import { Injectable, Logger } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { ErpTranslationService } from '../erp-translation/erp-translation.service';
import { Estado } from '../../entities/estado.entity';
import { Municipio } from '../../entities/municipio.entity';
import { Filial } from '../../entities/filial.entity';

@Injectable()
export class SyncMasterDataService {
  private readonly logger = new Logger(SyncMasterDataService.name);

  constructor(
    private dataSource: DataSource,
    private erpTranslation: ErpTranslationService,
  ) {}

  async syncBatch(entity: any, conteudo: any[], relations: any = {}) {
    const results: any[] = [];
    for (const item of conteudo) {
      const queryRunner = this.dataSource.createQueryRunner();
      await queryRunner.connect();
      await queryRunner.startTransaction();
      try {
        const resolvedData = await this.erpTranslation.resolveRelationIds(item, relations);
        
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

  async syncEstados(conteudo: any[]) { return this.syncBatch(Estado, conteudo); }
  async syncMunicipios(conteudo: any[]) { return this.syncBatch(Municipio, conteudo, { estado: 'estado' }); }
  async syncFiliais(conteudo: any[]) { return this.syncBatch(Filial, conteudo); }
}
