-- ============================================================
-- FIX: Corrige controllers inconsistentes no banco de produção
-- Execute no banco "security"
-- ============================================================

-- 1. Metas: MetaVendedorMesList → MetaList
UPDATE system_program
SET controller = 'MetaList'
WHERE controller = 'MetaVendedorMesList';

-- 2. Notas Fiscais: NotaFiscalList → NotaSaidaList
UPDATE system_program
SET controller = 'NotaSaidaList'
WHERE controller = 'NotaFiscalList';

-- 3. Verificação final
SELECT id, name, controller, system_module_id
FROM system_program
ORDER BY system_module_id, "order";
