import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';

type ViewMode = 'month' | 'week' | 'day';

@Injectable()
export class AgendaComercialService {
  constructor(private readonly dataSource: DataSource) {}

  async getAgenda(
    view: ViewMode,
    date: string,
    vendedorId?: number,
    atendimentoTipoId?: number,
    start?: string,
    end?: string,
  ) {
    const referenceDate = this.parseReferenceDate(date);
    const explicitRange = this.parseExplicitRange(start, end);
    const { periodStart, periodEnd } =
      explicitRange || this.getPeriod(view, referenceDate);

    const sales = await this.getSalesEvents(periodStart, periodEnd, vendedorId);
    const attendances = await this.getAttendanceEvents(
      periodStart,
      periodEnd,
      vendedorId,
      atendimentoTipoId,
    );

    const events = [...sales, ...attendances].sort((left, right) =>
      String(left.inicio).localeCompare(String(right.inicio)),
    );

    const summary = sales.reduce(
      (accumulator, current) => {
        accumulator.totalNotas += 1;
        accumulator.totalValor += Number(current.valor || 0);
        return accumulator;
      },
      {
        totalNotas: 0,
        totalValor: 0,
        totalAtendimentos: attendances.length,
      },
    );

    return {
      view,
      referenceDate: this.formatDate(referenceDate),
      periodStart: this.formatDate(periodStart),
      periodEnd: this.formatDate(periodEnd),
      summary,
      events,
    };
  }

  private async getSalesEvents(
    periodStart: Date,
    periodEnd: Date,
    vendedorId?: number,
  ) {
    const params: any[] = [this.formatDate(periodStart), this.formatDate(periodEnd)];
    let vendorFilter = '';

    if (vendedorId) {
      params.push(vendedorId);
      vendorFilter =
        ' AND (ns.vendedor1_id = $3 OR ns.vendedor2_id = $3)';
    }

    return this.dataSource.query(
      `SELECT
        CONCAT('sale-', ns.id) as id,
        'venda' as tipo,
        ns.id as "notaSaidaId",
        ns.nota_fiscal as "notaFiscal",
        COALESCE(
          CASE WHEN ns.dt_nfe IS NOT NULL
            THEN (ns.dt_nfe::text || ' ' || COALESCE(ns.hr_nfe, '00:00') || ':00')::timestamp
          END,
          ns.dt_emissao::timestamp
        ) as inicio,
        COALESCE(
          CASE WHEN ns.dt_nfe IS NOT NULL
            THEN (ns.dt_nfe::text || ' ' || COALESCE(ns.hr_nfe, '00:00') || ':00')::timestamp
          END,
          ns.dt_emissao::timestamp
        ) as fim,
        COALESCE(ns.dt_nfe, ns.dt_emissao) as data,
        COALESCE(ns.vlr_bruto, ns.vlr_mercadoria, 0) as valor,
        CONCAT('NF ', COALESCE(ns.nota_fiscal, ns.id::text), ' - ', COALESCE(c.razao, 'Cliente sem nome')) as titulo,
        c.id as "clienteId",
        c.razao as "clienteNome",
        COALESCE(v.nome, v2.nome, 'Sem vendedor') as "vendedorNome",
        COALESCE(ns.vendedor1_id, ns.vendedor2_id) as "vendedorId",
        '#0f766e' as cor,
        CONCAT(
          'NF-e transmitida em ',
          TO_CHAR(COALESCE(ns.dt_nfe, ns.dt_emissao), 'DD/MM/YYYY'),
          CASE WHEN ns.hr_nfe IS NOT NULL THEN CONCAT(' às ', ns.hr_nfe) ELSE '' END
        ) as observacao
       FROM nota_saida ns
       LEFT JOIN cliente c ON c.id = ns.cliente_id
       LEFT JOIN vendedor v ON v.id = ns.vendedor1_id
       LEFT JOIN vendedor v2 ON v2.id = ns.vendedor2_id
       WHERE ns.reg_ativo = 'S'
         AND COALESCE(ns.dt_nfe, ns.dt_emissao) BETWEEN $1::date AND $2::date
         ${vendorFilter}
       ORDER BY inicio ASC, ns.nota_fiscal ASC`,
      params,
    );
  }

