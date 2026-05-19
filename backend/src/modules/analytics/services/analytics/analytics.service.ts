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
    const params = [year, month];

    if (vendedorId) {
      whereVendedor = ' AND vendedor_id = $3';
      params.push(vendedorId);
    }

    const kpis = await this.dataSource.query(
      `SELECT 
        SUM(vlr_objetivo) as goal,
        SUM(vlr_liquido) as realized,
        AVG(perc_liquido) as achievement
       FROM view_vendedor_venda_mes 
       WHERE ano = $1::text AND mes = $2::text` + whereVendedor,
      params,
    );

    const categories = await this.dataSource.query(
      `SELECT 
        descricao as label,
        SUM(vlr_liquido) as value
       FROM view_total_catogoria_mes
       WHERE ano = $1::text AND mes = $2::text` +
        whereVendedor +
        ` GROUP BY descricao ORDER BY value DESC LIMIT 5`,
      params,
    );

    return {
      summary: kpis[0] || { goal: 0, realized: 0, achievement: 0 },
      categories: categories.map((c) => ({
        label: c.label,
        data: [parseFloat(c.value)],
      })),
    };
  }

  async getMvcData(year: number, vendedorId: number) {
    // Busca dados pivoteados de vendas 12 meses
    const mvcData = await this.dataSource.query(
      `SELECT * FROM pivot_venda_mes_cliente 
       WHERE ano = $1::text AND nota_saida_vendedor_id = $2`,
      [year, vendedorId],
    );

    // Complementa com dados da view mvc (detalhes geográficos e de carteira)
    const mvcDetails = await this.dataSource.query(
      `SELECT 
        id as cliente_id, situacao, ultima_compra, primeira_compra, 
        dias, municipio_descricao, estado_sigla, carteira 
       FROM mvc WHERE vendedor_id = $1`,
      [vendedorId],
    );

    // Merge dos dados
    return mvcData.map((item) => {
      const detail = mvcDetails.find((d) => d.cliente_id === item.cliente_id);

      // Cálculo de média dos últimos 3 meses (lógica baseada no Archaeologist)
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
    });
  }
}
