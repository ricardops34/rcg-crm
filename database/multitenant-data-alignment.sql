-- =========================================================================
-- SCRIPT UTILITÁRIO: ALINHAMENTO DE DADOS E REFRESH DE MATERIALIZED VIEWS
-- =========================================================================
-- Este script realiza o preenchimento preventivo de dados históricos legados
-- para a unidade '1' em todas as tabelas comerciais físicas ativas, e
-- atualiza o cache físico de todas as Materialized Views analíticas.
-- Projetado com blocos PL/pgSQL dinâmicos (DO $$) tolerantes a tabelas inexistentes.
-- =========================================================================

BEGIN;

-- =========================================================================
-- 1. REMOVE QUALQUER RESTRIÇÃO E ÍNDICE ÚNICO ANTERIOR QUE ESTEJA ATIVO
-- =========================================================================
ALTER TABLE public.cliente DROP CONSTRAINT IF EXISTS uq_cliente_tenant_cnpj_cpf CASCADE;
DROP INDEX IF EXISTS public.uq_cliente_tenant_cnpj_cpf CASCADE;
DROP INDEX IF EXISTS uq_cliente_tenant_cnpj_cpf CASCADE;

ALTER TABLE public.vendedor DROP CONSTRAINT IF EXISTS uq_vendedor_tenant_cod_erp CASCADE;
DROP INDEX IF EXISTS public.uq_vendedor_tenant_cod_erp CASCADE;
DROP INDEX IF EXISTS uq_vendedor_tenant_cod_erp CASCADE;

ALTER TABLE public.produto DROP CONSTRAINT IF EXISTS uq_produto_tenant_cod_erp CASCADE;
DROP INDEX IF EXISTS public.uq_produto_tenant_cod_erp CASCADE;
DROP INDEX IF EXISTS uq_produto_tenant_cod_erp CASCADE;

-- =========================================================================
-- 2. LOOPS CONDICIONAIS DE UPDATE (SÓ ATUALIZA AS TABELAS QUE EXISTIREM)
-- =========================================================================
DO $$
DECLARE
    tabelas TEXT[] := ARRAY[
        'cliente', 'vendedor', 'supervisor', 'transportadora', 'titulo_receber', 
        'nota_saida', 'pedido', 'produto', 'estoque', 'armazem', 
        'condicao_pagamento', 'tabela_preco', 'tipo_entrada_saida', 'empresa', 
        'filial', 'categoria', 'atendimento', 'atendimento_tipo', 'pedido_item', 
        'nota_saida_item', 'orcamento_item', 'tabela_preco_item', 'meta_vendedor_mes'
    ];
    t_nome TEXT;
BEGIN
    FOREACH t_nome IN ARRAY tabelas LOOP
        IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = t_nome) THEN
            EXECUTE format('UPDATE public.%I SET system_unit_id = 1', t_nome);
            RAISE NOTICE '✅ Tabela % atualizada com system_unit_id = 1.', t_nome;
        END IF;
    END LOOP;
END
$$;

COMMIT;

-- =========================================================================
-- 3. REFRESH INTELIGENTE DAS VIEWS MATERIALIZADAS EXISTENTES
-- =========================================================================
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'mvc' AND relkind = 'm') THEN
        REFRESH MATERIALIZED VIEW public.mvc;
        RAISE NOTICE '✅ Materialized View mvc atualizada.';
    END IF;

    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'view_vendedor_venda_mes' AND relkind = 'm') THEN
        REFRESH MATERIALIZED VIEW public.view_vendedor_venda_mes;
        RAISE NOTICE '✅ Materialized View view_vendedor_venda_mes atualizada.';
    END IF;

    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'pivot_venda_mes_cliente' AND relkind = 'm') THEN
        REFRESH MATERIALIZED VIEW public.pivot_venda_mes_cliente;
        RAISE NOTICE '✅ Materialized View pivot_venda_mes_cliente atualizada.';
    END IF;

    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'view_total_catogoria_mes' AND relkind = 'm') THEN
        REFRESH MATERIALIZED VIEW public.view_total_catogoria_mes;
        RAISE NOTICE '✅ Materialized View view_total_catogoria_mes atualizada.';
    END IF;
END
$$;
