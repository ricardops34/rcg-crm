import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Vendedor } from '../../../commercial/entities/vendedor.entity';

@Injectable()
export class AnalyticsService {
  constructor(
    private dataSource: DataSource,
    @InjectRepository(Vendedor)
    private vendedorRepository: Repository<Vendedor>,
    private readonly cls: ClsService,
  ) {}

  async getVendedorIdByUser(systemUserId: number): Promise<number | null> {
    const vendedor = await this.vendedorRepository.findOne({
      where: { systemUsersId: systemUserId },
    });
    console.log('[MVC-DEBUG][BACK][SERVICE] getVendedorIdByUser', {
      systemUserId,
      vendedorId: vendedor ? vendedor.id : null,
    });
    return vendedor ? vendedor.id : null;
  }

  async getDashboardStats(year: number, month: number, vendedorId?: number) {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId || 1;

    let whereVendedorKpi = '';
    let whereVendedorCategory = '';
    let whereVendedorSellers = '';
    const params: any[] = [year.toString(), month.toString().padStart(2, '0'), systemUnitId];

    if (vendedorId) {
      params.push(vendedorId);
      whereVendedorKpi = ' AND vendedor_id = $4';
      whereVendedorCategory = ' AND vt.vendedor_id = $4';
      whereVendedorSellers = ' AND vendedor_id = $4';
    }

    try {
      // 1. KPIs Gerais
      const kpis = await this.dataSource.query(
        `SELECT 
          SUM(vlr_objetivo) as goal,
          SUM(vlr_liquido) as realized,
          AVG(perc_liquido) as achievement
         FROM view_vendedor_venda_mes 
         WHERE ano = $1 AND mes = $2 AND system_unit_id = $3` + whereVendedorKpi,
        params,
      );

      // 2. Vendas por Categoria (Rosca)
      const categories = await this.dataSource.query(
        `SELECT 
          vt.categoria as label,
          SUM(vt.vlr_liquido) as value
         FROM view_total_catogoria_mes vt
         JOIN vendedor v ON v.id = vt.vendedor_id
         WHERE vt.ano = $1 AND vt.mes = $2 AND v.system_unit_id = $3` +
          whereVendedorCategory +
          ` GROUP BY vt.categoria ORDER BY value DESC LIMIT 5`,
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
           WHERE ano = $1 AND mes = $2 AND system_unit_id = $3
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
      diasDe?: number;
      diasAte?: number;
      situacao?: string;
      search?: string;
      cliente_nome?: string;
      fantasia?: string;
    },
  ) {
    try {
      const user = this.cls.get('user');
      const systemUnitId = user?.unitId || 1;

      let where = ` WHERE mvc.system_unit_id = $2`;
      const params: any[] = [year.toString(), systemUnitId];

      if (vendedorId) {
        params.push(vendedorId);
        where += ` AND mvc.vendedor_id = $${params.length}`;
      }

      if (filters?.dias) {
        params.push(filters.dias);
        where += ` AND mvc.dias >= $${params.length}`;
      }

      if (filters?.diasDe) {
        params.push(filters.diasDe);
        where += ` AND mvc.dias >= $${params.length}`;
      }

      if (filters?.diasAte) {
        params.push(filters.diasAte);
        where += ` AND mvc.dias <= $${params.length}`;
      }

      if (filters?.situacao) {
        params.push(filters.situacao);
        where += ` AND mvc.situacao = $${params.length}`;
      }

      if (filters?.cliente_nome) {
        params.push(`%${filters.cliente_nome}%`);
        where += ` AND mvc.razao ILIKE $${params.length}`;
      }

      if (filters?.fantasia) {
        params.push(`%${filters.fantasia}%`);
        where += ` AND mvc.fantasia ILIKE $${params.length}`;
      }

      if (filters?.search) {
        params.push(`%${filters.search}%`);
        where += ` AND (mvc.razao ILIKE $${params.length} OR mvc.fantasia ILIKE $${params.length} OR mvc.codigo ILIKE $${params.length})`;
      }

      console.log('[MVC-DEBUG][BACK][SERVICE] getMvcData filtros', {
        year,
        vendedorId,
        filters,
        where,
        params,
      });

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
          CASE 
            WHEN EXISTS (
              SELECT 1 FROM titulo_receber tr 
              WHERE tr.cliente_id = mvc.id AND tr.saldo > 0 AND tr.reg_ativo = 'S' AND tr.venc_real < CURRENT_DATE AND tr.system_unit_id = $2
            ) THEN 'B'
            WHEN EXISTS (
              SELECT 1 FROM titulo_receber tr 
              WHERE tr.cliente_id = mvc.id AND tr.saldo > 0 AND tr.reg_ativo = 'S' AND tr.venc_real >= CURRENT_DATE AND tr.system_unit_id = $2
            ) THEN 'A'
            ELSE 'C'
          END as financeiro_status,
          CASE WHEN EXISTS (
            SELECT 1 FROM nota_saida ns
            WHERE ns.cliente_id = mvc.id
              AND ns.comodato = 'S'
              AND ns.reg_ativo = 'S'
              AND ns.system_unit_id = $2
          ) THEN 'S' ELSE 'N' END as tem_comodato,
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
        LEFT JOIN pivot_venda_mes_cliente p ON p.cliente_id = mvc.id AND p.ano = $1 AND p.system_unit_id = $2
        ${where}
      `;

      console.log('[MVC-DEBUG][BACK][SERVICE] getMvcData SQL final', {
        queryStr,
        params,
        where,
      });

      const result = await this.dataSource.query(queryStr, params);

      console.log('[MVC-DEBUG][BACK][SERVICE] getMvcData resultado bruto', {
        total: result.length,
        primeiroItem: result[0],
        // Diagnóstico de encoding: verificar se os bytes chegam corretos do banco
        razaoRaw: result[0]?.razao ?? result[0]?.cliente_nome ?? '(vazio)',
        codigoRaw: result[0]?.codigo ?? '(vazio)',
        diasRaw: result[0]?.dias ?? '(vazio)',
      });

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

      const items = result.map((item: any) => {
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
          venda_mes: currentMonthSales,
          average3Months,
          difference: currentMonthSales - average3Months,
        };
      });

      console.log('[MVC-DEBUG][BACK][SERVICE] getMvcData resultado processado', {
        total: items.length,
        primeiroItem: items[0],
        // Diagnóstico de cálculo: mês atual e valores usados
        diagnostico: items[0] ? {
          currentMonth: new Date().getMonth() + 1,
          venda_mes: items[0].venda_mes,
          average3Months: items[0].average3Months,
          difference: items[0].difference,
        } : null,
      });

      return items;
    } catch (err) {
      console.error('[ANALYTICS] ❌ Erro ao buscar dados do MCV:', err.message);
      throw err;
    }
  }
  paginateMvcItems(items: Array<any>, page: number, pageSize: number) {
    const safePage = Math.max(1, Number(page) || 1);
    const safePageSize = Math.max(1, Number(pageSize) || 10);
    const start = (safePage - 1) * safePageSize;
    const end = start + safePageSize;

    return {
      items: items.slice(start, end),
      total: items.length,
      page: safePage,
      pageSize: safePageSize,
      hasNext: items.length > end,
    };
  }
}
