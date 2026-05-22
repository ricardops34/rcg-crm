-- ============================================================
-- SEED: system_module + system_program (associação de módulos)
-- Execute no banco "security" onde ficam as tabelas de sistema
-- ============================================================

-- 1. MÓDULOS
-- ============================================================
INSERT INTO system_module (name, icon, "order") VALUES
  ('CRM',           'an an-users',       1),
  ('Cadastros',     'an an-list-dashes', 2),
  ('Financeiro',    'an an-currency-dollar-simple', 3),
  ('Faturamento',   'an an-file-text',   4),
  ('Administração', 'an an-gear-six',    99);

-- 2. PROGRAMAS: insere se não existir, associa ao módulo
-- ============================================================

-- ► CRM
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'CRM' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Dashboard',           'DashboardVendedor',      'an an-house',             1),
    ('Agenda de Visitas',   'MvcList',                'an an-calendar-blank',    2),
    ('Clientes',            'ClienteList',            'an an-user',              3),
    ('Visão 360° Cliente',  'PosisaoClienteFormView', 'an an-eye',               4),
    ('Agenda Atendimento',  'AgendaAtendimentoList',  'an an-calendar-dots',     5),
    ('Metas',               'MetaList',               'an an-chart-bar',         6),
    ('Negociações',         'NegociacaoList',         'an an-handshake',         7)
  ) AS t(name, controller, icon, ord)
  WHERE NOT EXISTS (
    SELECT 1 FROM system_program WHERE controller = t.controller
  );

  -- Atualiza os que já existem
  UPDATE system_program SET system_module_id = mod_id
  WHERE controller IN (
    'DashboardVendedor','MvcList','ClienteList','PosisaoClienteFormView',
    'AgendaAtendimentoList','MetaList','NegociacaoList'
  );
END $$;

-- ► CADASTROS
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'Cadastros' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Vendedores',        'VendedorList',      'an an-user-plus',         1),
    ('Produtos',          'ProdutoList',       'an an-shopping-cart',     2),
    ('Tabelas de Preço',  'TabelaPrecoList',   'an an-currency-dollar-simple', 3),
    ('Categorias',        'CategoriaList',     'an an-tag',               4),
    ('Estados de Pedido', 'PedidoEstadoList',  'an an-info',              5)
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
    ('Títulos a Receber', 'TituloReceberList', 'an an-receipt',           1),
    ('Negociações',       'NegociacaoList',    'an an-handshake',         2)
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
    ('Notas Fiscais', 'NotaSaidaList', 'an an-file-text',         1),
    ('Comodatos',     'ComodatoList',  'an an-package',           2)
  ) AS t(name, controller, icon, ord)
  WHERE NOT EXISTS (
    SELECT 1 FROM system_program WHERE controller = t.controller
  );

  UPDATE system_program SET system_module_id = mod_id
  WHERE controller IN ('NotaSaidaList','ComodatoList');
END $$;

-- ► ADMINISTRAÇÃO
DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'Administração' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT name, controller, icon, ord, mod_id
  FROM (VALUES
    ('Usuários',         'SystemUserList',    'an an-user',              1),
    ('Perfis de Acesso', 'SystemGroupList',   'an an-users',             2),
    ('Unidades',         'SystemUnitList',    'an an-buildings',         3),
    ('Módulos',          'SystemModuleList',  'an an-sidebar-simple',    4),
    ('Rotinas',          'SystemProgramList', 'an an-terminal-window',   5),
    ('Meu Perfil',       'SystemProfileForm', 'an an-user-circle',       6)
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
