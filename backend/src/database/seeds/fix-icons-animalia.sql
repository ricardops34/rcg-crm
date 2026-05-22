-- ============================================================
-- FIX: Migra ícones po-icon-* → Animalia Icons (an an-*)
-- Execute no banco "security"
-- PO-UI v21+ usa a biblioteca Animalia Icons
-- ============================================================

-- Módulos
UPDATE system_module SET icon = 'an an-users'                   WHERE name = 'CRM';
UPDATE system_module SET icon = 'an an-list-dashes'             WHERE name = 'Cadastros';
UPDATE system_module SET icon = 'an an-currency-dollar-simple'  WHERE name = 'Financeiro';
UPDATE system_module SET icon = 'an an-file-text'               WHERE name = 'Faturamento';
UPDATE system_module SET icon = 'an an-gear-six'                WHERE name = 'Administração';

-- Programas CRM
UPDATE system_program SET icon = 'an an-house'           WHERE controller = 'DashboardVendedor';
UPDATE system_program SET icon = 'an an-calendar-blank'  WHERE controller = 'MvcList';
UPDATE system_program SET icon = 'an an-user'            WHERE controller = 'ClienteList';
UPDATE system_program SET icon = 'an an-eye'             WHERE controller = 'PosisaoClienteFormView';
UPDATE system_program SET icon = 'an an-calendar-dots'   WHERE controller = 'AgendaAtendimentoList';
UPDATE system_program SET icon = 'an an-chart-bar'       WHERE controller = 'MetaList';
UPDATE system_program SET icon = 'an an-handshake'       WHERE controller = 'NegociacaoList';

-- Programas Cadastros
UPDATE system_program SET icon = 'an an-user-plus'                WHERE controller = 'VendedorList';
UPDATE system_program SET icon = 'an an-shopping-cart'            WHERE controller = 'ProdutoList';
UPDATE system_program SET icon = 'an an-currency-dollar-simple'   WHERE controller = 'TabelaPrecoList';
UPDATE system_program SET icon = 'an an-tag'                      WHERE controller = 'CategoriaList';
UPDATE system_program SET icon = 'an an-info'                     WHERE controller = 'PedidoEstadoList';

-- Programas Financeiro
UPDATE system_program SET icon = 'an an-receipt'    WHERE controller = 'TituloReceberList';

-- Programas Faturamento
UPDATE system_program SET icon = 'an an-file-text'  WHERE controller = 'NotaSaidaList';
UPDATE system_program SET icon = 'an an-package'    WHERE controller = 'ComodatoList';

-- Programas Administração
UPDATE system_program SET icon = 'an an-user'              WHERE controller = 'SystemUserList';
UPDATE system_program SET icon = 'an an-users'             WHERE controller = 'SystemGroupList';
UPDATE system_program SET icon = 'an an-buildings'         WHERE controller = 'SystemUnitList';
UPDATE system_program SET icon = 'an an-sidebar-simple'    WHERE controller = 'SystemModuleList';
UPDATE system_program SET icon = 'an an-terminal-window'   WHERE controller = 'SystemProgramList';
UPDATE system_program SET icon = 'an an-user-circle'       WHERE controller = 'SystemProfileForm';

-- Verificação
SELECT sm.name AS modulo, sm.icon AS icone_modulo, sp.name AS rotina, sp.icon AS icone_rotina
FROM system_module sm
JOIN system_program sp ON sp.system_module_id = sm.id
ORDER BY sm.order, sp.order;
