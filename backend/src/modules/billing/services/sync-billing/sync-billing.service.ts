import { Injectable, Logger } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { ErpTranslationService } from '../../../master-data/services/erp-translation/erp-translation.service';
import { NotaSaida } from '../../entities/nota-saida.entity';
import { AnalyticsRefreshService } from '../../../analytics/services/analytics-refresh.service';

@Injectable()
export class SyncBillingService {
  private readonly logger = new Logger(SyncBillingService.name);

  constructor(
    private dataSource: DataSource,
    private erpTranslation: ErpTranslationService,
    private analyticsRefresh: AnalyticsRefreshService,
  ) {}

  async syncNotasSaida(conteudo: any[]) {
    const results: any[] = [];
    const relations = {
      cliente: 'cliente',
      filial: 'filial',
      vendedor1: 'vendedor',
      vendedor2: 'vendedor',
    };

    for (const item of conteudo) {
      const queryRunner = this.dataSource.createQueryRunner();
      await queryRunner.connect();
      await queryRunner.startTransaction();
      try {
        const resolvedData = await this.erpTranslation.resolveRelationIds(
          item,
          relations,
        );

        const existing = await queryRunner.manager.findOne(NotaSaida, {
          where: {
            notaFiscal: resolvedData.nota_fiscal,
            serieFiscal: resolvedData.serie_fiscal,
            filialId: resolvedData.filial_id,
          },
        });

        let saved;
        if (existing) {
          saved = await queryRunner.manager.save(NotaSaida, {
            ...existing,
            ...resolvedData,
          });
        } else {
          saved = await queryRunner.manager.save(NotaSaida, resolvedData);
        }

        await queryRunner.commitTransaction();
        results.push({
          status: 'OK',
          nota_fiscal: item.nota_fiscal,
          id: saved.id,
        });
      } catch (err) {
        await queryRunner.rollbackTransaction();
        this.logger.error(
          `Erro ao sincronizar Nota ${item.nota_fiscal}: ${err.message}`,
        );
        results.push({
          status: 'error',
          nota_fiscal: item.nota_fiscal,
          message: err.message,
        });
      } finally {
        await queryRunner.release();
      }
    }

    // Refresh materialized views in the background
    await this.analyticsRefresh.refreshViews();

    return results;
  }
}
