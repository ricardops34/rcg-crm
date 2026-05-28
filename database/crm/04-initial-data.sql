-- =============================================================================
-- SCRIPT DE LIMPEZA E CARGA CONTROLADA DAS TABELAS DE ACESSO DO SISTEMA
-- ECOSSISTEMA: RCG CRM (NestJS / Angular / PO-UI)
-- COMPATIBILIDADE: PostgreSQL (public schema)
-- =============================================================================

BEGIN;

-- 1 e 2 e 3. DESVINCULO E LIMPEZA SEGURA E CONDICIONAL DAS TABELAS system_*
-- Executa de forma dinâmica verificando a existência de cada coluna e tabela no banco real
DO $$
BEGIN
    -- Desvincula a frontpage de system_users apenas se a coluna frontpage_id existir
    IF EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'system_users' AND column_name = 'frontpage_id') THEN
        UPDATE system_users SET frontpage_id = NULL;
    END IF;

    -- Limpa as tabelas de associação se existirem
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_user_group') THEN
        DELETE FROM system_user_group;
    END IF;
    
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_user_program') THEN
        DELETE FROM system_user_program;
    END IF;
    
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_group_program') THEN
        DELETE FROM system_group_program;
    END IF;
    
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_preference') THEN
        DELETE FROM system_preference;
    END IF;
    
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_change_log') THEN
        DELETE FROM system_change_log;
    END IF;

    -- Limpa as tabelas estruturais se existirem
    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_group') THEN
        DELETE FROM system_group;
    END IF;

    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_program') THEN
        DELETE FROM system_program;
    END IF;

    IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'system_module') THEN
        DELETE FROM system_module;
    END IF;
END $$;


-- 4. RESETAR SEQUENCES DAS CHAVES PRIMÁRIAS AUTOINCREMENTAIS (POSTGRESQL)
-- Garante que novos cadastros efetuados pelas telas CRUD não deem erro de chave duplicada
ALTER SEQUENCE IF EXISTS system_module_id_seq RESTART WITH 1;
ALTER SEQUENCE IF EXISTS system_program_id_seq RESTART WITH 1;
ALTER SEQUENCE IF EXISTS system_group_id_seq RESTART WITH 1;
ALTER SEQUENCE IF EXISTS system_group_program_id_seq RESTART WITH 1;
ALTER SEQUENCE IF EXISTS system_user_group_id_seq RESTART WITH 1;
ALTER SEQUENCE IF EXISTS system_user_program_id_seq RESTART WITH 1;

-- 5. CARGA DE MÓDULOS (system_module)
-- Agrupamento principal das rotinas no menu lateral
INSERT INTO system_module (id, name, icon, "order") VALUES
(1, 'Atendimento', 'po-icon-chat', 1),
(2, 'Gerencial', 'po-icon-finance', 2),
(3, 'Administração', 'po-icon-settings', 3),
(4, 'Configurações', 'po-icon-settings', 4);

-- Reajuste da sequence para system_module de forma segura contra retornos NULL
SELECT setval(seq, COALESCE((SELECT MAX(id)+1 FROM system_module), 1), false)
FROM (SELECT pg_get_serial_sequence('system_module', 'id') AS seq) t
WHERE seq IS NOT NULL;

-- 6. CARGA DE PROGRAMAS/ROTINAS (system_program)
-- Cadastra apenas as telas e caminhos acordados no escopo do CRM moderno
INSERT INTO system_program (id, name, controller, system_module_id, "order", icon, actions) VALUES
-- MÓDULO: Atendimento (ID 1)
(1, 'Dashboard Vendedor', 'DashboardVendedor', 1, 1, 'po-icon-chart-area', NULL),
(2, 'MCV', 'MvcList', 1, 2, 'po-icon-list', NULL),

-- MÓDULO: Gerencial (ID 2)
(3, 'Cadastro de Vendedores', 'VendedorList', 2, 1, 'po-icon-user', NULL),
(4, 'Cadastro de Objetivos', 'MetaList', 2, 2, 'po-icon-target', NULL),

