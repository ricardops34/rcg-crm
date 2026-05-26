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
      console.error('[360-DEBUG][BACK] ❌ getComodato ERRO', { clienteId, message: err.message, detail: err.detail });
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
      console.error('[360-DEBUG][BACK] ❌ getMix ERRO', { clienteId, message: err.message, detail: err.detail });
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
      console.error('[360-DEBUG][BACK] ❌ getFinanceiro ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getNotasFiscais(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getNotasFiscais clienteId=', clienteId);
      const result = await this.dataSource.query(
        `SELECT id, nota_fiscal, serie_fiscal, dt_emissao, vlr_bruto, vlr_liquido, vendedor_nome
         FROM cliente_notafiscal 
         WHERE cliente_id = $1
         ORDER BY dt_emissao DESC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getNotasFiscais OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] ❌ getNotasFiscais ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getAtendimentos(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getAtendimentos clienteId=', clienteId);
      const result = await this.dataSource.query(
        `SELECT a.id, a.dt_atendimento, a.observacao, v.nome as vendedor_nome, ta.descricao as tipo_atendimento
         FROM atendimento a
         JOIN vendedor v ON v.id = a.vendedor_id
         JOIN atendimento_tipo ta ON ta.id = a.atendimento_tipo_id
         WHERE a.cliente_id = $1
         ORDER BY a.dt_atendimento DESC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getAtendimentos OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] ❌ getAtendimentos ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }

  async getPurchaseSuggestion(clienteId: number) {
    try {
      console.log('[360-DEBUG][BACK] getPurchaseSuggestion clienteId=', clienteId);
      const result = await this.dataSource.query(
        `WITH historico AS (
          SELECT 
            nsi.produto_id,
            p.descricao as produto_nome,
            SUM(nsi.qtd) / 6.0 as media_mensal,
            MAX(nsi.dt_emissao) as data_ultima_compra
          FROM nota_saida_item nsi
          JOIN produto p ON p.id = nsi.produto_id
          WHERE nsi.cliente_id = $1 
            AND nsi.dt_emissao >= CURRENT_DATE - INTERVAL '6 months'
            AND nsi.reg_ativo = 'S'
          GROUP BY nsi.produto_id, p.descricao
        ),
        mes_atual AS (
          SELECT 
            nsi.produto_id,
            SUM(nsi.qtd) as qtd_atual
          FROM nota_saida_item nsi
          WHERE nsi.cliente_id = $1 
            AND nsi.ano = EXTRACT(YEAR FROM CURRENT_DATE)::text
            AND nsi.mes = LPAD(EXTRACT(MONTH FROM CURRENT_DATE)::text, 2, '0')
            AND nsi.reg_ativo = 'S'
          GROUP BY nsi.produto_id
        )
        SELECT 
          h.produto_id,
          h.produto_nome,
          ROUND(h.media_mensal::numeric, 2) as media_mensal,
          COALESCE(ma.qtd_atual, 0) as qtd_atual,
          GREATEST(0, ROUND((h.media_mensal - COALESCE(ma.qtd_atual, 0))::numeric, 2)) as sugestao,
          h.data_ultima_compra
        FROM historico h
        LEFT JOIN mes_atual ma ON ma.produto_id = h.produto_id
        WHERE (h.media_mensal - COALESCE(ma.qtd_atual, 0)) > 0
        ORDER BY sugestao DESC`,
        [clienteId],
      );
      console.log('[360-DEBUG][BACK] getPurchaseSuggestion OK total=', result.length);
      return result;
    } catch (err) {
      console.error('[360-DEBUG][BACK] ❌ getPurchaseSuggestion ERRO', { clienteId, message: err.message, detail: err.detail });
      throw err;
    }
  }
}
