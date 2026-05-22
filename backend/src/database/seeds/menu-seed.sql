-- ============================================================
-- SEED: system_module + system_program (associação de módulos)
-- Execute no banco "security" onde ficam as tabelas de sistema
-- ============================================================

-- 1. MÓDULOS
-- ============================================================
INSERT INTO system_module (name, icon, "order") VALUES
  ('CRM',           'po-icon-users',     1),
  ('Cadastros',     'po-icon-list',      2),
  ('Financeiro',    'po-icon-finance',   3),
  ('Faturamento',   'po-icon-document',  4),
  ('Administração', 'po-icon-settings',  99);

-- 2. PROGRAMAS: insere se não existir, associa ao módulo
-- ============================================================

-- ► CRM
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'CRM' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Dashboard',           'DashboardVendedor',      'po-icon-home',       1),
    ('Agenda de Visitas',   'MvcList',                'po-icon-calendar',   2),
    ('Clientes',            'ClienteList',            'po-icon-user',       3),
    ('Visão 360° Cliente',  'PosisaoClienteFormView', 'po-icon-eye',        4),
    ('Agenda Atendimento',  'AgendaAtendimentoList',  'po-icon-calendar',   5),
    ('Metas',               'MetaVendedorMesList',    'po-icon-chart-bar',  6),
    ('Negociações',         'NegociacaoList',         'po-icon-handshake',  7)
  ) AS t(name, controller, icon, ord)
  WHERE NOT EXISTS (
    SELECT 1 FROM system_program WHERE controller = t.controller
  );

  -- Atualiza os que já existem
  UPDATE system_program SET system_module_id = mod_id
  WHERE controller IN (
    'DashboardVendedor','MvcList','ClienteList','PosisaoClienteFormView',
    'AgendaAtendimentoList','MetaVendedorMesList','NegociacaoList'
  );
END $$;

-- ► CADASTROS
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'Cadastros' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Vendedores',        'VendedorList',      'po-icon-user-add',  1),
    ('Produtos',          'ProdutoList',       'po-icon-cart',      2),
    ('Tabelas de Preço',  'TabelaPrecoList',   'po-icon-dollar',    3),
    ('Categorias',        'CategoriaList',     'po-icon-list',      4),
    ('Estados de Pedido', 'PedidoEstadoList',  'po-icon-info',      5)
  ) AS t(name, controller, icon, ord)
  WHERE NOT EXISTS (
    SELECT 1 FROM system_program WHERE controller = t.controller
  );

  UPDATE system_program SET system_module_id = mod_id
  WHERE controller IN (
    'VendedorList','ProdutoList','TabelaPrecoList','CategoriaList','PedidoEstadoList'
  );
END $$;

-- ► FINANCEIRO
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'Financeiro' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Títulos a Receber', 'TituloReceberList', 'po-icon-finance',   1),
    ('Negociações',       'NegociacaoList',    'po-icon-handshake', 2)
  ) AS t(name, controller, icon, ord)
  WHERE NOT EXISTS (
    SELECT 1 FROM system_program WHERE controller = t.controller
  );

  UPDATE system_program SET system_module_id = mod_id
  WHERE controller IN ('TituloReceberList','NegociacaoList');
END $$;

-- ► FATURAMENTO
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'Faturamento' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Notas Fiscais', 'NotaFiscalList', 'po-icon-document', 1),
    ('Comodatos',     'ComodatoList',   'po-icon-box',      2)
  ) AS t(name, controller, icon, ord)
  WHERE NOT EXISTS (
    SELECT 1 FROM system_program WHERE controller = t.controller
  );

  UPDATE system_program SET system_module_id = mod_id
  WHERE controller IN ('NotaFiscalList','ComodatoList');
END $$;

-- ► ADMINISTRAÇÃO
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'Administração' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Usuários',         'SystemUserList',    'po-icon-user',    1),
    ('Perfis de Acesso', 'SystemGroupList',   'po-icon-users',   2),
    ('Unidades',         'SystemUnitList',    'po-icon-company', 3),
    ('Módulos',          'SystemModuleList',  'po-icon-vendas',  4),
    ('Rotinas',          'SystemProgramList', 'po-icon-xml',     5),
    ('Meu Perfil',       'SystemProfileForm', 'po-icon-user',    6)
  ) AS t(name, controller, icon, ord)
  WHERE NOT EXISTS (
    SELECT 1 FROM system_program WHERE controller = t.controller
  );

  UPDATE system_program SET system_module_id = mod_id
  WHERE controller IN (
    'SystemUserList','SystemGroupList','SystemUnitList',
    'SystemModuleList','SystemProgramList','SystemProfileForm'
  );
END $$;

-- 3. Verificação final
SELECT sm.name AS modulo, sm.order, COUNT(sp.id) AS qtd_programas
FROM system_module sm
LEFT JOIN system_program sp ON sp.system_module_id = sm.id
GROUP BY sm.id, sm.name, sm.order
ORDER BY sm.order;
