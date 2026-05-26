-- ============================================================
-- RCG CRM - Fase 1 de Performance
-- Data: 2026-05-26
-- Baseado em: docs/database-performance-plan.md
--
-- Objetivo:
-- - acelerar MCV, agenda comercial, cliente 360 e analytics
-- - aplicar apenas melhorias de baixo risco e alto retorno
--
-- Observacoes:
-- - este script nao altera tipos de coluna nem cria FKs
-- - todos os indices usam IF NOT EXISTS
-- - revise tempo de execucao em producao antes de aplicar
-- ============================================================

-- ============================================================
-- 1. CRM / Agenda Comercial
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_atendimento_vendedor_inicio
  ON atendimento (vendedor_id, horario_inicial)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_atendimento_cliente_inicio_desc
  ON atendimento (cliente_id, horario_inicial DESC)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_atendimento_tipo_inicio
  ON atendimento (atendimento_tipo_id, horario_inicial)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_atendimento_retorno
  ON atendimento (retorno)
  WHERE dt_delete IS NULL AND retorno IS NOT NULL;

-- ============================================================
-- 2. MCV / Financeiro
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_titulo_receber_cliente_venc_real_aberto
  ON titulo_receber (cliente_id, venc_real)
  WHERE reg_ativo = 'S' AND saldo > 0;

CREATE INDEX IF NOT EXISTS idx_titulo_receber_cliente_saldo_venc_real
  ON titulo_receber (cliente_id, saldo, venc_real)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_titulo_receber_vendedor_venc_real
  ON titulo_receber (vendedor_id, venc_real)
  WHERE reg_ativo = 'S' AND saldo > 0;

CREATE INDEX IF NOT EXISTS idx_cliente_vendedor_reg_ativo
  ON cliente (vendedor_id, reg_ativo);

CREATE INDEX IF NOT EXISTS idx_cliente_regiao
  ON cliente (regiao_cliente_id);

CREATE INDEX IF NOT EXISTS idx_cliente_municipio
  ON cliente (municipio_id);

CREATE INDEX IF NOT EXISTS idx_cliente_ultima_compra
  ON cliente (ultima_compra);

CREATE INDEX IF NOT EXISTS idx_cliente_ultimo_atendimento
  ON cliente (ultimo_atendimento);

CREATE INDEX IF NOT EXISTS idx_cliente_cod_erp
  ON cliente (cod_erp);

-- ============================================================
-- 3. Faturamento / Vendas / Analytics
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_nota_saida_vendedor1_emissao
  ON nota_saida (vendedor1_id, dt_emissao)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_vendedor2_emissao
  ON nota_saida (vendedor2_id, dt_emissao)
  WHERE reg_ativo = 'S' AND vendedor2_id IS NOT NULL;

CREATE INDEX IF NOT EXISTS idx_nota_saida_cliente_emissao
  ON nota_saida (cliente_id, dt_emissao)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_ano_mes_vendedor1
  ON nota_saida (ano, mes, vendedor1_id)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_ano_mes_cliente
  ON nota_saida (ano, mes, cliente_id)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_reg_ativo_tipo_numero_titulo
  ON nota_saida (reg_ativo, tipo, numero_titulo);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_nota_saida
  ON nota_saida_item (nota_saida_id);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_produto_ano_mes
  ON nota_saida_item (produto_id, ano, mes);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_cliente_ano_mes
  ON nota_saida_item (cliente_id, ano, mes);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_vendedor1_ano_mes
  ON nota_saida_item (vendedor1_id, ano, mes);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_vendedor2_ano_mes
  ON nota_saida_item (vendedor2_id, ano, mes)
  WHERE vendedor2_id IS NOT NULL;

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_emissao
  ON nota_saida_item (dt_emissao);

-- ============================================================
-- 4. Metas
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_mes_lookup
  ON meta_vendedor_mes (vendedor_id, ano, mes)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_categoria_lookup
  ON meta_vendedor_categoria (meta_vendedor_mes_id, cod_erp)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_categoria_categoria
  ON meta_vendedor_categoria (categoria_id)
  WHERE dt_delete IS NULL;

-- ============================================================
-- 5. Catalogo / Estoque / Preco
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_tabela_preco_item_tabela_produto
  ON tabela_preco_item (tabela_preco_id, produto_id);

CREATE INDEX IF NOT EXISTS idx_estoque_produto_armazem
  ON estoque (produto_id, armazem_id);

CREATE INDEX IF NOT EXISTS idx_produto_categoria
  ON produto (categoria_id);

CREATE INDEX IF NOT EXISTS idx_produto_sub_categoria
  ON produto (sub_categoria_id);

CREATE INDEX IF NOT EXISTS idx_produto_cod_erp
  ON produto (cod_erp);

-- ============================================================
-- 6. Apoio a relacoes comerciais
-- ============================================================

DO $$
BEGIN
  IF to_regclass('public.vendedor_cliente') IS NOT NULL THEN
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_vendedor_cliente_vendedor ON vendedor_cliente (vendedor_id)';
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_vendedor_cliente_cliente ON vendedor_cliente (cliente_id)';
  ELSE
    RAISE NOTICE 'Tabela public.vendedor_cliente nao existe. Indices ignorados.';
  END IF;
END
$$;

DO $$
BEGIN
  IF to_regclass('public.supervisor_vendedor') IS NOT NULL THEN
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_supervisor_vendedor_supervisor ON supervisor_vendedor (supervisor_id)';
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_supervisor_vendedor_vendedor ON supervisor_vendedor (vendedor_id)';
  ELSE
    RAISE NOTICE 'Tabela public.supervisor_vendedor nao existe. Indices ignorados.';
  END IF;
END
$$;

-- ============================================================
-- 7. Pos aplicacao
-- ============================================================

-- Recomendado apos a criacao dos indices:
-- ANALYZE atendimento;
-- ANALYZE titulo_receber;
-- ANALYZE cliente;
-- ANALYZE nota_saida;
-- ANALYZE nota_saida_item;
-- ANALYZE meta_vendedor_mes;
-- ANALYZE meta_vendedor_categoria;
-- ANALYZE tabela_preco_item;
-- ANALYZE estoque;
