-- =========================================================================
-- SCRIPT DE MIGRAÇÃO: BASE DE SEGURANÇA (rcg_security)
-- =========================================================================
-- Adiciona colunas para armazenamento de logomarca e favicon personalizados 
-- em formato Base64 na tabela de Unidades (system_unit).
-- =========================================================================

BEGIN;

ALTER TABLE public.system_unit ADD COLUMN IF NOT EXISTS logo TEXT;
ALTER TABLE public.system_unit ADD COLUMN IF NOT EXISTS favicon TEXT;

COMMIT;