-- MÓDULO: Administração (ID 3)
(5, 'Dashboard', 'Dashboard', 3, 1, 'po-icon-chart-area', NULL),
(6, 'Cadastro de Usuários', 'SystemUserList', 3, 2, 'po-icon-user-add', NULL),
(7, 'Cadastro de Perfis', 'SystemGroupList', 3, 3, 'po-icon-lock', NULL),
(8, 'Cadastro de Unidades', 'SystemUnitList', 3, 4, 'po-icon-company', NULL),
(9, 'Cadastro de Módulos', 'SystemModuleList', 3, 5, 'po-icon-layers', NULL),
(10, 'Cadastro de Rotinas', 'SystemProgramList', 3, 6, 'po-icon-grid', NULL),
(11, 'Manutenção de Menus', 'SystemMenuEditor', 3, 7, 'po-icon-menu', NULL),

-- MÓDULO: Configurações (ID 4)
(12, 'Parâmetros do Sistema', 'SystemParameterList', 4, 1, 'po-icon-sliders', NULL),
(13, 'Configurações do Sistema', 'SystemSettings', 4, 2, 'an an-gear-six', NULL);

-- Reajuste da sequence para system_program de forma segura contra retornos NULL
SELECT setval(seq, COALESCE((SELECT MAX(id)+1 FROM system_program), 1), false)
FROM (SELECT pg_get_serial_sequence('system_program', 'id') AS seq) t
WHERE seq IS NOT NULL;

