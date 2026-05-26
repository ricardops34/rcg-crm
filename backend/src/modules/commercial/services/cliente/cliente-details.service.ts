import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';

@Injectable()
export class ClienteDetailsService {
  constructor(private dataSource: DataSource) {}

  async getComodato(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getComodato clienteId=', clienteId);
      const result = await this.dataSource.query(
        `SELECT id, nota_fiscal, serie_fiscal, dt_emissao, vlr_mercadoria
         FROM nota_saida
         WHERE cliente_id = $1 AND comodato = 'S' AND reg_ativo = 'S'
         ORDER BY dt_emissao DESC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getComodato OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] getComodato ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getMix(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getMix clienteId=', clienteId);
      const result = await this.dataSource.query(
        `SELECT
          p.id as produto_id, p.descricao as produto_nome,
          SUM(nsi.qtd) as total_qtd,
          MAX(nsi.dt_emissao) as ultima_compra,
          SUM(nsi.vlr_total) as total_valor
         FROM nota_saida_item nsi
         JOIN produto p ON p.id = nsi.produto_id
         WHERE nsi.cliente_id = $1 AND nsi.reg_ativo = 'S'
         GROUP BY p.id, p.descricao
         ORDER BY ultima_compra DESC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getMix OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] getMix ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getFinanceiro(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getFinanceiro clienteId=', clienteId);
      const result = await this.dataSource.query(
        `SELECT
          id, numero, parcela, prefixo, emissao, venc_real as vencimento, baixa, saldo, valor,
          CASE
            WHEN venc_real < CURRENT_DATE AND baixa IS NULL THEN 'Vencido'
            WHEN venc_real = CURRENT_DATE AND baixa IS NULL THEN 'Vencendo'
            WHEN baixa IS NOT NULL THEN 'Pago'
            ELSE 'A Vencer'
          END as status
         FROM titulo_receber
         WHERE cliente_id = $1 AND reg_ativo = 'S' AND saldo > 0
         ORDER BY venc_real ASC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getFinanceiro OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] getFinanceiro ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getNotasFiscais(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getNotasFiscais clienteId=', clienteId);
      const result = await this.dataSource.query(
        `SELECT
          id,
          nota_fiscal,
          serie_fiscal,
          dt_emissao,
          vlr_bruto,
          COALESCE(vlr_itens, vlr_mercadoria, vlr_bruto) as vlr_liquido,
          nome as vendedor_nome
         FROM cliente_notafiscal
         WHERE cliente_id = $1
         ORDER BY dt_emissao DESC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getNotasFiscais OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] getNotasFiscais ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getAtendimentos(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getAtendimentos clienteId=', clienteId);
      const result = await this.dataSource.query(
        `SELECT
          a.id,
          a.horario_inicial as dt_atendimento,
          a.observacao,
          v.nome as vendedor_nome,
          ta.descricao as tipo_atendimento
         FROM atendimento a
         LEFT JOIN vendedor v ON v.id = a.vendedor_id
         LEFT JOIN atendimento_tipo ta ON ta.id = a.atendimento_tipo_id
         WHERE a.cliente_id = $1
           AND a.dt_delete IS NULL
         ORDER BY a.horario_inicial DESC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getAtendimentos OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] getAtendimentos ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getEstimatedStock(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getEstimatedStock clienteId=', clienteId);
      const result = await this.dataSource.query(
        `WITH compras AS (
          SELECT
            nsi.produto_id,
            p.descricao AS produto_nome,
            COALESCE(p.um, 'UN') AS um,
            COALESCE(NULLIF(p.qtd_embalagem, 0), 1) AS qtd_embalagem,
            nsi.dt_emissao::date AS dt_emissao,
            COALESCE(nsi.qtd, 0) AS qtd
          FROM nota_saida_item nsi
          JOIN produto p ON p.id = nsi.produto_id
          WHERE nsi.cliente_id = $1
            AND nsi.reg_ativo = 'S'
            AND nsi.dt_emissao >= CURRENT_DATE - INTERVAL '180 days'
        ),
        compras_intervalo AS (
          SELECT
            c.*,
            LAG(c.dt_emissao) OVER (PARTITION BY c.produto_id ORDER BY c.dt_emissao) AS compra_anterior
          FROM compras c
        ),
        metricas AS (
          SELECT
            ci.produto_id,
            MAX(ci.produto_nome) AS produto_nome,
            MAX(ci.um) AS um,
            MAX(ci.qtd_embalagem) AS qtd_embalagem,
            SUM(ci.qtd) AS qtd_total_periodo,
            MIN(ci.dt_emissao) AS primeira_compra_periodo,
            MAX(ci.dt_emissao) AS data_ultima_compra,
            AVG(
              CASE
                WHEN ci.compra_anterior IS NOT NULL THEN (ci.dt_emissao - ci.compra_anterior)
                ELSE NULL
              END
            )::numeric AS intervalo_medio_dias
          FROM compras_intervalo ci
          GROUP BY ci.produto_id
        ),
        ultima_compra AS (
          SELECT DISTINCT ON (c.produto_id)
            c.produto_id,
            c.dt_emissao AS data_ultima_compra,
            c.qtd AS qtd_ultima_compra
          FROM compras c
          ORDER BY c.produto_id, c.dt_emissao DESC
        ),
        base AS (
          SELECT
            m.produto_id,
            m.produto_nome,
            m.um,
            m.qtd_embalagem,
            uc.data_ultima_compra,
            COALESCE(uc.qtd_ultima_compra, 0) AS qtd_ultima_compra,
            (m.qtd_total_periodo / GREATEST(1, (CURRENT_DATE - m.primeira_compra_periodo) + 1)) AS consumo_medio_dia,
            COALESCE(m.intervalo_medio_dias, 30) AS intervalo_medio_dias,
            GREATEST(
              0,
              COALESCE(uc.qtd_ultima_compra, 0) -
              (
                (m.qtd_total_periodo / GREATEST(1, (CURRENT_DATE - m.primeira_compra_periodo) + 1)) *
                GREATEST(0, CURRENT_DATE - uc.data_ultima_compra)
              )
            ) AS estoque_estimado
          FROM metricas m
          JOIN ultima_compra uc ON uc.produto_id = m.produto_id
        )
        SELECT
          b.produto_id,
          b.produto_nome,
          b.um,
          ROUND(b.qtd_embalagem::numeric, 2) AS qtd_embalagem,
          b.data_ultima_compra,
          ROUND(b.qtd_ultima_compra::numeric, 2) AS qtd_ultima_compra,
          ROUND(b.consumo_medio_dia::numeric, 4) AS consumo_medio_dia,
          ROUND(b.intervalo_medio_dias::numeric, 2) AS intervalo_medio_dias,
          ROUND(b.estoque_estimado::numeric, 2) AS estoque_estimado,
          CASE
            WHEN b.consumo_medio_dia > 0 THEN CURRENT_DATE + CEIL(b.estoque_estimado / b.consumo_medio_dia)::int
            ELSE NULL
          END AS data_estimada_necessidade,
          CASE
            WHEN b.qtd_embalagem > 1 THEN
              CEIL(
                GREATEST(0, (b.consumo_medio_dia * b.intervalo_medio_dias) - b.estoque_estimado) / b.qtd_embalagem
              ) * b.qtd_embalagem
            ELSE
              CEIL(GREATEST(0, (b.consumo_medio_dia * b.intervalo_medio_dias) - b.estoque_estimado))
          END AS qtd_sugerida,
          ROUND(
            CASE
              WHEN b.consumo_medio_dia > 0 THEN b.estoque_estimado / b.consumo_medio_dia
              ELSE 0
            END::numeric,
            1
          ) AS cobertura_dias
        FROM base b
        ORDER BY data_estimada_necessidade NULLS LAST, qtd_sugerida DESC, b.produto_nome`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getEstimatedStock OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] getEstimatedStock ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }
}