  private async getAttendanceEvents(
    periodStart: Date,
    periodEnd: Date,
    vendedorId?: number,
    atendimentoTipoId?: number,
  ) {
    const params: any[] = [this.formatDate(periodStart), this.formatDate(periodEnd)];
    let vendorFilter = '';
    let atendimentoTipoFilter = '';

    if (vendedorId) {
      params.push(vendedorId);
      vendorFilter = ' AND a.vendedor_id = $3';
    }

    if (atendimentoTipoId) {
      params.push(atendimentoTipoId);
      atendimentoTipoFilter = ` AND a.atendimento_tipo_id = $${params.length}`;
    }

    return this.dataSource.query(
      `SELECT
        CONCAT('attendance-', a.id) as id,
        'atendimento' as tipo,
        a.id as "atendimentoId",
        a.atendimento_tipo_id as "atendimentoTipoId",
        ta.descricao as "atendimentoTipoDescricao",
        ta.icone as icone,
        a.horario_inicial as inicio,
        a.horario_final as fim,
        a.horario_inicial::date as data,
        0 as valor,
        COALESCE(a.titulo, ta.descricao, 'Atendimento') as titulo,
        a.cliente_id as "clienteId",
        COALESCE(c.razao, a.nome, 'Cliente não informado') as "clienteNome",
        v.nome as "vendedorNome",
        a.vendedor_id as "vendedorId",
        COALESCE(NULLIF(a.cor, ''), NULLIF(ta.cor, ''), '#2563eb') as cor,
        a.observacao as observacao,
        a.retorno as retorno
       FROM atendimento a
       LEFT JOIN atendimento_tipo ta ON ta.id = a.atendimento_tipo_id
       LEFT JOIN cliente c ON c.id = a.cliente_id
       LEFT JOIN vendedor v ON v.id = a.vendedor_id
       WHERE a.dt_delete IS NULL
         AND a.horario_inicial::date BETWEEN $1::date AND $2::date
         ${vendorFilter}
         ${atendimentoTipoFilter}
       ORDER BY a.horario_inicial ASC`,
      params,
    );
  }

  private parseReferenceDate(date?: string) {
    if (!date) {
      return new Date();
    }

    const parsedDate = new Date(`${date}T12:00:00`);
    return Number.isNaN(parsedDate.getTime()) ? new Date() : parsedDate;
  }

  private parseExplicitRange(start?: string, end?: string) {
    if (!start || !end) {
      return undefined;
    }

    const periodStart = new Date(`${start}T00:00:00`);
    const periodEnd = new Date(`${end}T00:00:00`);

    if (
      Number.isNaN(periodStart.getTime()) ||
      Number.isNaN(periodEnd.getTime())
    ) {
      return undefined;
    }

    return {
      periodStart,
      periodEnd,
    };
  }

  private getPeriod(view: ViewMode, referenceDate: Date) {
    const normalizedDate = new Date(referenceDate);
    normalizedDate.setHours(0, 0, 0, 0);

    if (view === 'day') {
      return {
        periodStart: normalizedDate,
        periodEnd: normalizedDate,
      };
    }

    if (view === 'week') {
      const dayOfWeek = normalizedDate.getDay();
      const diff = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
      const periodStart = new Date(normalizedDate);
      periodStart.setDate(normalizedDate.getDate() + diff);

      const periodEnd = new Date(periodStart);
      periodEnd.setDate(periodStart.getDate() + 6);

      return { periodStart, periodEnd };
    }

    const periodStart = new Date(
      normalizedDate.getFullYear(),
      normalizedDate.getMonth(),
      1,
    );
    const periodEnd = new Date(
      normalizedDate.getFullYear(),
      normalizedDate.getMonth() + 1,
      0,
    );

    return { periodStart, periodEnd };
  }

  private formatDate(date: Date) {
    return date.toISOString().slice(0, 10);
  }
}
