import { Injectable, Logger } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { ErpTranslationService } from '../../../master-data/services/erp-translation/erp-translation.service';
import { TituloReceber } from '../../entities/titulo-receber.entity';

@Injectable()
export class SyncFinanceService {
  private readonly logger = new Logger(SyncFinanceService.name);

  constructor(
    private dataSource: DataSource,
    private erpTranslation: ErpTranslationService,
  ) {}

  async syncTitulos(conteudo: any[]) {
    const results: any[] = [];
    const relations = {
      cliente: 'cliente',
      vendedor: 'vendedor',
      filial: 'filial',
    };

    for (const item of conteudo) {
      const queryRunner = this.dataSource.createQueryRunner();
      await queryRunner.connect();
      await queryRunner.startTransaction();
      try {
        const resolvedData = await this.erpTranslation.resolveRelationIds(item, relations);
        
        const existing = await queryRunner.manager.findOne(TituloReceber, { 
          where: { numero: resolvedData.numero, parcela: resolvedData.parcela, prefixo: resolvedData.prefixo, filialId: resolvedData.filial_id } 
        });

        let saved;
        if (existing) {
          saved = await queryRunner.manager.save(TituloReceber, { ...existing, ...resolvedData });
        } else {
          saved = await queryRunner.manager.save(TituloReceber, resolvedData);
        }

        await queryRunner.commitTransaction();
        results.push({ status: 'OK', numero: item.numero, id: saved.id });
      } catch (err) {
        await queryRunner.rollbackTransaction();
        this.logger.error(`Erro ao sincronizar Título ${item.numero}: ${err.message}`);
        results.push({ status: 'error', numero: item.numero, message: err.message });
      } finally {
        await queryRunner.release();
      }
    }
    return results;
  }
}
