import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { Vendedor } from '../../../commercial/entities/vendedor.entity';

@Injectable()
export class AnalyticsService {
  constructor(
    private dataSource: DataSource,
    @InjectRepository(Vendedor)
    private vendedorRepository: Repository<Vendedor>,
  ) {}

  async getVendedorIdByUser(systemUserId: number): Promise<number | null> {
    const vendedor = await this.vendedorRepository.findOne({
      where: { systemUsersId: systemUserId },
    });
    return vendedor ? vendedor.id : null;
  }

  async getDashboardStats(year: number, month: number, vendedorId?: number) {
    let whereVendedor = '';
    const params: any[] = [year, month];

    if (vendedorId) {
      whereVendedor = ' AND vendedor_id = $3';
      params.push(vendedorId);
    }

    try {
      // 1. KPIs Gerais
      const kpis = await this.dataSource.query(
        `SELECT 
          SUM(vlr_objetivo) as goal,
          SUM(vlr_liquido) as realized,
          AVG(perc_liquido) as achievement
         FROM view_vendedor_venda_mes 
         WHERE CAST(ano AS integer) = $1 AND CAST(mes AS integer) = $2` + whereVendedor,
        params,
      );

      // 2. Vendas por Categoria (Rosca)
      const categories = await this.dataSource.query(
        `SELECT 
          categoria as label,
          SUM(vlr_liquido) as value
         FROM view_total_catogoria_mes
         WHERE CAST(ano AS integer) = $1 AND CAST(mes AS integer) = $2` +
          whereVendedor +
          ` GROUP BY categoria ORDER BY value DESC LIMIT 5`,
        params,
      );

      // 3. Vendas por Vendedor (Barras) - Apenas se não for filtro de vendedor único
      let sellers: any[] = [];
      if (!vendedorId) {
        sellers = await this.dataSource.query(
          `SELECT 
            nome as label,
            SUM(vlr_liquido) as value
           FROM view_vendedor_venda_mes
           WHERE CAST(ano AS integer) = $1 AND CAST(mes AS integer) = $2
           GROUP BY nome ORDER BY value DESC LIMIT 5`,
          params,
        );
      }

      return {
        summary: {
          goal: parseFloat(kpis[0]?.goal) || 0,
          realized: parseFloat(kpis[0]?.realized) || 0,
          achievement: parseFloat(kpis[0]?.achievement) || 0,
        },
        categories: categories.map((c) => ({
          label: c.label,
          data: [parseFloat(c.value)],
        })),
        sellers: sellers.map((s) => ({
          label: s.label,
          data: [parseFloat(s.value)],
        })),
      };
    } catch (err) {
      console.error('[ANALYTICS] ❌ Erro ao buscar estatísticas do dashboard:', err.message);
      throw err;
    }
  }

  async getMvcData(
    year: number,
    vendedorId?: number,
    filters?: {
      estadoId?: number;
      municipioId?: number;
      dias?: number;
      situacao?: string;
    },
  ) {
    try {
      let where = ` WHERE CAST(ano AS integer) = CAST($1 AS integer)`;
      const params: any[] = [year];

      if (vendedorId) {
        params.push(vendedorId);
        where += ` AND nota_saida_vendedor_id = $${params.length}`;
      }

      // Busca dados pivoteados de vendas 12 meses
      const mvcData = await this.dataSource.query(
        `SELECT * FROM pivot_venda_mes_cliente` + where,
        params,
      );

      // Complementa com dados da view mvc (detalhes geográficos, carteira e ícone financeiro)
      let mvcWhere = ` WHERE 1=1`;
      const mvcParams: any[] = [];

      if (vendedorId) {
        mvcParams.push(vendedorId);
        mvcWhere += ` AND vendedor_id = $${mvcParams.length}`;
      }

      if (filters?.estadoId) {
        mvcParams.push(filters.estadoId);
        mvcWhere += ` AND estado_id = $${mvcParams.length}`;
      }

      if (filters?.municipioId) {
        mvcParams.push(filters.municipioId);
        mvcWhere += ` AND municipio_id = $${mvcParams.length}`;
      }

      if (filters?.dias) {
        mvcParams.push(filters.dias);
        mvcWhere += ` AND dias >= $${mvcParams.length}`;
      }

      if (filters?.situacao) {
        mvcParams.push(filters.situacao);
        mvcWhere += ` AND situacao = $${mvcParams.length}`;
      }

      const mvcDetails = await this.dataSource.query(
        `SELECT 
          id as cliente_id, situacao, ultima_compra, primeira_compra, 
          dias, municipio_descricao, estado_sigla, carteira,
          vendedor_reduzido,
          (SELECT CASE 
              WHEN EXISTS (SELECT 1 FROM titulo_receber tr WHERE tr.cliente_id = mvc.id AND tr.saldo > 0 AND tr.venc_real < CURRENT_DATE AND tr.reg_ativo = 'S') THEN 'R'
              WHEN EXISTS (SELECT 1 FROM titulo_receber tr WHERE tr.cliente_id = mvc.id AND tr.saldo > 0 AND tr.venc_real >= CURRENT_DATE AND tr.reg_ativo = 'S') THEN 'B'
              ELSE NULL 
           END) as financeiro_status
         FROM mvc` + mvcWhere,
        mvcParams,
      );

      // Merge dos dados
      return mvcData
        .map((item) => {
          const detail = mvcDetails.find((d) => String(d.cliente_id) === String(item.cliente_id));
          if (!detail) return null;

          // Cálculo de média dos últimos 3 meses
          const currentMonth = new Date().getMonth() + 1;
          const monthNames = [
            'janeiro',
            'fevereiro',
            'marco',
            'abril',
            'maio',
            'junho',
            'julho',
            'agosto',
            'setembro',
            'outubro',
            'novembro',
            'dezembro',
          ];

          let sumLast3 = 0;
          let count = 0;
          for (let i = 1; i <= 3; i++) {
            const targetMonthIdx = (currentMonth - i - 1 + 12) % 12;
            sumLast3 += parseFloat(item[monthNames[targetMonthIdx]] || 0);
            count++;
          }
          const average3Months = sumLast3 / count;
          const currentMonthSales = parseFloat(
            item[monthNames[currentMonth - 1]] || 0,
          );

          return {
            ...item,
            ...detail,
            average3Months,
            difference: currentMonthSales - average3Months,
          };
        })
        .filter((i) => i !== null);
    } catch (err) {
      console.error('[ANALYTICS] ❌ Erro ao buscar dados do MCV:', err.message);
      throw err;
    }
  }
}
