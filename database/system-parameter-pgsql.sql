-- Tabela de parametros do sistema
CREATE TABLE IF NOT EXISTS system_parameter (
  id SERIAL PRIMARY KEY,
  system_unit_id INTEGER NULL REFERENCES system_unit(id),
  system_parameter VARCHAR(150) NOT NULL,
  system_type VARCHAR(20) NOT NULL,
  system_content TEXT NULL,
  "system_user" CHAR(1) NOT NULL DEFAULT 'N',
  CONSTRAINT chk_system_parameter_type
    CHECK (system_type IN ('DATA', 'NUMERO', 'LOGICO', 'CARACTER')),
  CONSTRAINT chk_system_parameter_user
    CHECK ("system_user" IN ('S', 'N'))
);

CREATE INDEX IF NOT EXISTS idx_system_parameter_name
  ON system_parameter (system_parameter);

CREATE INDEX IF NOT EXISTS idx_system_parameter_unit
  ON system_parameter (system_unit_id);

-- Garante unicidade do parametro global
CREATE UNIQUE INDEX IF NOT EXISTS uq_system_parameter_global
  ON system_parameter (LOWER(system_parameter))
  WHERE system_unit_id IS NULL;

-- Garante unicidade do parametro por unidade
CREATE UNIQUE INDEX IF NOT EXISTS uq_system_parameter_per_unit
  ON system_parameter (system_unit_id, LOWER(system_parameter))
  WHERE system_unit_id IS NOT NULL;

-- Cadastro das rotinas de parametros
DO $$
DECLARE
  v_admin_module_id INTEGER;
  v_program_list_id INTEGER;
  v_program_form_id INTEGER;
  v_next_order INTEGER;
BEGIN
  SELECT id
    INTO v_admin_module_id
    FROM system_module
   WHERE UPPER(name) = 'ADMINISTRACAO'
   ORDER BY id
   LIMIT 1;

  IF v_admin_module_id IS NULL THEN
    RAISE EXCEPTION 'Modulo Administracao nao encontrado em system_module';
  END IF;

  SELECT COALESCE(MAX("order"), 0) + 1
    INTO v_next_order
    FROM system_program
   WHERE system_module_id = v_admin_module_id;

  SELECT id
    INTO v_program_list_id
    FROM system_program
   WHERE controller = 'SystemParameterList'
   LIMIT 1;

  IF v_program_list_id IS NULL THEN
    INSERT INTO system_program (name, controller, icon, "order", system_module_id)
    VALUES ('Parametros do Sistema', 'SystemParameterList', 'an an-sliders', v_next_order, v_admin_module_id)
    RETURNING id INTO v_program_list_id;
  END IF;

  SELECT id
    INTO v_program_form_id
    FROM system_program
   WHERE controller = 'SystemParameterForm'
   LIMIT 1;

  IF v_program_form_id IS NULL THEN
    INSERT INTO system_program (name, controller, icon, "order", system_module_id)
    VALUES ('Cadastro de Parametro', 'SystemParameterForm', 'an an-sliders', v_next_order + 1, v_admin_module_id)
    RETURNING id INTO v_program_form_id;
  END IF;

  INSERT INTO system_group_program (system_group_id, system_program_id, actions)
  SELECT
    sg.id,
    v_program_list_id,
    '{"view":true,"insert":true,"update":true,"delete":true}'
  FROM system_group sg
  WHERE (UPPER(COALESCE(sg.role, '')) = 'ADMIN' OR UPPER(COALESCE(sg.name, '')) LIKE '%ADMIN%')
    AND NOT EXISTS (
      SELECT 1
        FROM system_group_program gp
       WHERE gp.system_group_id = sg.id
         AND gp.system_program_id = v_program_list_id
    );

  INSERT INTO system_group_program (system_group_id, system_program_id, actions)
  SELECT
    sg.id,
    v_program_form_id,
    '{"view":true,"insert":true,"update":true,"delete":true}'
  FROM system_group sg
  WHERE (UPPER(COALESCE(sg.role, '')) = 'ADMIN' OR UPPER(COALESCE(sg.name, '')) LIKE '%ADMIN%')
    AND NOT EXISTS (
      SELECT 1
        FROM system_group_program gp
       WHERE gp.system_group_id = sg.id
         AND gp.system_program_id = v_program_form_id
    );
END $$;
