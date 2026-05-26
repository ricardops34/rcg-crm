-- ============================================================
-- SEED ADITIVO: modulo Configurador + programa Construtor Visual de Telas
-- Execute no banco "security"
-- ============================================================

INSERT INTO system_module (name, icon, "order")
SELECT 'Configurador', 'an an-sidebar-simple', 80
WHERE NOT EXISTS (
  SELECT 1
  FROM system_module
  WHERE name = 'Configurador'
);

UPDATE system_module
SET icon = 'an an-sidebar-simple',
    "order" = 80
WHERE name = 'Configurador';

DO $$
DECLARE mod_id INTEGER := (SELECT id FROM system_module WHERE name = 'Configurador' LIMIT 1);
BEGIN
  INSERT INTO system_program (name, controller, icon, "order", system_module_id)
  SELECT 'Construtor Visual de Telas', 'SystemScreenConfigurator', 'an an-terminal-window', 1, mod_id
  WHERE NOT EXISTS (
    SELECT 1
    FROM system_program
    WHERE controller = 'SystemScreenConfigurator'
  );

  UPDATE system_program
  SET name = 'Construtor Visual de Telas',
      icon = 'an an-terminal-window',
      "order" = 1,
      system_module_id = mod_id
  WHERE controller = 'SystemScreenConfigurator';
END $$;

SELECT
  sm.name AS modulo,
  sm.icon AS modulo_icone,
  sp.name AS programa,
  sp.controller,
  sp.icon AS programa_icone
FROM system_module sm
JOIN system_program sp ON sp.system_module_id = sm.id
WHERE sm.name = 'Configurador'
ORDER BY sp."order";
