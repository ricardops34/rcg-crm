-- ============================================================
-- RCG CRM - Fase 2 de Performance / Integridade
-- Constraints e unicidade segura
-- Data: 2026-05-26
--
-- Objetivo:
-- - adicionar FKs em modo NOT VALID
-- - adicionar unicidade de negocio quando nao houver duplicidade
--
-- Observacoes:
-- - execute primeiro: database/performance-phase2-audit.sql
-- - FKs NOT VALID passam a proteger dados novos
-- - validacao completa pode ser feita depois com ALTER TABLE ... VALIDATE CONSTRAINT
-- ============================================================

-- ============================================================
-- 1. Foreign Keys seguras (NOT VALID)
-- ============================================================

DO $$
BEGIN
  IF to_regclass('public.cliente') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE cliente
      ADD CONSTRAINT fk_cliente_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_cliente_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.cliente') IS NOT NULL
     AND to_regclass('public.municipio') IS NOT NULL THEN
    ALTER TABLE cliente
      ADD CONSTRAINT fk_cliente_municipio
      FOREIGN KEY (municipio_id) REFERENCES municipio(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_cliente_municipio ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.cliente') IS NOT NULL
     AND to_regclass('public.condicao_pagamento') IS NOT NULL THEN
    ALTER TABLE cliente
      ADD CONSTRAINT fk_cliente_condicao_pagamento
      FOREIGN KEY (condicao_pagamento_id) REFERENCES condicao_pagamento(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_cliente_condicao_pagamento ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.atendimento') IS NOT NULL
     AND to_regclass('public.cliente') IS NOT NULL THEN
    ALTER TABLE atendimento
      ADD CONSTRAINT fk_atendimento_cliente
      FOREIGN KEY (cliente_id) REFERENCES cliente(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_atendimento_cliente ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.atendimento') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE atendimento
      ADD CONSTRAINT fk_atendimento_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_atendimento_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.atendimento') IS NOT NULL
     AND to_regclass('public.atendimento_tipo') IS NOT NULL THEN
    ALTER TABLE atendimento
      ADD CONSTRAINT fk_atendimento_tipo
      FOREIGN KEY (atendimento_tipo_id) REFERENCES atendimento_tipo(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_atendimento_tipo ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.titulo_receber') IS NOT NULL
     AND to_regclass('public.cliente') IS NOT NULL THEN
    ALTER TABLE titulo_receber
      ADD CONSTRAINT fk_titulo_receber_cliente
      FOREIGN KEY (cliente_id) REFERENCES cliente(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_titulo_receber_cliente ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.titulo_receber') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE titulo_receber
      ADD CONSTRAINT fk_titulo_receber_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_titulo_receber_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.nota_saida') IS NOT NULL
     AND to_regclass('public.cliente') IS NOT NULL THEN
    ALTER TABLE nota_saida
      ADD CONSTRAINT fk_nota_saida_cliente
      FOREIGN KEY (cliente_id) REFERENCES cliente(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_nota_saida_cliente ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.nota_saida_item') IS NOT NULL
     AND to_regclass('public.nota_saida') IS NOT NULL THEN
    ALTER TABLE nota_saida_item
      ADD CONSTRAINT fk_nota_saida_item_nota_saida
      FOREIGN KEY (nota_saida_id) REFERENCES nota_saida(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_nota_saida_item_nota_saida ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.nota_saida_item') IS NOT NULL
     AND to_regclass('public.produto') IS NOT NULL THEN
    ALTER TABLE nota_saida_item
      ADD CONSTRAINT fk_nota_saida_item_produto
      FOREIGN KEY (produto_id) REFERENCES produto(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_nota_saida_item_produto ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.meta_vendedor_mes') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE meta_vendedor_mes
      ADD CONSTRAINT fk_meta_vendedor_mes_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_meta_vendedor_mes_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.meta_vendedor_categoria') IS NOT NULL
     AND to_regclass('public.meta_vendedor_mes') IS NOT NULL THEN
    ALTER TABLE meta_vendedor_categoria
      ADD CONSTRAINT fk_meta_vendedor_categoria_mes
      FOREIGN KEY (meta_vendedor_mes_id) REFERENCES meta_vendedor_mes(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_meta_vendedor_categoria_mes ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.tabela_preco_item') IS NOT NULL
     AND to_regclass('public.tabela_preco') IS NOT NULL THEN
    ALTER TABLE tabela_preco_item
      ADD CONSTRAINT fk_tabela_preco_item_tabela
      FOREIGN KEY (tabela_preco_id) REFERENCES tabela_preco(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_tabela_preco_item_tabela ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.tabela_preco_item') IS NOT NULL
     AND to_regclass('public.produto') IS NOT NULL THEN
    ALTER TABLE tabela_preco_item
      ADD CONSTRAINT fk_tabela_preco_item_produto
      FOREIGN KEY (produto_id) REFERENCES produto(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_tabela_preco_item_produto ja existe.';
END
$$;

-- ============================================================
-- 2. Unicidade de negocio
-- ============================================================

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.vendedor_cliente') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT vendedor_id, cliente_id
      FROM vendedor_cliente
      GROUP BY vendedor_id, cliente_id
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_vendedor_cliente ON vendedor_cliente (vendedor_id, cliente_id)';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em vendedor_cliente. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.supervisor_vendedor') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT supervisor_id, vendedor_id
      FROM supervisor_vendedor
      GROUP BY supervisor_id, vendedor_id
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_supervisor_vendedor ON supervisor_vendedor (supervisor_id, vendedor_id)';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em supervisor_vendedor. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.meta_vendedor_mes') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT vendedor_id, ano, mes, tipo
      FROM meta_vendedor_mes
      WHERE dt_delete IS NULL
      GROUP BY vendedor_id, ano, mes, tipo
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_meta_vendedor_mes_ativo ON meta_vendedor_mes (vendedor_id, ano, mes, tipo) WHERE dt_delete IS NULL';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em meta_vendedor_mes. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.tabela_preco_item') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT tabela_preco_id, produto_id
      FROM tabela_preco_item
      GROUP BY tabela_preco_id, produto_id
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_tabela_preco_item ON tabela_preco_item (tabela_preco_id, produto_id)';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em tabela_preco_item. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.cliente_acesso') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT login
      FROM cliente_acesso
      WHERE login IS NOT NULL
      GROUP BY login
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_cliente_acesso_login ON cliente_acesso (login) WHERE login IS NOT NULL';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em cliente_acesso.login. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

-- ============================================================
-- 3. Validacao posterior
-- ============================================================

-- Execute depois de corrigir dados antigos:
-- ALTER TABLE cliente VALIDATE CONSTRAINT fk_cliente_vendedor;
-- ALTER TABLE cliente VALIDATE CONSTRAINT fk_cliente_municipio;
-- ALTER TABLE cliente VALIDATE CONSTRAINT fk_cliente_condicao_pagamento;
-- ALTER TABLE atendimento VALIDATE CONSTRAINT fk_atendimento_cliente;
-- ALTER TABLE atendimento VALIDATE CONSTRAINT fk_atendimento_vendedor;
-- ALTER TABLE atendimento VALIDATE CONSTRAINT fk_atendimento_tipo;
-- ALTER TABLE titulo_receber VALIDATE CONSTRAINT fk_titulo_receber_cliente;
-- ALTER TABLE titulo_receber VALIDATE CONSTRAINT fk_titulo_receber_vendedor;
-- ALTER TABLE nota_saida VALIDATE CONSTRAINT fk_nota_saida_cliente;
-- ALTER TABLE nota_saida_item VALIDATE CONSTRAINT fk_nota_saida_item_nota_saida;
-- ALTER TABLE nota_saida_item VALIDATE CONSTRAINT fk_nota_saida_item_produto;
-- ALTER TABLE meta_vendedor_mes VALIDATE CONSTRAINT fk_meta_vendedor_mes_vendedor;
-- ALTER TABLE meta_vendedor_categoria VALIDATE CONSTRAINT fk_meta_vendedor_categoria_mes;
-- ALTER TABLE tabela_preco_item VALIDATE CONSTRAINT fk_tabela_preco_item_tabela;
-- ALTER TABLE tabela_preco_item VALIDATE CONSTRAINT fk_tabela_preco_item_produto;
