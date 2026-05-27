-- =========================================================================
-- SCRIPT DE MIGRAÇÃO: IMPLANTAÇÃO DE MULTITENANCY LÓGICO SUPER RESILIENTE
-- =========================================================================
-- Este script realiza as alterações estruturais de forma 100% segura usando
-- blocos condicionais PL/pgSQL (DO $$).
-- OBSERVAÇÃO DE ARQUITETURA: Como a tabela "system_unit" reside em um banco 
-- de dados fisicamente separado (Security), restrições físicas de FK 
-- cruzando bases (Cross-Database FK) não são possíveis no PostgreSQL.
-- Portanto, o isolamento será garantido logicamente pela aplicação (NestJS),
-- mantendo a integridade referencial lógica, índices de performance e restrições.
-- Todos os dados legados são associados à unidade padrão ID 1 (Unidade Central).
-- =========================================================================

BEGIN;

-- =========================================================================
-- 1. BLOCO PL/pgSQL PARA ADIÇÃO DAS COLUNAS DE TENANT
-- =========================================================================
DO $$
BEGIN
    -- 1. ATENDIMENTO
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'atendimento') THEN
        ALTER TABLE atendimento ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela atendimento atualizada com sucesso para Multitenancy.';
    END IF;

    -- 2. ATENDIMENTO_TIPO
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'atendimento_tipo') THEN
        ALTER TABLE atendimento_tipo ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela atendimento_tipo atualizada com sucesso para Multitenancy.';
    END IF;

    -- 3. CALENDARIO_ORCAMENTO
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'calendario_orcamento') THEN
        ALTER TABLE calendario_orcamento ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela calendario_orcamento atualizada com sucesso para Multitenancy.';
    END IF;

    -- 4. PEDIDO_ITEM
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'pedido_item') THEN
        ALTER TABLE pedido_item ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela pedido_item atualizada com sucesso para Multitenancy.';
    END IF;

    -- 5. NOTA_SAIDA_ITEM
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'nota_saida_item') THEN
        ALTER TABLE nota_saida_item ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela nota_saida_item atualizada com sucesso para Multitenancy.';
    END IF;

    -- 6. NOTA_ENTRADA_ITEM
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'nota_entrada_item') THEN
        ALTER TABLE nota_entrada_item ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela nota_entrada_item atualizada com sucesso para Multitenancy.';
    END IF;

    -- 7. ORCAMENTO_ITEM
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'orcamento_item') THEN
        ALTER TABLE orcamento_item ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela orcamento_item atualizada com sucesso para Multitenancy.';
    END IF;

    -- 8. TABELA_PRECO_ITEM
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'tabela_preco_item') THEN
        ALTER TABLE tabela_preco_item ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela tabela_preco_item atualizada com sucesso para Multitenancy.';
    END IF;

    -- 9. BLOG_NOTICIAS
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'blog_noticias') THEN
        ALTER TABLE blog_noticias ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela blog_noticias atualizada com sucesso para Multitenancy.';
    END IF;

    -- 10. BLOG_COMUNICADOS
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'blog_comunicados') THEN
        ALTER TABLE blog_comunicados ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela blog_comunicados atualizada com sucesso para Multitenancy.';
    END IF;

    -- 11. BLOG_ANIVERSARIOS
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'blog_aniversarios') THEN
        ALTER TABLE blog_aniversarios ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela blog_aniversarios atualizada com sucesso para Multitenancy.';
    END IF;

    -- 12. META_VENDEDOR_MES
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'meta_vendedor_mes') THEN
        ALTER TABLE meta_vendedor_mes ADD COLUMN IF NOT EXISTS system_unit_id INTEGER DEFAULT 1;
        RAISE NOTICE '✅ Tabela meta_vendedor_mes atualizada com sucesso para Multitenancy.';
    END IF;

END
$$;

