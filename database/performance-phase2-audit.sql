-- ============================================================
-- RCG CRM - Fase 2 de Performance / Integridade
-- Auditoria previa de dados
-- Data: 2026-05-26
--
-- Objetivo:
-- - identificar dados orfaos antes de criar FKs
-- - identificar duplicidades antes de criar unicidade
--
-- Uso:
-- - execute consulta por consulta
-- - qualquer linha retornada precisa ser tratada antes da constraint
-- ============================================================

-- ============================================================
-- 1. Orfaos - Comercial / CRM
-- ============================================================

-- cliente -> vendedor
SELECT c.id, c.vendedor_id
FROM cliente c
LEFT JOIN vendedor v ON v.id = c.vendedor_id
WHERE c.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- cliente -> municipio
SELECT c.id, c.municipio_id
FROM cliente c
LEFT JOIN municipio m ON m.id = c.municipio_id
WHERE c.municipio_id IS NOT NULL
  AND m.id IS NULL;

-- cliente -> condicao_pagamento
SELECT c.id, c.condicao_pagamento_id
FROM cliente c
LEFT JOIN condicao_pagamento cp ON cp.id = c.condicao_pagamento_id
WHERE c.condicao_pagamento_id IS NOT NULL
  AND cp.id IS NULL;

-- atendimento -> cliente
SELECT a.id, a.cliente_id
FROM atendimento a
LEFT JOIN cliente c ON c.id = a.cliente_id
WHERE a.cliente_id IS NOT NULL
  AND c.id IS NULL;

-- atendimento -> vendedor
SELECT a.id, a.vendedor_id
FROM atendimento a
LEFT JOIN vendedor v ON v.id = a.vendedor_id
WHERE a.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- atendimento -> atendimento_tipo
SELECT a.id, a.atendimento_tipo_id
FROM atendimento a
LEFT JOIN atendimento_tipo t ON t.id = a.atendimento_tipo_id
WHERE a.atendimento_tipo_id IS NOT NULL
  AND t.id IS NULL;

-- ============================================================
-- 2. Orfaos - Financeiro / Faturamento
-- ============================================================

-- titulo_receber -> cliente
SELECT tr.id, tr.cliente_id
FROM titulo_receber tr
LEFT JOIN cliente c ON c.id = tr.cliente_id
WHERE tr.cliente_id IS NOT NULL
  AND c.id IS NULL;

-- titulo_receber -> vendedor
SELECT tr.id, tr.vendedor_id
FROM titulo_receber tr
LEFT JOIN vendedor v ON v.id = tr.vendedor_id
WHERE tr.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- nota_saida -> cliente
SELECT ns.id, ns.cliente_id
FROM nota_saida ns
LEFT JOIN cliente c ON c.id = ns.cliente_id
WHERE ns.cliente_id IS NOT NULL
  AND c.id IS NULL;

-- nota_saida -> vendedor1
SELECT ns.id, ns.vendedor1_id
FROM nota_saida ns
LEFT JOIN vendedor v ON v.id = ns.vendedor1_id
WHERE ns.vendedor1_id IS NOT NULL
  AND v.id IS NULL;

-- nota_saida_item -> nota_saida
SELECT nsi.id, nsi.nota_saida_id
FROM nota_saida_item nsi
LEFT JOIN nota_saida ns ON ns.id = nsi.nota_saida_id
WHERE nsi.nota_saida_id IS NOT NULL
  AND ns.id IS NULL;

-- nota_saida_item -> produto
SELECT nsi.id, nsi.produto_id
FROM nota_saida_item nsi
LEFT JOIN produto p ON p.id = nsi.produto_id
WHERE nsi.produto_id IS NOT NULL
  AND p.id IS NULL;

-- ============================================================
-- 3. Orfaos - Metas / Preco
-- ============================================================

-- meta_vendedor_mes -> vendedor
SELECT mvm.id, mvm.vendedor_id
FROM meta_vendedor_mes mvm
LEFT JOIN vendedor v ON v.id = mvm.vendedor_id
WHERE mvm.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- meta_vendedor_categoria -> meta_vendedor_mes
SELECT mvc.id, mvc.meta_vendedor_mes_id
FROM meta_vendedor_categoria mvc
LEFT JOIN meta_vendedor_mes mvm ON mvm.id = mvc.meta_vendedor_mes_id
WHERE mvc.meta_vendedor_mes_id IS NOT NULL
  AND mvm.id IS NULL;

-- tabela_preco_item -> tabela_preco
SELECT tpi.id, tpi.tabela_preco_id
FROM tabela_preco_item tpi
LEFT JOIN tabela_preco tp ON tp.id = tpi.tabela_preco_id
WHERE tpi.tabela_preco_id IS NOT NULL
  AND tp.id IS NULL;

-- tabela_preco_item -> produto
SELECT tpi.id, tpi.produto_id
FROM tabela_preco_item tpi
LEFT JOIN produto p ON p.id = tpi.produto_id
WHERE tpi.produto_id IS NOT NULL
  AND p.id IS NULL;

-- ============================================================
-- 4. Duplicidades - Unicidade de negocio
-- ============================================================

-- vendedor_cliente
SELECT vendedor_id, cliente_id, COUNT(*)
FROM vendedor_cliente
GROUP BY vendedor_id, cliente_id
HAVING COUNT(*) > 1;

-- supervisor_vendedor
SELECT supervisor_id, vendedor_id, COUNT(*)
FROM supervisor_vendedor
GROUP BY supervisor_id, vendedor_id
HAVING COUNT(*) > 1;

-- meta_vendedor_mes (ativos)
SELECT vendedor_id, ano, mes, tipo, COUNT(*)
FROM meta_vendedor_mes
WHERE dt_delete IS NULL
GROUP BY vendedor_id, ano, mes, tipo
HAVING COUNT(*) > 1;

-- tabela_preco_item
SELECT tabela_preco_id, produto_id, COUNT(*)
FROM tabela_preco_item
GROUP BY tabela_preco_id, produto_id
HAVING COUNT(*) > 1;

-- cliente_acesso por login
SELECT login, COUNT(*)
FROM cliente_acesso
WHERE login IS NOT NULL
GROUP BY login
HAVING COUNT(*) > 1;
