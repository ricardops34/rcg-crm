import { Injectable, Logger } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { ErpTranslationService } from '../../../master-data/services/erp-translation/erp-translation.service';
import { Pedido } from '../../entities/pedido.entity';
import { PedidoItem } from '../../entities/pedido-item.entity';
import { AnalyticsRefreshService } from '../../../analytics/services/analytics-refresh.service';

@Injectable()
export class SyncSalesService {
  private readonly logger = new Logger(SyncSalesService.name);

  constructor(
    private dataSource: DataSource,
    private erpTranslation: ErpTranslationService,
    private analyticsRefresh: AnalyticsRefreshService,
  ) {}

  async syncPedidos(conteudo: any[]) {
    const results: any[] = [];
    const relations = {
      cliente: 'cliente',
      vendedor1: 'vendedor',
      vendedor2: 'vendedor',
      filial: 'filial',
      pedido_estado: 'pedido_estado',
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
        resolvedData.sinc = 'S';

        const existing = await queryRunner.manager.findOne(Pedido, {
          where: { codErp: resolvedData.cod_erp },
        });

        let saved;
        if (existing) {
          saved = await queryRunner.manager.save(Pedido, {
            ...existing,
            ...resolvedData,
          });
        } else {
          saved = await queryRunner.manager.save(Pedido, resolvedData);
        }

        await queryRunner.commitTransaction();
        results.push({ status: 'OK', cod_erp: item.cod_erp, id: saved.id });
      } catch (err) {
        await queryRunner.rollbackTransaction();
        this.logger.error(
          `Erro ao sincronizar Pedido ${item.cod_erp}: ${err.message}`,
        );
        results.push({
          status: 'error',
          cod_erp: item.cod_erp,
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