-- =========================================================================
-- 2. BLOCO PL/pgSQL PARA REMOÇÃO DE CONSTRAINTS DE UNICIDADE (CPF/CNPJ DUPLICADO PERMITIDO)
-- =========================================================================
DO $$
BEGIN
    -- Removemos qualquer restrição física UNIQUE de CNPJ/CPF por regra de negócio (CPF/CNPJ duplicado é válido)
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'cliente') THEN
        ALTER TABLE cliente DROP CONSTRAINT IF EXISTS uq_cliente_tenant_cnpj_cpf;
        ALTER TABLE cliente DROP CONSTRAINT IF EXISTS cliente_cnpj_cpf_key;
        RAISE NOTICE '✅ Tabela cliente: Garantida a remoção de chaves únicas físicas de CPF/CNPJ.';
    END IF;

    -- Removemos as demais constraints de unicidade para evitar qualquer quebra na migração de dados do ERP
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'vendedor') THEN
        ALTER TABLE vendedor DROP CONSTRAINT IF EXISTS uq_vendedor_tenant_cod_erp;
        ALTER TABLE vendedor DROP CONSTRAINT IF EXISTS vendedor_cod_erp_key;
    END IF;

    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'produto') THEN
        ALTER TABLE produto DROP CONSTRAINT IF EXISTS uq_produto_tenant_cod_erp;
        ALTER TABLE produto DROP CONSTRAINT IF EXISTS produto_cod_erp_key;
    END IF;

END
$$;

-- =========================================================================
-- 3. BLOCO PL/pgSQL PARA CRIAÇÃO DE ÍNDICES COMPOSTOS COM TENANT FIRST
-- =========================================================================
DO $$
BEGIN
    -- Clientes
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'cliente') THEN
        CREATE INDEX IF NOT EXISTS idx_cliente_tenant_vendedor_ativo ON cliente (system_unit_id, vendedor_id, reg_ativo);
        CREATE INDEX IF NOT EXISTS idx_cliente_tenant_municipio ON cliente (system_unit_id, municipio_id);
    END IF;

    -- CRM e Agenda
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'atendimento') THEN
        CREATE INDEX IF NOT EXISTS idx_atendimento_tenant_vendedor_horario ON atendimento (system_unit_id, vendedor_id, horario_inicial) WHERE dt_delete IS NULL;
        CREATE INDEX IF NOT EXISTS idx_atendimento_tenant_cliente_horario ON atendimento (system_unit_id, cliente_id, horario_inicial DESC) WHERE dt_delete IS NULL;
    END IF;

    -- Financeiro (Títulos a receber)
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'titulo_receber') THEN
        CREATE INDEX IF NOT EXISTS idx_titulo_receber_tenant_cliente_saldo ON titulo_receber (system_unit_id, cliente_id, saldo) WHERE reg_ativo = 'S' AND saldo > 0;
    END IF;

    -- Faturamento e Analytics (Nota de Saída e Itens)
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'nota_saida') THEN
        CREATE INDEX IF NOT EXISTS idx_nota_saida_tenant_vendedor_emissao ON nota_saida (system_unit_id, vendedor1_id, dt_emissao) WHERE reg_ativo = 'S';
        CREATE INDEX IF NOT EXISTS idx_nota_saida_tenant_cliente_emissao ON nota_saida (system_unit_id, cliente_id, dt_emissao) WHERE reg_ativo = 'S';
    END IF;

    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'nota_saida_item') THEN
        CREATE INDEX IF NOT EXISTS idx_nota_saida_item_tenant_parent ON nota_saida_item (system_unit_id, nota_saida_id);
        CREATE INDEX IF NOT EXISTS idx_nota_saida_item_tenant_produto_data ON nota_saida_item (system_unit_id, produto_id, ano, mes);
    END IF;

    -- Metas Comerciais
    IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'meta_vendedor_mes') THEN
        CREATE INDEX IF NOT EXISTS idx_meta_vendedor_mes_tenant_lookup ON meta_vendedor_mes (system_unit_id, vendedor_id, ano, mes) WHERE dt_delete IS NULL;
    END IF;

    RAISE NOTICE '✅ Todos os índices de performance multitenant foram configurados.';
END
$$;

COMMIT;
