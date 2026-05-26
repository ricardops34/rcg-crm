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

-- 'po-icon-settings' -> 'an an-gear-six' (Módulo Administração)
UPDATE system_module 
SET icon = 'an an-gear-six' 
WHERE name = 'Administração';


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

COMMIT;
