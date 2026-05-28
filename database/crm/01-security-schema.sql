-- =========================================================================
-- SCRIPT DE MIGRAÇÃO: BASE DE SEGURANÇA (rcg_security) E PARAMETROS
-- =========================================================================

BEGIN;

CREATE TABLE IF NOT EXISTS public.system_unit (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    connection_name VARCHAR(100),
    logo TEXT,
    favicon TEXT,
    limite_disco_mb INTEGER DEFAULT 1000
);

CREATE TABLE IF NOT EXISTS public.system_preference (
    id VARCHAR(255) PRIMARY KEY,
    preference TEXT
);

CREATE TABLE IF NOT EXISTS public.system_module (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(100),
    "order" INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS public.system_program (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    controller VARCHAR(100),
    system_module_id INTEGER REFERENCES public.system_module(id),
    "order" INTEGER DEFAULT 0,
    icon VARCHAR(100),
    actions TEXT
);

CREATE TABLE IF NOT EXISTS public.system_users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    login VARCHAR(100),
    password VARCHAR(100),
    email VARCHAR(100),
    frontpage_id INTEGER REFERENCES public.system_program(id),
    system_unit_id INTEGER REFERENCES public.system_unit(id),
    active CHAR(1),
    accepted_term_policy CHAR(1),
    accepted_term_policy_at TEXT,
    two_factor_enabled CHAR(1),
    two_factor_type VARCHAR(100),
    two_factor_secret VARCHAR(255),
    current_session_id VARCHAR(255),
    avatar TEXT
);

CREATE TABLE IF NOT EXISTS public.system_group (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    uuid VARCHAR(36),
    role VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS public.system_user_group (
    id SERIAL PRIMARY KEY,
    system_user_id INTEGER REFERENCES public.system_users(id),
    system_group_id INTEGER REFERENCES public.system_group(id)
);

CREATE TABLE IF NOT EXISTS public.system_user_program (
    id SERIAL PRIMARY KEY,
    system_user_id INTEGER REFERENCES public.system_users(id),
    system_program_id INTEGER REFERENCES public.system_program(id)
);

CREATE TABLE IF NOT EXISTS public.system_user_unit (
    id SERIAL PRIMARY KEY,
    system_user_id INTEGER REFERENCES public.system_users(id),
    system_unit_id INTEGER REFERENCES public.system_unit(id)
);

CREATE TABLE IF NOT EXISTS public.system_group_program (
    id SERIAL PRIMARY KEY,
    system_group_id INTEGER REFERENCES public.system_group(id),
    system_program_id INTEGER REFERENCES public.system_program(id),
    actions TEXT
);

CREATE TABLE IF NOT EXISTS public.system_change_log (
    id SERIAL PRIMARY KEY,
    logdate TIMESTAMP,
    login VARCHAR(100),
    tablename VARCHAR(100),
    primarykey VARCHAR(100),
    pkvalue VARCHAR(100),
    operation VARCHAR(20),
    columnname VARCHAR(100),
    oldvalue TEXT,
    newvalue TEXT,
    access_ip VARCHAR(45),
    transaction_id VARCHAR(100)
);

-- Migrado do system-parameter-pgsql.sql
CREATE TABLE IF NOT EXISTS public.system_parameter (
    id SERIAL PRIMARY KEY,
    system_unit_id INTEGER NULL REFERENCES public.system_unit(id),
    system_parameter VARCHAR(150) NOT NULL,
    system_type VARCHAR(20) NOT NULL,
    system_content TEXT NULL,
    system_system CHAR(1) NOT NULL DEFAULT 'N',
    CONSTRAINT chk_system_parameter_type
        CHECK (system_type IN ('DATA', 'NUMERO', 'LOGICO', 'CARACTER')),
    CONSTRAINT chk_system_parameter_user
        CHECK (system_system IN ('S', 'N'))
);

CREATE INDEX IF NOT EXISTS idx_system_parameter_name ON public.system_parameter (system_parameter);
CREATE INDEX IF NOT EXISTS idx_system_parameter_unit ON public.system_parameter (system_unit_id);
CREATE UNIQUE INDEX IF NOT EXISTS uq_system_parameter_global ON public.system_parameter (LOWER(system_parameter)) WHERE system_unit_id IS NULL;
CREATE UNIQUE INDEX IF NOT EXISTS uq_system_parameter_per_unit ON public.system_parameter (system_unit_id, LOWER(system_parameter)) WHERE system_unit_id IS NOT NULL;

COMMIT;
