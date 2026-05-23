import { Controller, Get } from '@nestjs/common';
import { AppService } from './app.service';
import { DataSource } from 'typeorm';

@Controller()
export class AppController {
  constructor(
    private readonly appService: AppService,
    private readonly dataSource: DataSource,
  ) {}

  @Get()
  getHello(): string {
    return this.appService.getHello();
  }

  @Get('db-test')
  async testDb() {
    const results: any = {};
    try {
      // 1. Column names of system_group
      const systemGroupCols = await this.dataSource.query(`
        SELECT column_name 
        FROM information_schema.columns 
        WHERE table_name = 'system_group'
      `);
      results.systemGroupColumns = systemGroupCols.map((r: any) => r.column_name);

      // 2. Counts of various tables
      const tables = ['system_users', 'system_group', 'system_user_group', 'vendedor', 'mvc', 'pivot_venda_mes_cliente'];
      results.counts = {};
      for (const t of tables) {
        try {
          const countRes = await this.dataSource.query(`SELECT COUNT(*) as cnt FROM ${t}`);
          results.counts[t] = parseInt(countRes[0].cnt);
        } catch (e) {
          results.counts[t] = `Error: ${e.message}`;
        }
      }

      // 3. Sample rows from vendedor
      try {
        const vendedores = await this.dataSource.query(`SELECT * FROM vendedor LIMIT 5`);
        results.sampleVendedores = vendedores;
      } catch (e) {
        results.sampleVendedores = `Error: ${e.message}`;
      }

      // 4. Sample rows from mvc
      try {
        const mvc = await this.dataSource.query(`SELECT * FROM mvc LIMIT 5`);
        results.sampleMvc = mvc;
      } catch (e) {
        results.sampleMvc = `Error: ${e.message}`;
      }

    } catch (err) {
      results.error = err.message;
    }
    return results;
  }
}
