import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';

@Injectable()
export class ClienteDetailsService {
  constructor(private dataSource: DataSource) {}

  async getComodato(clienteId: number) {
    return this.dataSource.query(
      `SELECT id, nota_fiscal, serie_fiscal, dt_emissao, vlr_mercadoria 
       FROM nota_saida 
       WHERE cliente_id = $1 AND comodato = 'S' AND reg_ativo = 'S'
       ORDER BY dt_emissao DESC`,
      [clienteId],
    );
  }

  async getMix(clienteId: number) {
    return this.dataSource.query(
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
  }

  async getFinanceiro(clienteId: number) {
    return this.dataSource.query(
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
  }

  async getNotasFiscais(clienteId: number) {
    return this.dataSource.query(
      `SELECT id, nota_fiscal, serie_fiscal, dt_emissao, vlr_bruto, vlr_liquido, vendedor_nome
       FROM cliente_notafiscal 
       WHERE cliente_id = $1
       ORDER BY dt_emissao DESC`,
      [clienteId],
    );
  }

  async getAtendimentos(clienteId: number) {
    return this.dataSource.query(
      `SELECT a.id, a.dt_atendimento, a.observacao, v.nome as vendedor_nome, ta.descricao as tipo_atendimento
       FROM atendimento a
       JOIN vendedor v ON v.id = a.vendedor_id
       JOIN atendimento_tipo ta ON ta.id = a.atendimento_tipo_id
       WHERE a.cliente_id = $1
       ORDER BY a.dt_atendimento DESC`,
      [clienteId],
    );
  }
}
