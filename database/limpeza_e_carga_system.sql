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
(3, 'Administração', 'po-icon-settings', 3);

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
(11, 'Manutenção de Menus', 'SystemMenuEditor', 3, 7, 'po-icon-menu', NULL);

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
(1, 11, '{"view":true,"insert":true,"update":true,"delete":true}');

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
