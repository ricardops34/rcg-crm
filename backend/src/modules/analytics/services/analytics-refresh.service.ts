import { Injectable, Logger } from '@nestjs/common';
import { DataSource } from 'typeorm';

@Injectable()
export class AnalyticsRefreshService {
  private readonly logger = new Logger(AnalyticsRefreshService.name);
  private isRefreshing = false;

  constructor(private dataSource: DataSource) {}

  /**
   * Refreshes materialized views concurrently in the background.
   * This is non-blocking and executes safely without locking the tables.
   */
  async refreshViews() {
    if (this.isRefreshing) {
      this.logger.warn('View refresh is already in progress. Skipping duplicate request.');
      return;
    }

    this.isRefreshing = true;

    // Perform refresh asynchronously so the API response is returned immediately to the ERP sync tool
    setTimeout(async () => {
      try {
        this.logger.log('Starting Concurrent Refresh of Materialized Views...');

        await this.dataSource.query('REFRESH MATERIALIZED VIEW CONCURRENTLY mvc');
        this.logger.log('Materialized View "mvc" refreshed.');

        await this.dataSource.query('REFRESH MATERIALIZED VIEW CONCURRENTLY pivot_venda_mes_cliente');
        this.logger.log('Materialized View "pivot_venda_mes_cliente" refreshed.');

        await this.dataSource.query('REFRESH MATERIALIZED VIEW CONCURRENTLY view_vendedor_venda_mes');
        this.logger.log('Materialized View "view_vendedor_venda_mes" refreshed.');

        await this.dataSource.query('REFRESH MATERIALIZED VIEW CONCURRENTLY view_total_catogoria_mes');
        this.logger.log('Materialized View "view_total_catogoria_mes" refreshed.');

        this.logger.log('All Analytics Materialized Views successfully refreshed!');
      } catch (err) {
        this.logger.error(`Error refreshing materialized views: ${err.message}`);
      } finally {
        this.isRefreshing = false;
      }
    }, 100);
  }
}
