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
    const params: any[] = [year.toString(), month.toString().padStart(2, '0')];

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
         WHERE ano = $1 AND mes = $2` + whereVendedor,
        params,
      );

      // 2. Vendas por Categoria (Rosca)
      const categories = await this.dataSource.query(
        `SELECT 
          categoria as label,
          SUM(vlr_liquido) as value
         FROM view_total_catogoria_mes
         WHERE ano = $1 AND mes = $2` +
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
           WHERE ano = $1 AND mes = $2
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
      dias?: number;
      situacao?: string;
      search?: string;
    },
  ) {
    try {
      let where = ` WHERE 1=1`;
      const params: any[] = [year.toString()]; // Pass year as string to leverage unique composite B-tree index

      if (vendedorId) {
        params.push(vendedorId);
        where += ` AND mvc.vendedor_id = $${params.length}`;
      }

      if (filters?.dias) {
        params.push(filters.dias);
        where += ` AND mvc.dias >= $${params.length}`;
      }

      if (filters?.situacao) {
        params.push(filters.situacao);
        where += ` AND mvc.situacao = $${params.length}`;
      }

      if (filters?.search) {
        params.push(`%${filters.search}%`);
        where += ` AND (mvc.razao ILIKE $${params.length} OR mvc.fantasia ILIKE $${params.length} OR mvc.codigo ILIKE $${params.length})`;
      }

      const queryStr = `
        SELECT 
          mvc.id as cliente_id,
          mvc.codigo,
          mvc.situacao,
          mvc.razao as cliente_nome,
          mvc.fantasia,
          mvc.primeira_compra,
          mvc.ultima_compra,
          mvc.dias,
          mvc.municipio_descricao,
          mvc.estado_sigla,
          mvc.carteira,
          mvc.vendedor_reduzido,
          mvc.vendedor_id,
          mvc.estado_id,
          mvc.municipio_id,
          mvc.financeiro_status,
          COALESCE(p.ano, $1) as ano,
          COALESCE(p.janeiro, 0) as janeiro,
          COALESCE(p.fevereiro, 0) as fevereiro,
          COALESCE(p.marco, 0) as marco,
          COALESCE(p.abril, 0) as abril,
          COALESCE(p.maio, 0) as maio,
          COALESCE(p.junho, 0) as junho,
          COALESCE(p.julho, 0) as julho,
          COALESCE(p.agosto, 0) as agosto,
          COALESCE(p.setembro, 0) as setembro,
          COALESCE(p.outubro, 0) as outubro,
          COALESCE(p.novembro, 0) as novembro,
          COALESCE(p.dezembro, 0) as dezembro
        FROM mvc
        LEFT JOIN pivot_venda_mes_cliente p ON p.cliente_id = mvc.id AND p.ano = $1
        ${where}
      `;

      const result = await this.dataSource.query(queryStr, params);

      // Cálculo de média dos últimos 3 meses em memória
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

      return result.map((item: any) => {
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
          average3Months,
          difference: currentMonthSales - average3Months,
        };
      });
    } catch (err) {
      console.error('[ANALYTICS] ❌ Erro ao buscar dados do MCV:', err.message);
      throw err;
    }
  }
}