-- 7. CARGA DE PERFIS DE ACESSO (system_group)
INSERT INTO system_group (id, name, uuid, role) VALUES
(1, 'Administrador', 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11', 'ADMIN'),
(2, 'Gerente/Supervisor', 'b0eebc99-9c0b-4ef8-bb6d-6bb9bd380a22', 'GERENTE'),
(3, 'Vendedor', 'c0eebc99-9c0b-4ef8-bb6d-6bb9bd380a33', 'VENDEDOR');

-- Reajuste da sequence para system_group de forma segura contra retornos NULL
SELECT setval(seq, COALESCE((SELECT MAX(id)+1 FROM system_group), 1), false)
FROM (SELECT pg_get_serial_sequence('system_group', 'id') AS seq) t
WHERE seq IS NOT NULL;

-- 8. ASSOCIAÇÃO PERFIL x PROGRAMA (system_group_program)
-- Perfil Administrador (ID 1) - Acesso total a todas as rotinas (1 a 11)
INSERT INTO system_group_program (system_group_id, system_program_id, actions) VALUES
(1, 1, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 2, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 3, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 4, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 5, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 6, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 7, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 8, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 9, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 10, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 11, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 12, '{"view":true,"insert":true,"update":true,"delete":true}'),
(1, 13, '{"view":true,"insert":true,"update":true,"delete":true}');

-- Perfil Gerente/Supervisor (ID 2) - Acesso ao dashboard e rotinas comerciais
INSERT INTO system_group_program (system_group_id, system_program_id, actions) VALUES
(2, 1, '{"view":true,"insert":true,"update":true,"delete":true}'),
(2, 2, '{"view":true,"insert":true,"update":true,"delete":true}'),
(2, 3, '{"view":true,"insert":true,"update":true,"delete":true}'),
(2, 4, '{"view":true,"insert":true,"update":true,"delete":true}'),
(2, 5, '{"view":true,"insert":true,"update":true,"delete":true}');

-- Perfil Vendedor (ID 3) - Acesso apenas às suas rotinas de atendimento
INSERT INTO system_group_program (system_group_id, system_program_id, actions) VALUES
(3, 1, '{"view":true,"insert":true,"update":true,"delete":true}'),
(3, 2, '{"view":true,"insert":true,"update":true,"delete":true}');

-- Reajuste da sequence para system_group_program de forma segura contra retornos NULL
SELECT setval(seq, COALESCE((SELECT MAX(id)+1 FROM system_group_program), 1), false)
FROM (SELECT pg_get_serial_sequence('system_group_program', 'id') AS seq) t
WHERE seq IS NOT NULL;

-- 9. ASSOCIAÇÃO DINÂMICA DE USUÁRIOS A SEUS RESPECTIVOS PERFIS
-- O usuário 'admin' é associado ao grupo Administrador (ID 1)
-- Todos os outros usuários cadastrados são associados ao grupo Vendedor (ID 3)
INSERT INTO system_user_group (id, system_user_id, system_group_id)
SELECT 1, id, 1 FROM system_users WHERE login = 'admin';

INSERT INTO system_user_group (id, system_user_id, system_group_id)
SELECT 
    row_number() OVER () + 1 AS id,
    id AS system_user_id,
    3 AS system_group_id
FROM system_users 
WHERE login != 'admin';

-- Reajuste da sequence para system_user_group de forma segura contra retornos NULL
SELECT setval(seq, COALESCE((SELECT MAX(id)+1 FROM system_user_group), 1), false)
FROM (SELECT pg_get_serial_sequence('system_user_group', 'id') AS seq) t
WHERE seq IS NOT NULL;

COMMIT;
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
-- 2. LOOPS DINÂMICOS DE UPDATE (ATUALIZA APENAS ONDE A TENANT FOR NULA)
-- =========================================================================
-- Ao invés de usar uma lista estática, ele pesquisa todas as tabelas reais 
-- no banco que possuem a coluna 'system_unit_id' e faz o update dos orfãos.
DO $$
DECLARE
    t_nome TEXT;
BEGIN
    FOR t_nome IN 
        SELECT table_name 
        FROM information_schema.columns 
        WHERE table_schema = 'public' 
          AND column_name = 'system_unit_id'
    LOOP
        EXECUTE format('UPDATE public.%I SET system_unit_id = 1 WHERE system_unit_id IS NULL', t_nome);
        RAISE NOTICE '✅ Tabela % ajustada (registros órfãos de tenant foram atrelados à unidade 1).', t_nome;
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
-- =============================================================================
-- SCRIPT DE ATUALIZAÇÃO E PADRONIZAÇÃO DE ÍCONES DAS TABELAS DE ACESSO DO SISTEMA
-- BIBLIOTECA: Animalia Icons (an an-*) - Equivalente ao PO-UI v21+
-- ORIGEM: c:\Ricardo\rcgcrm\po-ui\doc\llms-generated\icons-animalia.md
-- COMPATIBILIDADE: PostgreSQL (public schema)
-- =============================================================================

BEGIN;

-- =============================================================================
-- 1. ATUALIZAÇÃO DE MÓDULOS (system_module)
-- Substitui ícones legados po-icon-* pelos equivalentes oficiais da biblioteca Animalia
-- =============================================================================

-- 'po-icon-chat' -> 'an an-chat-circle' (Módulo Atendimento)
UPDATE system_module 
SET icon = 'an an-chat-circle' 
WHERE name = 'Atendimento';

-- 'po-icon-finance' -> 'an an-currency-dollar-simple' (Módulo Gerencial)
UPDATE system_module 
SET icon = 'an an-currency-dollar-simple' 
WHERE name = 'Gerencial';

-- 'po-icon-settings' -> 'an an-gear-six' (Módulos Administração e Configurações)
UPDATE system_module 
SET icon = 'an an-gear-six' 
WHERE name IN ('Administração', 'Configurações');


-- =============================================================================
-- 2. ATUALIZAÇÃO DE ROTINAS / PROGRAMAS (system_program)
-- Substitui ícones legados po-icon-* pelos equivalentes oficiais da biblioteca Animalia
-- =============================================================================

-- 'po-icon-chart-area' -> 'an an-chart-area' (Dashboard Vendedor)
UPDATE system_program 
SET icon = 'an an-chart-area' 
WHERE controller = 'DashboardVendedor';

-- 'po-icon-list' -> 'an an-list' (MCV)
UPDATE system_program 
SET icon = 'an an-list' 
WHERE controller = 'MvcList';

-- 'po-icon-user' -> 'an an-user' (Cadastro de Vendedores)
UPDATE system_program 
SET icon = 'an an-user' 
WHERE controller = 'VendedorList';

-- 'po-icon-target' -> 'an an-target' (Cadastro de Objetivos / Metas)
UPDATE system_program 
SET icon = 'an an-target' 
WHERE controller = 'MetaList';

-- 'po-icon-chart-area' -> 'an an-chart-area' (Dashboard Admin)
UPDATE system_program 
SET icon = 'an an-chart-area' 
WHERE controller = 'Dashboard';

-- 'po-icon-user-add' -> 'an an-user-plus' (Cadastro de Usuários)
UPDATE system_program 
SET icon = 'an an-user-plus' 
WHERE controller = 'SystemUserList';

-- 'po-icon-lock' -> 'an an-lock' (Cadastro de Perfis)
UPDATE system_program 
SET icon = 'an an-lock' 
WHERE controller = 'SystemGroupList';

-- 'po-icon-company' -> 'an an-buildings' (Cadastro de Unidades)
UPDATE system_program 
SET icon = 'an an-buildings' 
WHERE controller = 'SystemUnitList';

-- 'po-icon-layers' -> 'an an-layers' (Cadastro de Módulos)
UPDATE system_program 
SET icon = 'an an-layers' 
WHERE controller = 'SystemModuleList';

-- 'po-icon-grid' -> 'an an-grid-four' (Cadastro de Rotinas)
UPDATE system_program 
SET icon = 'an an-grid-four' 
WHERE controller = 'SystemProgramList';

-- 'po-icon-menu' -> 'an an-sidebar-simple' (Manutenção de Menus)
UPDATE system_program 
SET icon = 'an an-sidebar-simple' 
WHERE controller = 'SystemMenuEditor';

-- 'po-icon-sliders' -> 'an an-sliders' (Parâmetros do Sistema)
UPDATE system_program 
SET icon = 'an an-sliders' 
WHERE controller = 'SystemParameterList';

-- 10. CARGA DE PARÂMETROS PADRÃO DO SISTEMA (system_parameter)
INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_smtp_host', 'CARACTER', '', 'S', 'Servidor SMTP de envio de e-mails'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_smtp_host' AND system_unit_id IS NULL);

INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_smtp_port', 'NUMERO', '587', 'S', 'Porta SMTP de envio de e-mails'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_smtp_port' AND system_unit_id IS NULL);

INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_smtp_user', 'CARACTER', '', 'S', 'Usuário de autenticação do servidor SMTP'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_smtp_user' AND system_unit_id IS NULL);

INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_smtp_pass', 'CARACTER', '', 'S', 'Senha de autenticação do servidor SMTP'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_smtp_pass' AND system_unit_id IS NULL);

INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_smtp_from', 'CARACTER', 'nao-responda@rcg.com.br', 'S', 'E-mail remetente padrão do sistema'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_smtp_from' AND system_unit_id IS NULL);

INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_smtp_secure', 'CARACTER', 'TLS', 'S', 'Tipo de segurança/criptografia SMTP (SSL/TLS/NONE)'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_smtp_secure' AND system_unit_id IS NULL);

INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_system_name', 'CARACTER', 'RCG CRM', 'S', 'Nome customizado do sistema'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_system_name' AND system_unit_id IS NULL);

INSERT INTO system_parameter (system_unit_id, parameter, type, content, system, description)
SELECT NULL, 'sys_query_limit', 'NUMERO', '20', 'S', 'Limite de linhas exibidas em buscas padrão'
WHERE NOT EXISTS (SELECT 1 FROM system_parameter WHERE LOWER(parameter) = 'sys_query_limit' AND system_unit_id IS NULL);

COMMIT;
