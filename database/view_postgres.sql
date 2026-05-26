-- ====================================================================
-- SCRIPT DE CRIAÇÃO DE VIEWS EM ORDEM DE DEPENDÊNCIA (POSTGRESQL)
-- ====================================================================
-- Este script limpa e recria todas as 47 views do banco de dados na 
-- ordem exata de suas dependências (Topological Sort).
-- Traduzido 100% para a sintaxe nativa e compatível do PostgreSQL.

-- ==========================================
-- 1. DROP DAS VIEWS EXISTENTES (ORDEM REVERSA DE DEPENDÊNCIAS)
-- ==========================================

DROP VIEW IF EXISTS view_qtd_venda_mes_vendedor CASCADE;

-- Safely drop pivot_venda_mes_cliente (could be standard or materialized view in target database)
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'pivot_venda_mes_cliente' AND relkind = 'm') THEN
        DROP MATERIALIZED VIEW pivot_venda_mes_cliente CASCADE;
    ELSIF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'pivot_venda_mes_cliente' AND relkind = 'v') THEN
        DROP VIEW pivot_venda_mes_cliente CASCADE;
    END IF;
END $$;

DROP VIEW IF EXISTS pivot_vendas CASCADE;
DROP VIEW IF EXISTS view_base_cliente_mes CASCADE;
DROP VIEW IF EXISTS view_base_venda_categoria_ano CASCADE;
DROP VIEW IF EXISTS view_base_venda_cliente_ano CASCADE;
DROP VIEW IF EXISTS view_base_venda_produto_ano CASCADE;
DROP VIEW IF EXISTS view_venda_categoria_ano CASCADE;
DROP VIEW IF EXISTS view_venda_mes_vendedor CASCADE;

DROP VIEW IF EXISTS pivot_cliente_atendido_mes CASCADE;
DROP VIEW IF EXISTS vendas_ano_mes CASCADE;
DROP VIEW IF EXISTS view_ano_base_cliente CASCADE;
DROP VIEW IF EXISTS view_base_cliente CASCADE;
DROP VIEW IF EXISTS view_base_venda_categoria CASCADE;
DROP VIEW IF EXISTS view_base_venda_cliente CASCADE;
DROP VIEW IF EXISTS view_base_venda_produto CASCADE;
DROP VIEW IF EXISTS view_produto_estoque_preco CASCADE;
DROP VIEW IF EXISTS view_produto_orcamento CASCADE;

-- Safely drop view_total_catogoria_mes
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'view_total_catogoria_mes' AND relkind = 'm') THEN
        DROP MATERIALIZED VIEW view_total_catogoria_mes CASCADE;
    ELSIF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'view_total_catogoria_mes' AND relkind = 'v') THEN
        DROP VIEW view_total_catogoria_mes CASCADE;
    END IF;
END $$;

DROP VIEW IF EXISTS view_venda_categoria_mes CASCADE;
DROP VIEW IF EXISTS view_venda_cliente_mes CASCADE;
DROP VIEW IF EXISTS view_venda_mes CASCADE;
DROP VIEW IF EXISTS view_venda_regiao_mes CASCADE;

-- Safely drop view_vendedor_venda_mes
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'view_vendedor_venda_mes' AND relkind = 'm') THEN
        DROP MATERIALIZED VIEW view_vendedor_venda_mes CASCADE;
    ELSIF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'view_vendedor_venda_mes' AND relkind = 'v') THEN
        DROP VIEW view_vendedor_venda_mes CASCADE;
    END IF;
END $$;

DROP VIEW IF EXISTS cliente_atendido_mes CASCADE;
DROP VIEW IF EXISTS cliente_indicadores CASCADE;
DROP VIEW IF EXISTS cliente_notafiscal CASCADE;
DROP VIEW IF EXISTS cliente_positivado CASCADE;
DROP VIEW IF EXISTS clienteseekview CASCADE;
DROP VIEW IF EXISTS cliente_top10 CASCADE;

-- Safely drop mvc
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'mvc' AND relkind = 'm') THEN
        DROP MATERIALIZED VIEW mvc CASCADE;
    ELSIF EXISTS (SELECT 1 FROM pg_class WHERE relname = 'mvc' AND relkind = 'v') THEN
        DROP VIEW mvc CASCADE;
    END IF;
END $$;
DROP VIEW IF EXISTS vendas_vendedor_mes CASCADE;
DROP VIEW IF EXISTS venda_vendedor_produto CASCADE;
DROP VIEW IF EXISTS vendedor_nota_fiscal CASCADE;
DROP VIEW IF EXISTS view_ano_base CASCADE;
DROP VIEW IF EXISTS view_base_venda CASCADE;
DROP VIEW IF EXISTS view_cliente_saldo_titulo CASCADE;
DROP VIEW IF EXISTS view_cliente_vendedor CASCADE;
DROP VIEW IF EXISTS view_precos CASCADE;
DROP VIEW IF EXISTS view_titulo_cliente CASCADE;
DROP VIEW IF EXISTS view_venda_categoria CASCADE;
DROP VIEW IF EXISTS view_ultimo_preco CASCADE;
DROP VIEW IF EXISTS view_venda_cliente CASCADE;
DROP VIEW IF EXISTS view_venda_regiao CASCADE;
DROP VIEW IF EXISTS view_vendas CASCADE;
DROP VIEW IF EXISTS view_vendedor_cliente_status CASCADE;
DROP VIEW IF EXISTS view_vendedor_venda CASCADE;

-- ==========================================
-- 2. CRIAÇÃO DAS VIEWS (ORDEM DIRETA DE DEPENDÊNCIAS - LAYER 0)
-- ==========================================

-- 2.1. cliente_atendido_mes
DROP VIEW IF EXISTS cliente_atendido_mes CASCADE;
CREATE VIEW cliente_atendido_mes AS
SELECT DISTINCT
    nota_saida.cliente_id as cliente_id,
    nota_saida.vendedor1_id as vendedor1,
    nota_saida.vendedor2_id as vendedor2,
    nota_saida.mes as mes,
    nota_saida.ano as ano
FROM nota_saida
WHERE nota_saida.numero_titulo <> '' 
  AND nota_saida.tipo = 'N'
  AND nota_saida.reg_ativo = 'S'  
  AND nota_saida.especie_fiscal = 'SPED';

-- 2.2. cliente_indicadores
DROP VIEW IF EXISTS cliente_indicadores CASCADE;
CREATE VIEW cliente_indicadores AS
SELECT DISTINCT 
    cliente.id as id,
    cliente.cod_erp as cliente_codigo,
    cliente.fantasia as cliente_fantasia,
    municipio.descricao as cliente_municipio,
    estado.sigla as cliente_estado,
    cliente.status as cliente_status,
    vendedor.id as vendedor_id,
    vendedor.cod_erp as vendedor_codigo,
    vendedor.nome as vendedor_nome,
    cliente.data_cadastro as cliente_data_cadastro,
    cliente.primeira_compra as cliente_primeira_compra,
    cliente.ultima_visita as cliente_ultima_compra,
    (CURRENT_DATE - cliente.ultima_visita::date) as dias_sem_compra
FROM cliente 
JOIN vendedor ON vendedor.id = cliente.vendedor_id
JOIN municipio ON municipio.id = cliente.municipio_id
JOIN estado ON estado.id = municipio.estado_id;

-- 2.3. cliente_notafiscal
DROP VIEW IF EXISTS cliente_notafiscal CASCADE;
CREATE VIEW cliente_notafiscal AS
SELECT 
    nota_saida.id as "id",
    nota_saida.ano as "ano",
    nota_saida.mes as "mes",
    nota_saida.nota_fiscal as "nota_fiscal",
    nota_saida.serie_fiscal as "serie_fiscal",
    nota_saida.especie_fiscal as "especie_fiscal",
    nota_saida.condicao_id as "condicao_id",
    nota_saida.dt_emissao as "dt_emissao",
    nota_saida.vlr_bruto as "vlr_bruto",
    nota_saida.vlr_mercadoria as "vlr_mercadoria",
    nota_saida.vlr_desconto as "vlr_desconto",
    nota_saida.vlr_comodato as "vlr_comodato",
    nota_saida.vlr_itens as "vlr_itens",
    nota_saida.vlr_devolucao as "vlr_devolucao",
    nota_saida.vlr_frete as "vlr_frete",
    nota_saida.vendedor1_id as "vendedor1_id",
    nota_saida.comodato as "comodato",
    cliente.id as "cliente_id",
    cliente.cod_erp as "cod_erp",
    cliente.razao as "razao",
    cliente.fantasia as "fantasia",
    vendedor.id as "vendedor_id",
    vendedor.cod_erp as "cod_erp_vendedor",
    vendedor.nome as "nome",
    vendedor.nome_reduzido as "nome_reduzido",
    (CURRENT_DATE - nota_saida.dt_emissao::date) as "dias"
FROM 
    nota_saida, 
    cliente, 
    vendedor
WHERE 
    nota_saida.cliente_id = cliente.id AND 
    nota_saida.vendedor1_id = vendedor.id AND
    nota_saida.reg_ativo = 'S' 
ORDER BY "dias", "ano", "mes" DESC;

-- 2.4. cliente_positivado
DROP VIEW IF EXISTS cliente_positivado CASCADE;
CREATE VIEW cliente_positivado AS
SELECT DISTINCT 
    1 as id,
    nota_saida.ano as ano,
    nota_saida.mes as mes,
    nota_saida.cliente_id as cliente_id,
    nota_saida.vendedor1_id as vendedor_id,
    meta_vendedor_mes.numero_cliente as objetivo_numero_cliente
FROM nota_saida
LEFT JOIN meta_vendedor_mes ON ( 
    meta_vendedor_mes.vendedor_id = nota_saida.vendedor1_id and 
    meta_vendedor_mes.ano = nota_saida.ano and 
    meta_vendedor_mes.mes = nota_saida.mes ) 
JOIN cliente ON (cliente.id = nota_saida.cliente_id)
JOIN vendedor ON (
    nota_saida.vendedor1_id = vendedor.id 
    and vendedor.status = 'A'
) 
WHERE nota_saida.tipo = 'N'
  AND nota_saida.reg_ativo = 'S';

-- 2.5. clienteseekview
DROP VIEW IF EXISTS clienteseekview CASCADE;
CREATE VIEW clienteseekview AS
SELECT 
    cod_erp as cod_erp,
    razao as razao,
    fantasia as fantasia,
    cnpj_cpf as cnpj_cpf,
    telefone1 as telefone,
    email as email,
    status as situacao,
    vendedor_id as vendedor_id,
    id as cliente_id,
    municipio_id as municipio_id,
    ultima_visita as ultima_visita,
    data_cadastro as data_cadastro,
    condicao_pagamento_id as condicao_pagamento_id,
    tabela_preco_id as tabela_preco_id,
    seguimento_id as seguimento_id
FROM cliente
WHERE status <> 'B' AND cod_erp <> '';

-- 2.6. cliente_top10
DROP VIEW IF EXISTS cliente_top10 CASCADE;
CREATE VIEW cliente_top10 AS
SELECT 
    1 AS id,
    cliente_id AS cliente_id,
    vendedor1_id AS vendedor1_id,
    mes AS mes,
    ano AS ano,
    count(nota_fiscal) as nota_fiscal,
    sum(vlr_itens) as vlr_total
FROM nota_saida 
JOIN vendedor ON (
    vendedor.id = vendedor1_id
    AND vendedor.status = 'A'
)
WHERE vlr_comodato = 0
GROUP BY cliente_id, vendedor1_id, mes, ano
ORDER BY vlr_total DESC;

-- 2.7. mvc
-- Já removido no topo de forma segura (normal ou materialized)
CREATE MATERIALIZED VIEW mvc AS
SELECT 
    cliente.id as id,
    cliente.filial_id as filial,
    cliente.cod_erp as codigo,
    cliente.status as situacao,
    cliente.razao as razao,
    cliente.fantasia as fantasia,
    cliente.primeira_compra as primeira_compra,
    cliente.ultima_compra as ultima_compra,
    cliente.ultima_visita as ultima_visita,
    cliente.ultimo_atendimento as ultimo_atendimento,
    cliente.dt_bloqueio as dt_bloqueio,
    cliente.motivo_bloqueio as motivo_bloqueio,
    cliente.carteira as carteira,
    municipio.descricao as municipio_descricao,
    estado.sigla as estado_sigla,
    vendedor.id as vendedor_id,
    vendedor.nome as vendedor_nome,
    vendedor.nome_reduzido as vendedor_reduzido,
    vendedor.system_users_id as system_user_id,
    regiao_cliente.id as regiao_id,
    regiao_cliente.cod_erp as regiao_codigo,
    regiao_cliente.descricao as regiao_descricao,
    to_char(CURRENT_DATE, 'MM') as mes,
    to_char(CURRENT_DATE, 'YYYY') as ano,
    (CURRENT_DATE - INTERVAL '3 months')::date as tres,
    COALESCE(
        (CURRENT_DATE - cliente.ultima_compra::date),
        (CURRENT_DATE - cliente.data_cadastro::date),
        9999
    ) as dias,
    -- Pre-calculated financeiro_status inside the materialized view
    (SELECT CASE 
        WHEN EXISTS (SELECT 1 FROM titulo_receber tr WHERE tr.cliente_id = cliente.id AND tr.saldo > 0 AND tr.venc_real < CURRENT_DATE AND tr.reg_ativo = 'S') THEN 'R'
        WHEN EXISTS (SELECT 1 FROM titulo_receber tr WHERE tr.cliente_id = cliente.id AND tr.saldo > 0 AND tr.venc_real >= CURRENT_DATE AND tr.reg_ativo = 'S') THEN 'B'
        ELSE NULL 
     END) as financeiro_status
FROM cliente
LEFT JOIN municipio ON (cliente.municipio_id = municipio.id)
LEFT JOIN estado ON (municipio.estado_id = estado.id)
LEFT JOIN vendedor ON (cliente.vendedor_id = vendedor.id)
LEFT JOIN regiao_cliente ON (cliente.regiao_cliente_id = regiao_cliente.id )
WHERE cliente.cod_erp not in('00000002','00000001') AND cliente.reg_ativo = 'S';

CREATE UNIQUE INDEX idx_mvc_id ON mvc (id);
CREATE INDEX idx_mvc_vendedor ON mvc (vendedor_id);

-- 2.8. vendas_vendedor_mes
DROP VIEW IF EXISTS vendas_vendedor_mes CASCADE;
CREATE VIEW vendas_vendedor_mes AS
SELECT 
    sum(vlr_mercadoria) as vlr_mercadoria,
    sum(vlr_bruto) as vlr_bruto,
    sum(vlr_devolucao) as vlr_devolucao,
    sum(vlr_comodato) as vlr_comodato,
    sum(vlr_bruto) - sum(vlr_devolucao) as vlr_liquido,
    nota_saida.mes as mes,
    nota_saida.ano as ano,
    especie_fiscal as especie_fiscal,
    vendedor1_id as vendedor1_id,
    vendedor.status as vendedor1_status,
    nota_saida.reg_ativo as reg_ativo
FROM nota_saida 
JOIN vendedor ON (vendedor.id = nota_saida.vendedor1_id)
JOIN cliente ON (cliente.id = nota_saida.cliente_id)
WHERE nota_saida.numero_titulo <> '' 
  AND nota_saida.tipo = 'N'
GROUP BY ano, mes, vendedor1_id, especie_fiscal, vendedor1_id, vendedor.status, nota_saida.reg_ativo;

-- 2.9. venda_vendedor_produto
DROP VIEW IF EXISTS venda_vendedor_produto CASCADE;
CREATE VIEW venda_vendedor_produto AS
SELECT DISTINCT 
    nota_saida.id as id,
    nota_saida.nota_fiscal as nota_fiscal,
    nota_saida.serie_fiscal as serie_fiscal,
    nota_saida.dt_emissao as dt_emissao,
    nota_saida.mes as mes,
    nota_saida.ano as ano,
    nota_saida.tipo as tipo,
    vendedor.cod_erp as vendedor_cod,
    vendedor.nome as vendedor_nome,
    cliente.id as cliente_id,
    cliente.cod_erp as cliente_codigo,
    cliente.razao as cliente_razao,
    cliente.fantasia as cliente_fantasia,
    municipio.descricao as cliente_municipio,
    estado.sigla as cliente_estado,
    produto.cod_erp as produto_codigo,
    produto.descricao as produto_descricao,
    categoria.cod_erp as produto_categoria,
    categoria.descricao as categoria_descricao,
    sub_categoria.cod_erp as produto_sub_categoria,
    sub_categoria.descricao as sub_categoria_descricao,
    nota_saida_item.vlr_tabela as vlr_tabela,
    nota_saida_item.qtd as quantidade,
    nota_saida_item.vlr_unitario as vlr_unitario,
    nota_saida_item.vlr_desconto as vlr_desconto,
    nota_saida_item.vlr_total as vlr_total
FROM nota_saida
JOIN vendedor ON vendedor.id = nota_saida.vendedor1_id
JOIN cliente ON cliente.id = nota_saida.cliente_id
JOIN municipio ON municipio.id = cliente.municipio_id
JOIN estado ON estado.id = municipio.estado_id
JOIN nota_saida_item ON nota_saida_item.nota_saida_id = nota_saida.id
JOIN produto ON produto.id = nota_saida_item.produto_id
JOIN categoria ON categoria.id = produto.categoria_id
JOIN sub_categoria ON sub_categoria.id = produto.sub_categoria_id
WHERE nota_saida.reg_ativo = 'S' AND nota_saida.numero_titulo <> '';

-- 2.10. vendedor_nota_fiscal
DROP VIEW IF EXISTS vendedor_nota_fiscal CASCADE;
CREATE VIEW vendedor_nota_fiscal AS
SELECT 
    vendedor.id as vendedor_id,
    vendedor.cod_erp as vendedor_cod_erp,
    vendedor.nome as vendedor_nome,
    nota_saida.ano as ano,
    nota_saida.mes as mes,
    nota_saida.cliente_id as nota_saida_cliente_id,
    nota_saida.vlr_itens AS nota_saida_vlr_itens
FROM vendedor 
JOIN nota_saida ON (nota_saida.vendedor1_id = vendedor.id and nota_saida.numero_titulo <> '' and nota_saida.reg_ativo = 'S');

-- 2.11. view_ano_base
DROP VIEW IF EXISTS view_ano_base CASCADE;
CREATE VIEW view_ano_base AS
SELECT DISTINCT 
    ano as ano,
    0 as "01",
    0 as "02",
    0 as "03",
    0 as "04",
    0 as "05",
    0 as "06",
    0 as "07",
    0 as "08",
    0 as "09",
    0 as "10",
    0 as "11",
    0 as "12"
FROM nota_saida_item;

-- 2.12. view_base_venda
DROP VIEW IF EXISTS view_base_venda CASCADE;
CREATE VIEW view_base_venda AS
SELECT 
    nota_saida.id as id,
    nota_saida.ano as ano,
    nota_saida.mes as mes,
    nota_saida_item.item as nota_saida_item,
    nota_saida_item.cfop as cfop,
    nota_saida_item.vlr_total as vlr_total,
    nota_saida_item.vlr_bruto as vlr_bruto,
    nota_saida_item.vlr_dev as vlr_dev,
    categoria.id as categoria_id,
    produto.id as produto_id,
    cliente.id as cliente_id,
    nota_saida.vendedor1_id as vendedor_id,
    cliente.vendedor_id as cliente_vendedor_id 
FROM nota_saida 
JOIN nota_saida_item ON (nota_saida_item.nota_saida_id = nota_saida.id and nota_saida_item.reg_ativo = 'S')
JOIN produto ON (nota_saida_item.produto_id = produto.id)
JOIN categoria ON (produto.categoria_id = categoria.id)
JOIN cliente ON (nota_saida.cliente_id = cliente.id)
WHERE nota_saida.tipo = 'N'
  AND nota_saida.numero_titulo <> ''
  AND nota_saida.reg_ativo = 'S'  
  AND nota_saida.especie_fiscal = 'SPED';

-- 2.13. view_cliente_saldo_titulo
DROP VIEW IF EXISTS view_cliente_saldo_titulo CASCADE;
CREATE VIEW view_cliente_saldo_titulo AS
SELECT 
    cliente.id as id,
    cliente.cod_erp as cod_erp,
    cliente.razao as razao,
    cliente.fantasia as fantasia,
    cliente.vendedor_id as vendedor_id,
    cliente.tipo as tipo,
    cliente.status as status,
    cliente.situacao_cadastral_id as situacao_id,
    round(coalesce((
        SELECT sum(titulo_receber.saldo) 
        FROM titulo_receber 
        WHERE titulo_receber.reg_ativo = 'S'
          AND saldo > 0
          AND titulo_receber.venc_real < CURRENT_DATE
          AND titulo_receber.cliente_id = cliente.id
        GROUP BY titulo_receber.cliente_id
    ), 0)::numeric, 2) as vencido,
    round(coalesce((
        SELECT sum(titulo_receber.saldo) 
        FROM titulo_receber 
        WHERE titulo_receber.reg_ativo = 'S'
          AND saldo > 0
          AND titulo_receber.venc_real >= CURRENT_DATE
          AND titulo_receber.cliente_id = cliente.id
        GROUP BY titulo_receber.cliente_id
    ), 0)::numeric, 2) as aberto,
    round(coalesce((
        SELECT sum(titulo_receber.saldo) 
        FROM titulo_receber 
        WHERE titulo_receber.reg_ativo = 'S'
          AND saldo > 0
          AND titulo_receber.cliente_id = cliente.id
        GROUP BY titulo_receber.cliente_id
    ), 0)::numeric, 2) as saldo,
    round(coalesce((
        SELECT count(titulo_receber.id) 
        FROM titulo_receber 
        WHERE titulo_receber.reg_ativo = 'S'
          AND saldo > 0
          AND titulo_receber.cliente_id = cliente.id
        GROUP BY titulo_receber.cliente_id
    ), 0)::numeric, 2) as quantidade,
    round(coalesce((
        SELECT (CURRENT_DATE - min(titulo_receber.venc_real)::date)
        FROM titulo_receber 
        WHERE titulo_receber.reg_ativo = 'S'
          AND saldo > 0
          AND titulo_receber.venc_real < CURRENT_DATE
          AND titulo_receber.cliente_id = cliente.id
        GROUP BY titulo_receber.cliente_id
    ), 0)::numeric, 2) as MaiorAtraso
FROM cliente 
WHERE cliente.reg_ativo = 'S' 
GROUP BY cliente.id, cliente.cod_erp, cliente.razao, cliente.fantasia, cliente.vendedor_id, cliente.tipo, cliente.status, cliente.situacao_cadastral_id;

-- 2.14. view_cliente_vendedor
DROP VIEW IF EXISTS view_cliente_vendedor CASCADE;
CREATE VIEW view_cliente_vendedor AS
SELECT 
    cliente.id as cliente_id,
    cliente.cod_erp as cliente_cod_erp,
    cliente.razao as cliente_razao,
    cliente.status as cliente_situacao,
    cliente.carteira as cliente_carteira,
    cliente.ultima_visita as cliente_ult_visita,
    cliente.ultima_compra as cliente_ult_compra,
    cliente.ultimo_atendimento as cliente_ult_atendimento,
    cliente.data_cadastro as cliente_dt_cadastro,
    vendedor.id as vendedor_id,
    vendedor.cod_erp as vendedor_cod_erp,
    vendedor.nome as vendedor_nome
FROM cliente 
JOIN vendedor ON (vendedor.id = vendedor_id)
WHERE cliente.reg_ativo = 'S';

-- 2.15. view_precos
DROP VIEW IF EXISTS view_precos CASCADE;
CREATE VIEW view_precos AS
SELECT 
    tabela_preco_item.id as id,
    tabela_preco_item.tabela_preco_id as tabela_preco_id,
    tabela_preco.cod_erp as cod_erp,
    tabela_preco.descricao as descricao,
    tabela_preco_item.item as item,
    tabela_preco_item.produto_id as produto_id,
    tabela_preco_item.preco as preco
FROM tabela_preco_item
JOIN tabela_preco ON (tabela_preco.id = tabela_preco_item.tabela_preco_id and tabela_preco.status = 'S');

-- 2.16. view_titulo_cliente
DROP VIEW IF EXISTS view_titulo_cliente CASCADE;
CREATE VIEW view_titulo_cliente AS
SELECT 
    titulo_receber.id as id,
    cliente_id as cliente_id,
    vendedor.id as vendedor_id,
    titulo_receber.prefixo as prefixo,
    titulo_receber.tipo as tipo,
    titulo_receber.numero as numero,
    titulo_receber.parcela as parcela,
    titulo_receber.emissao as emissao,
    titulo_receber.venc_real as vencimento,
    titulo_receber.saldo as saldo,
    titulo_receber.valor as valor,
    titulo_receber.forma_pgto as forma,
    titulo_receber.pedido_id as pedido_id,
    titulo_receber.nota_fiscal_id as nota_fiscal_id,
    titulo_receber.origem as origem,
    titulo_receber.situacao as portador,
    CASE 
        WHEN titulo_receber.saldo > 0 AND titulo_receber.venc_real > CURRENT_DATE THEN 'A RECEBER'
        WHEN titulo_receber.saldo > 0 AND titulo_receber.venc_real <= CURRENT_DATE THEN 'EM ATRASO'
        WHEN titulo_receber.saldo = 0 THEN 'RECEBIDO'
        ELSE 'RECEBIDO'
    END as situacao,
    EXTRACT(MONTH FROM titulo_receber.venc_real)::integer as mesVencimento,
    EXTRACT(YEAR FROM titulo_receber.venc_real)::integer as anoVencimento,
    EXTRACT(MONTH FROM titulo_receber.emissao)::integer as mesEmissao,
    EXTRACT(YEAR FROM titulo_receber.emissao)::integer as anoEmissao,
    (CURRENT_DATE - titulo_receber.venc_real::date) as dias
FROM titulo_receber
JOIN cliente ON (cliente.reg_ativo = 'S' and cliente.id = titulo_receber.cliente_id)
JOIN vendedor ON (vendedor.id = cliente.vendedor_id)
WHERE titulo_receber.reg_ativo = 'S';

-- 2.17. view_venda_categoria
DROP VIEW IF EXISTS view_venda_categoria CASCADE;
CREATE VIEW view_venda_categoria AS
SELECT 
    categoria.id as categoria_id,
    categoria.cod_erp as categoria_cod_erp,
    categoria.descricao as categoria_descricao,
    produto.id as produto_id,
    nota_saida.id as nota_saida_id,
    nota_saida.nota_fiscal as nota_fiscal,
    nota_saida.especie_fiscal as especie_fiscal,
    nota_saida.ano as nota_saida_ano,
    nota_saida.mes as nota_saida_mes,
    nota_saida_item.vlr_bruto as nota_saida_item_vlr_total,
    (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as nota_saida_item_vlr_liquido,
    cliente.id as cliente_id,
    cliente.cod_erp as cliente_cod_erp,
    cliente.vendedor_id as cliente_vendedor_id,
    vendedor.id as vendedor_id,
    vendedor.status as vendedor_status
FROM nota_saida 
JOIN nota_saida_item ON (nota_saida_item.nota_saida_id = nota_saida.id and nota_saida_item.reg_ativo = 'S')
LEFT JOIN vendedor ON (vendedor.id = nota_saida.vendedor1_id)    
LEFT JOIN produto ON (nota_saida_item.produto_id = produto.id)
LEFT JOIN categoria ON (produto.categoria_id = categoria.id)
LEFT JOIN cliente ON (nota_saida.cliente_id = cliente.id)
WHERE nota_saida.numero_titulo <> '' 
  AND nota_saida.tipo = 'N'
  AND nota_saida.reg_ativo = 'S'  
  AND nota_saida.especie_fiscal = 'SPED';

-- 2.18. view_ultimo_preco
DROP VIEW IF EXISTS view_ultimo_preco CASCADE;
CREATE VIEW view_ultimo_preco AS
SELECT 
    nota_saida_item.id as id,
    nota_saida_item.produto_id as produto_id,
    nota_saida.cliente_id as cliente_id,
    nota_saida_item.nota_saida_id as nota_saida_id,
    nota_saida.nota_fiscal as nota_fiscal,
    nota_saida.serie_fiscal as serie_fiscal,
    nota_saida.especie_fiscal as especie_fiscal,
    nota_saida_item.qtd as quantidade,
    nota_saida_item.vlr_unitario as vlr_unitario,
    nota_saida_item.vlr_tabela as vlr_tabela,
    nota_saida_item.vlr_desconto as vlr_desconto,
    nota_saida_item.financeiro as financeiro,
    nota_saida_item.estoque as estoque,
    nota_saida.vendedor1_id as vendedor_id,
    nota_saida_item.tes as tes,
    max(nota_saida.dt_emissao) as dt_emissao
FROM nota_saida_item 
JOIN nota_saida ON (nota_saida_item.nota_saida_id = nota_saida.id and nota_saida.reg_ativo = 'S') 
WHERE nota_saida_item.reg_ativo = 'S' 
GROUP BY nota_saida_item.id, nota_saida_item.produto_id, nota_saida.cliente_id, 
         nota_saida_item.nota_saida_id, nota_saida.nota_fiscal, nota_saida.serie_fiscal, 
         nota_saida.especie_fiscal, nota_saida_item.qtd, nota_saida_item.vlr_unitario, 
         nota_saida_item.vlr_tabela, nota_saida_item.vlr_desconto, nota_saida_item.financeiro, 
         nota_saida_item.estoque, nota_saida.vendedor1_id, nota_saida_item.tes;

-- 2.19. view_venda_cliente
DROP VIEW IF EXISTS view_venda_cliente CASCADE;
CREATE VIEW view_venda_cliente AS
SELECT 
    cliente.id as cliente_id,
    cliente.cod_erp as cliente_cod_erp,
    produto.id as produto_id,
    nota_saida.dt_emissao as nota_saida_dt_emissao,
    nota_saida_item.ano as nota_saida_item_ano,
    nota_saida_item.mes as nota_saida_item_mes,
    nota_saida_item.vlr_bruto as nota_saida_item_vlr_total,
    (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as nota_saida_item_vlr_liquido,
    cliente.vendedor_id as cliente_vendedor_id,
    nota_saida.vendedor1_id as nota_vendedor_id 
FROM nota_saida_item
JOIN nota_saida ON (nota_saida_item.nota_saida_id = nota_saida.id 
    and nota_saida.numero_titulo <> '' 
    and nota_saida.tipo = 'N'
    and nota_saida.reg_ativo = 'S'  
    and nota_saida.especie_fiscal = 'SPED')
JOIN vendedor ON (vendedor.id = nota_saida.vendedor1_id)    
LEFT JOIN produto ON (nota_saida_item.produto_id = produto.id)
JOIN cliente ON (nota_saida.cliente_id = cliente.id);

-- 2.20. view_venda_regiao
DROP VIEW IF EXISTS view_venda_regiao CASCADE;
CREATE VIEW view_venda_regiao AS
SELECT 
    regiao_cliente.id as regiao_id,
    regiao_cliente.descricao as regiao_descricao,
    nota_saida.mes as mes,
    nota_saida.ano as ano,
    nota_saida_item.produto_id as produto_id,
    nota_saida.cliente_id as cliente_id,
    nota_saida_item.vlr_bruto as vlr_total,
    (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as vlr_liquido,
    nota_saida_item.vlr_dev as vlr_dev
FROM regiao_cliente
LEFT JOIN nota_saida ON ( 
    nota_saida.numero_titulo <> '' 
    and nota_saida.tipo = 'N'
    and nota_saida.reg_ativo = 'S'  
    and nota_saida.especie_fiscal = 'SPED'
)
LEFT JOIN nota_saida_item ON (
    nota_saida_item.nota_saida_id = nota_saida.id 
    and nota_saida_item.reg_ativo = 'S' 
    and nota_saida.mes = nota_saida_item.mes
    and nota_saida.ano = nota_saida_item.ano
)
LEFT JOIN cliente ON (nota_saida.cliente_id = cliente.id and regiao_cliente.id = cliente.regiao_cliente_id);

-- 2.21. view_vendas
DROP VIEW IF EXISTS view_vendas CASCADE;
CREATE VIEW view_vendas AS
SELECT DISTINCT 
    vendedor1_id as vendedor1_id,
    cliente_id as cliente_id,
    ano as ano,
    mes as mes
FROM nota_saida
WHERE nota_saida.numero_titulo <> '' 
  AND nota_saida.tipo = 'N'
  AND nota_saida.reg_ativo = 'S'  
  AND nota_saida.especie_fiscal = 'SPED';

-- 2.22. view_vendedor_cliente_status
DROP VIEW IF EXISTS view_vendedor_cliente_status CASCADE;
CREATE VIEW view_vendedor_cliente_status AS
SELECT 
    vendedor.id as vendedor_id,
    cliente.status as cliente_status,
    vendedor.desligado as vendedor_desligado,
    count(*) as quantidade 
FROM cliente 
JOIN vendedor ON (vendedor.id = cliente.vendedor_id)
GROUP BY cliente.status, vendedor.id, vendedor.desligado;

-- 2.23. view_vendedor_venda
DROP VIEW IF EXISTS view_vendedor_venda CASCADE;
CREATE VIEW view_vendedor_venda AS
SELECT 
    vendedor.id as vendedor_id,
    vendedor.nome_reduzido as nome_reduzido,
    vendedor.nome as nome,
    vendedor.status as situacao,
    nota_saida.mes as mes,
    nota_saida.ano as ano,
    nota_saida_item.produto_id as produto_id,
    produto.cod_erp as produto_cod_erp,
    produto.descricao as produto_descricao,
    categoria.id as categoria_id,
    categoria.cod_erp as categoria_cod_erp,
    categoria.descricao as categoria_descricao,
    nota_saida.cliente_id as cliente_id,
    cliente.razao as cliente_razao,
    cliente.fantasia as cliente_fantasia,
    nota_saida_item.vlr_bruto as nota_saida_item_vlr_total,
    (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as nota_saida_item_vlr_liquido
FROM vendedor
LEFT JOIN nota_saida ON ( 
    nota_saida.vendedor1_id = vendedor.id
    and nota_saida.numero_titulo <> '' 
    and nota_saida.tipo = 'N'
    and nota_saida.reg_ativo = 'S'  
    and nota_saida.especie_fiscal = 'SPED'
)
LEFT JOIN nota_saida_item ON (
    nota_saida_item.nota_saida_id = nota_saida.id 
    and nota_saida_item.reg_ativo = 'S' 
    and nota_saida.mes = nota_saida_item.mes
    and nota_saida.ano = nota_saida_item.ano
)
LEFT JOIN cliente ON (nota_saida.cliente_id = cliente.id)
LEFT JOIN produto ON (nota_saida_item.produto_id = produto.id)
LEFT JOIN categoria ON (produto.categoria_id = categoria.id);


-- ==========================================
-- 3. CRIAÇÃO DAS VIEWS (ORDEM DIRETA DE DEPENDÊNCIAS - LAYER 1)
-- ==========================================

-- 3.1. pivot_cliente_atendido_mes
DROP VIEW IF EXISTS pivot_cliente_atendido_mes CASCADE;
CREATE VIEW pivot_cliente_atendido_mes AS
SELECT 
    cliente_atendido_mes.vendedor1 as vendedor1_id,
    cliente_atendido_mes.vendedor2 as vendedor2_id,
    cliente_atendido_mes.ano as ano,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '01' THEN cliente_id END) AS janeiro,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '02' THEN cliente_id END) AS fevereiro,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '03' THEN cliente_id END) AS marco,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '04' THEN cliente_id END) AS abril,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '05' THEN cliente_id END) AS maio,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '06' THEN cliente_id END) AS junho,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '07' THEN cliente_id END) AS julho,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '08' THEN cliente_id END) AS agosto,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '09' THEN cliente_id END) AS setembro,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '10' THEN cliente_id END) AS outubro,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '11' THEN cliente_id END) AS novembro,
    COUNT(CASE WHEN cliente_atendido_mes.mes = '12' THEN cliente_id END) AS dezembro 
FROM cliente_atendido_mes
GROUP BY vendedor1, vendedor2, ano;

-- 3.2. vendas_ano_mes
DROP VIEW IF EXISTS vendas_ano_mes CASCADE;
CREATE VIEW vendas_ano_mes AS
SELECT 
    vendedor_nota_fiscal.vendedor_id as vendedor_id,
    vendedor_cod_erp as vendedor_cod_erp,
    vendedor_nome as vendedor_nome,
    cliente.cod_erp as cliente_cod_erp,
    cliente.razao as cliente_razao,
    cliente.id as cliente_id,
    ano as ano,
    mes as mes,
    sum(nota_saida_vlr_itens) AS valor
FROM vendedor_nota_fiscal
JOIN cliente ON (nota_saida_cliente_id = cliente.id) 
GROUP BY vendedor_nota_fiscal.vendedor_id, vendedor_cod_erp, vendedor_nome, cliente.id, cliente.cod_erp, cliente.razao, ano, mes;

-- 3.3. view_ano_base_cliente
DROP VIEW IF EXISTS view_ano_base_cliente CASCADE;
CREATE VIEW view_ano_base_cliente AS
SELECT 
    cliente.id as cliente_id,
    view_ano_base.ano as ano,
    view_ano_base."01" as "01",
    view_ano_base."02" as "02",
    view_ano_base."03" as "03",
    view_ano_base."04" as "04",
    view_ano_base."05" as "05",
    view_ano_base."06" as "06",
    view_ano_base."07" as "07",
    view_ano_base."08" as "08",
    view_ano_base."09" as "09",
    view_ano_base."10" as "10",
    view_ano_base."11" as "11",
    view_ano_base."12" as "12"
FROM view_ano_base, cliente;

-- 3.4. view_base_cliente
DROP VIEW IF EXISTS view_base_cliente CASCADE;
CREATE VIEW view_base_cliente AS
SELECT DISTINCT 
    cliente_id as cliente_id,
    vendedor_id as vendedor_id,
    ano as ano,
    mes as mes,
    id as nota_fiscal_id
FROM view_base_venda;

-- 3.5. view_base_venda_categoria
DROP VIEW IF EXISTS view_base_venda_categoria CASCADE;
CREATE VIEW view_base_venda_categoria AS
SELECT 
    cliente_id as cliente_id,
    categoria_id as categoria_id,
    vendedor_id as vendedor_id,
    ano as ano,
    CASE WHEN mes = '01' THEN vlr_total ELSE 0 END AS janeiro,
    CASE WHEN mes = '02' THEN vlr_total ELSE 0 END AS fevereiro,
    CASE WHEN mes = '03' THEN vlr_total ELSE 0 END AS marco,
    CASE WHEN mes = '04' THEN vlr_total ELSE 0 END AS abril,
    CASE WHEN mes = '05' THEN vlr_total ELSE 0 END AS maio,
    CASE WHEN mes = '06' THEN vlr_total ELSE 0 END AS junho,
    CASE WHEN mes = '07' THEN vlr_total ELSE 0 END AS julho,
    CASE WHEN mes = '08' THEN vlr_total ELSE 0 END AS agosto,
    CASE WHEN mes = '09' THEN vlr_total ELSE 0 END AS setembro,
    CASE WHEN mes = '10' THEN vlr_total ELSE 0 END AS outubro,
    CASE WHEN mes = '11' THEN vlr_total ELSE 0 END AS novembro,
    CASE WHEN mes = '12' THEN vlr_total ELSE 0 END AS dezembro 
FROM view_base_venda;

-- 3.6. view_base_venda_cliente
DROP VIEW IF EXISTS view_base_venda_cliente CASCADE;
CREATE VIEW view_base_venda_cliente AS
SELECT 
    cliente_id as cliente_id,
    vendedor_id as vendedor_id,
    ano as ano,
    CASE WHEN mes = '01' THEN vlr_total ELSE 0 END AS janeiro,
    CASE WHEN mes = '02' THEN vlr_total ELSE 0 END AS fevereiro,
    CASE WHEN mes = '03' THEN vlr_total ELSE 0 END AS marco,
    CASE WHEN mes = '04' THEN vlr_total ELSE 0 END AS abril,
    CASE WHEN mes = '05' THEN vlr_total ELSE 0 END AS maio,
    CASE WHEN mes = '06' THEN vlr_total ELSE 0 END AS junho,
    CASE WHEN mes = '07' THEN vlr_total ELSE 0 END AS julho,
    CASE WHEN mes = '08' THEN vlr_total ELSE 0 END AS agosto,
    CASE WHEN mes = '09' THEN vlr_total ELSE 0 END AS setembro,
    CASE WHEN mes = '10' THEN vlr_total ELSE 0 END AS outubro,
    CASE WHEN mes = '11' THEN vlr_total ELSE 0 END AS novembro,
    CASE WHEN mes = '12' THEN vlr_total ELSE 0 END AS dezembro 
FROM view_base_venda;

-- 3.7. view_base_venda_produto
DROP VIEW IF EXISTS view_base_venda_produto CASCADE;
CREATE VIEW view_base_venda_produto AS
SELECT 
    cliente_id as cliente_id,
    produto_id as produto_id,
    vendedor_id as vendedor_id,
    ano as ano,
    CASE WHEN mes = '01' THEN vlr_total ELSE 0 END AS janeiro,
    CASE WHEN mes = '02' THEN vlr_total ELSE 0 END AS fevereiro,
    CASE WHEN mes = '03' THEN vlr_total ELSE 0 END AS marco,
    CASE WHEN mes = '04' THEN vlr_total ELSE 0 END AS abril,
    CASE WHEN mes = '05' THEN vlr_total ELSE 0 END AS maio,
    CASE WHEN mes = '06' THEN vlr_total ELSE 0 END AS junho,
    CASE WHEN mes = '07' THEN vlr_total ELSE 0 END AS julho,
    CASE WHEN mes = '08' THEN vlr_total ELSE 0 END AS agosto,
    CASE WHEN mes = '09' THEN vlr_total ELSE 0 END AS setembro,
    CASE WHEN mes = '10' THEN vlr_total ELSE 0 END AS outubro,
    CASE WHEN mes = '11' THEN vlr_total ELSE 0 END AS novembro,
    CASE WHEN mes = '12' THEN vlr_total ELSE 0 END AS dezembro 
FROM view_base_venda;

-- 3.8. view_produto_estoque_preco
DROP VIEW IF EXISTS view_produto_estoque_preco CASCADE;
CREATE VIEW view_produto_estoque_preco AS
SELECT 
    produto.id as produto_id,
    produto.cod_erp as produto_cod_erp,
    produto.descricao as produto_descricao,
    produto.armazem_id as armazem_id,
    armazem.cod_erp as armazem_cod_erp,
    armazem.descricao as armazem_descricao,
    estoque.saldo as produto_saldo,
    view_precos.tabela_preco_id as tabela_preco_id,
    view_precos.descricao as precos_descricao,
    view_precos.preco as preco
FROM produto
LEFT JOIN estoque ON (estoque.produto_id = produto.id and estoque.armazem_id = produto.armazem_id)
LEFT JOIN armazem ON (armazem.id = estoque.armazem_id)
LEFT JOIN view_precos ON (view_precos.produto_id = produto.id);

-- 3.9. view_produto_orcamento
DROP VIEW IF EXISTS view_produto_orcamento CASCADE;
CREATE VIEW view_produto_orcamento AS
SELECT 
    produto.cod_erp as produto_cod_erp,
    produto.id as produto_id,
    produto.descricao as produto_descricao,
    produto.status as situacao,
    armazem.id as armazem_id,
    armazem.cod_erp as armazem_cod_erp,
    armazem.descricao as armazem_descricao,
    estoque.saldo as produto_saldo,
    view_precos.tabela_preco_id as tabela_preco_id,
    view_precos.descricao as precos_descricao,
    view_precos.preco as preco,
    produto.id as ultima_venda,
    produto.id as ultimo_preco
FROM produto
LEFT JOIN estoque ON (estoque.produto_id = produto.id and estoque.armazem_id = produto.armazem_id)
LEFT JOIN armazem ON (armazem.id = estoque.armazem_id)
LEFT JOIN view_precos ON (view_precos.produto_id = produto.id);

-- 3.10. view_total_catogoria_mes
-- Já removido no topo de forma segura (normal ou materialized)
CREATE MATERIALIZED VIEW view_total_catogoria_mes AS
SELECT DISTINCT 
    categoria.id as id,
    categoria.cod_erp as cod_erp,
    categoria.descricao as categoria,
    vendedor.id as vendedor_id,
    view_venda_categoria.nota_saida_mes as mes,
    view_venda_categoria.nota_saida_ano as ano,
    meta_vendedor_categoria.valor as vlr_objetivo,
    sum(view_venda_categoria.nota_saida_item_vlr_total) as vlr_total,
    sum(view_venda_categoria.nota_saida_item_vlr_liquido) as vlr_liquido,
    CASE 
        WHEN sum(meta_vendedor_categoria.valor) > 0 THEN round((sum(view_venda_categoria.nota_saida_item_vlr_total) * 100 / sum(meta_vendedor_categoria.valor))::numeric, 2) 
        ELSE 0 
    END as perc_total,
    CASE 
        WHEN sum(meta_vendedor_categoria.valor) > 0 THEN round((sum(view_venda_categoria.nota_saida_item_vlr_liquido) * 100 / sum(meta_vendedor_categoria.valor))::numeric, 2) 
        ELSE 0 
    END as perc_liquido
FROM view_venda_categoria
LEFT JOIN categoria ON (categoria.id = view_venda_categoria.categoria_id)
LEFT JOIN vendedor ON (view_venda_categoria.vendedor_id = vendedor.id)
LEFT JOIN meta_vendedor_mes ON (
    meta_vendedor_mes.vendedor_id = view_venda_categoria.vendedor_id
    and meta_vendedor_mes.ano = view_venda_categoria.nota_saida_ano    
    and meta_vendedor_mes.mes = view_venda_categoria.nota_saida_mes
    and meta_vendedor_mes.dt_delete IS NULL
)
LEFT JOIN meta_vendedor_categoria ON (
    meta_vendedor_categoria.meta_vendedor_mes_id = meta_vendedor_mes.id
    and meta_vendedor_categoria.cod_erp = categoria.cod_erp
    and meta_vendedor_categoria.dt_delete IS NULL
)
GROUP BY categoria.id, categoria.cod_erp, categoria.descricao, vendedor.id, 
         view_venda_categoria.nota_saida_mes, view_venda_categoria.nota_saida_ano, 
         meta_vendedor_categoria.valor;

CREATE UNIQUE INDEX idx_total_categoria_mes ON view_total_catogoria_mes (id, COALESCE(vendedor_id, 0), ano, mes);

-- 3.11. view_venda_categoria_mes
DROP VIEW IF EXISTS view_venda_categoria_mes CASCADE;
CREATE VIEW view_venda_categoria_mes AS
SELECT 
    categoria_id as id,
    categoria_cod_erp as categoria_cod_erp,
    categoria_descricao as categoria_descricao,
    vendedor_id as vendedor_id,
    nota_saida_ano as ano,
    CASE WHEN nota_saida_mes = '01' THEN nota_saida_item_vlr_liquido ELSE 0 END AS janeiro,
    CASE WHEN nota_saida_mes = '02' THEN nota_saida_item_vlr_liquido ELSE 0 END AS fevereiro,
    CASE WHEN nota_saida_mes = '03' THEN nota_saida_item_vlr_liquido ELSE 0 END AS marco,
    CASE WHEN nota_saida_mes = '04' THEN nota_saida_item_vlr_liquido ELSE 0 END AS abril,
    CASE WHEN nota_saida_mes = '05' THEN nota_saida_item_vlr_liquido ELSE 0 END AS maio,
    CASE WHEN nota_saida_mes = '06' THEN nota_saida_item_vlr_liquido ELSE 0 END AS junho,
    CASE WHEN nota_saida_mes = '07' THEN nota_saida_item_vlr_liquido ELSE 0 END AS julho,
    CASE WHEN nota_saida_mes = '08' THEN nota_saida_item_vlr_liquido ELSE 0 END AS agosto,
    CASE WHEN nota_saida_mes = '09' THEN nota_saida_item_vlr_liquido ELSE 0 END AS setembro,
    CASE WHEN nota_saida_mes = '10' THEN nota_saida_item_vlr_liquido ELSE 0 END AS outubro,
    CASE WHEN nota_saida_mes = '11' THEN nota_saida_item_vlr_liquido ELSE 0 END AS novembro,
    CASE WHEN nota_saida_mes = '12' THEN nota_saida_item_vlr_liquido ELSE 0 END AS dezembro 
FROM view_venda_categoria;

-- 3.12. view_venda_cliente_mes
DROP VIEW IF EXISTS view_venda_cliente_mes CASCADE;
CREATE VIEW view_venda_cliente_mes AS
SELECT 
    cliente_id as cliente_id,
    nota_vendedor_id as nota_vendedor_id,
    cliente_vendedor_id as cliente_vendedor_id,
    nota_saida_item_ano as ano,
    nota_saida_item_mes as mes,
    nota_saida_dt_emissao as dt_emissao,
    nota_saida_item_vlr_total as vlr_total
FROM view_venda_cliente
GROUP BY cliente_id, nota_vendedor_id, cliente_vendedor_id, nota_saida_item_ano, nota_saida_item_mes, nota_saida_dt_emissao, nota_saida_item_vlr_total;

-- 3.13. view_venda_mes
DROP VIEW IF EXISTS view_venda_mes CASCADE;
CREATE VIEW view_venda_mes AS
SELECT 
    vendedor1_id as vendedor1_id,
    ano as ano,
    mes as mes,
    count(cliente_id) as qtd
FROM view_vendas 
GROUP BY vendedor1_id, ano, mes;

-- 3.14. view_venda_regiao_mes
DROP VIEW IF EXISTS view_venda_regiao_mes CASCADE;
CREATE VIEW view_venda_regiao_mes AS
SELECT 
    view_venda_regiao.regiao_id as regiao_id,
    view_venda_regiao.regiao_descricao as regiao_descricao,
    view_venda_regiao.mes as mes,
    view_venda_regiao.ano as ano,
    round(sum(vlr_total)::numeric, 2) as vlr_total,
    round(sum(vlr_liquido)::numeric, 2) as vlr_liquido
FROM view_venda_regiao
GROUP BY view_venda_regiao.regiao_id, view_venda_regiao.regiao_descricao, view_venda_regiao.mes, view_venda_regiao.ano;

-- 3.15. view_vendedor_venda_mes
-- Já removido no topo de forma segura (normal ou materialized)
CREATE MATERIALIZED VIEW view_vendedor_venda_mes AS
SELECT DISTINCT 
    view_vendedor_venda.vendedor_id as vendedor_id,
    vendedor.nome as nome,
    vendedor.nome_reduzido as nome_reduzido,
    view_vendedor_venda.mes as mes,
    view_vendedor_venda.ano as ano,
    0 as positivacao,
    round(sum(nota_saida_item_vlr_total)::numeric, 2) as vlr_total,
    round(sum(nota_saida_item_vlr_liquido)::numeric, 2) as vlr_liquido,
    meta_vendedor_mes.valor as vlr_objetivo,
    CASE
        WHEN meta_vendedor_mes.valor > 0 THEN round((round(sum(nota_saida_item_vlr_total)::numeric, 2) * 100 / meta_vendedor_mes.valor)::numeric, 2)
        ELSE 0
    END as perc_total,
    CASE
        WHEN meta_vendedor_mes.valor > 0 THEN round((round(sum(nota_saida_item_vlr_liquido)::numeric, 2) * 100 / meta_vendedor_mes.valor)::numeric, 2)
        ELSE 0
    END as perc_liquido
FROM view_vendedor_venda
JOIN vendedor ON (vendedor.id = view_vendedor_venda.vendedor_id)
LEFT JOIN meta_vendedor_mes ON (
    meta_vendedor_mes.vendedor_id = view_vendedor_venda.vendedor_id 
    and meta_vendedor_mes.ano = view_vendedor_venda.ano 
    and meta_vendedor_mes.mes = view_vendedor_venda.mes
    and meta_vendedor_mes.dt_delete is null
)
GROUP BY view_vendedor_venda.vendedor_id, vendedor.nome, vendedor.nome_reduzido, 
         view_vendedor_venda.mes, view_vendedor_venda.ano, meta_vendedor_mes.valor;

CREATE UNIQUE INDEX idx_vendedor_venda_mes ON view_vendedor_venda_mes (vendedor_id, ano, mes);


-- ==========================================
-- 4. CRIAÇÃO DAS VIEWS (ORDEM DIRETA DE DEPENDÊNCIAS - LAYER 2)
-- ==========================================

-- 4.1. pivot_venda_mes_cliente
-- Já removido no topo de forma segura (normal ou materialized)
CREATE MATERIALIZED VIEW pivot_venda_mes_cliente AS
SELECT 
    cliente.id as cliente_id,
    cliente.vendedor_id as cliente_vendedor_id,
    COALESCE(nota_saida.vendedor1_id, 0) as nota_saida_vendedor_id,
    cliente.razao as cliente_nome,
    venda_ano.ano as ano,
    SUM(CASE WHEN nota_saida.mes = '01' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS janeiro,
    SUM(CASE WHEN nota_saida.mes = '02' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS fevereiro,
    SUM(CASE WHEN nota_saida.mes = '03' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS marco,
    SUM(CASE WHEN nota_saida.mes = '04' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS abril,
    SUM(CASE WHEN nota_saida.mes = '05' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS maio,
    SUM(CASE WHEN nota_saida.mes = '06' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS junho,
    SUM(CASE WHEN nota_saida.mes = '07' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS julho,
    SUM(CASE WHEN nota_saida.mes = '08' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS agosto,
    SUM(CASE WHEN nota_saida.mes = '09' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS setembro,
    SUM(CASE WHEN nota_saida.mes = '10' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS outubro,
    SUM(CASE WHEN nota_saida.mes = '11' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS novembro,
    SUM(CASE WHEN nota_saida.mes = '12' THEN (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) ELSE 0 END) AS dezembro 
FROM view_ano_base_cliente venda_ano
LEFT JOIN nota_saida ON (
    nota_saida.cliente_id = venda_ano.cliente_id 
    AND venda_ano.ano = nota_saida.ano
    AND nota_saida.numero_titulo <> '' 
    AND nota_saida.tipo = 'N'
    AND nota_saida.especie_fiscal = 'SPED'
    AND nota_saida.reg_ativo = 'S'  
)
LEFT JOIN nota_saida_item ON (nota_saida_item.nota_saida_id = nota_saida.id AND nota_saida_item.reg_ativo = 'S')
JOIN cliente ON (cliente.id = nota_saida.cliente_id)
JOIN vendedor ON (nota_saida.vendedor1_id = vendedor.id)
GROUP BY cliente.id, cliente.vendedor_id, COALESCE(nota_saida.vendedor1_id, 0), cliente.razao, venda_ano.ano;

CREATE UNIQUE INDEX idx_pivot_venda_mes_cliente ON pivot_venda_mes_cliente (cliente_id, ano, nota_saida_vendedor_id);

-- 4.2. pivot_vendas
DROP VIEW IF EXISTS pivot_vendas CASCADE;
CREATE VIEW pivot_vendas AS
SELECT 
    vendedor.id as vendedor_id,
    vendedor.cod_erp as vendedor_cod_erp,
    vendedor.nome as vendedor_nome,
    venda_ano.cliente_id as cliente_id,
    cliente.cod_erp as cliente_cod_erp,
    cliente.razao as cliente_razao,
    venda_ano.ano as ano,
    SUM(CASE WHEN venda_mes.mes = '01' THEN valor ELSE 0 END) AS JANEIRO,
    SUM(CASE WHEN venda_mes.mes = '02' THEN valor ELSE 0 END) AS FEVEREIRO,
    SUM(CASE WHEN venda_mes.mes = '03' THEN valor ELSE 0 END) AS MARCO,
    SUM(CASE WHEN venda_mes.mes = '04' THEN valor ELSE 0 END) AS ABRIL,
    SUM(CASE WHEN venda_mes.mes = '05' THEN valor ELSE 0 END) AS MAIO,
    SUM(CASE WHEN venda_mes.mes = '06' THEN valor ELSE 0 END) AS JUNHO,
    SUM(CASE WHEN venda_mes.mes = '07' THEN valor ELSE 0 END) AS JULHO,
    SUM(CASE WHEN venda_mes.mes = '08' THEN valor ELSE 0 END) AS AGOSTO,
    SUM(CASE WHEN venda_mes.mes = '09' THEN valor ELSE 0 END) AS SETEMBRO,
    SUM(CASE WHEN venda_mes.mes = '10' THEN valor ELSE 0 END) AS OUTUBRO,
    SUM(CASE WHEN venda_mes.mes = '11' THEN valor ELSE 0 END) AS NOVEMBRO,
    SUM(CASE WHEN venda_mes.mes = '12' THEN valor ELSE 0 END) AS DEZEMBRO 
FROM venda_mes_cliente venda_ano
JOIN cliente ON (cliente.id = venda_ano.cliente_id)
JOIN vendedor ON (vendedor.id = cliente.vendedor_id)
LEFT JOIN vendas_ano_mes venda_mes ON (venda_mes.cliente_id = venda_ano.cliente_id and venda_mes.ano = venda_ano.ano)
GROUP BY vendedor.id, vendedor.cod_erp, vendedor.nome, venda_ano.cliente_id, cliente.cod_erp, cliente.razao, venda_ano.ano;

-- 4.3. view_base_cliente_mes
DROP VIEW IF EXISTS view_base_cliente_mes CASCADE;
CREATE VIEW view_base_cliente_mes AS
SELECT DISTINCT 
    cliente_id as cliente_id,
    vendedor_id as vendedor_id,
    ano as ano,
    mes as mes,
    count(nota_fiscal_id) as nr_notas
FROM view_base_cliente
GROUP BY cliente_id, vendedor_id, ano, mes;

-- 4.4. view_base_venda_categoria_ano
DROP VIEW IF EXISTS view_base_venda_categoria_ano CASCADE;
CREATE VIEW view_base_venda_categoria_ano AS
SELECT
    cliente_id as cliente_id,
    categoria_id as categoria_id,
    vendedor_id as vendedor_id,
    ano as ano,
    sum(janeiro) AS janeiro,
    sum(fevereiro) AS fevereiro,
    sum(marco) AS marco,
    sum(abril) AS abril,
    sum(maio) AS maio,
    sum(junho) AS junho,
    sum(julho) AS julho,
    sum(agosto) AS agosto,
    sum(setembro) AS setembro,
    sum(outubro) AS outubro,
    sum(novembro) AS novembro,
    sum(dezembro) AS dezembro 
FROM view_base_venda_categoria
GROUP BY cliente_id, categoria_id, vendedor_id, ano;

-- 4.5. view_base_venda_cliente_ano
DROP VIEW IF EXISTS view_base_venda_cliente_ano CASCADE;
CREATE VIEW view_base_venda_cliente_ano AS
SELECT
    cliente_id as cliente_id,
    vendedor_id as vendedor_id,
    ano as ano,
    sum(janeiro) AS janeiro,
    sum(fevereiro) AS fevereiro,
    sum(marco) AS marco,
    sum(abril) AS abril,
    sum(maio) AS maio,
    sum(junho) AS junho,
    sum(julho) AS julho,
    sum(agosto) AS agosto,
    sum(setembro) AS setembro,
    sum(outubro) AS outubro,
    sum(novembro) AS novembro,
    sum(dezembro) AS dezembro 
FROM view_base_venda_cliente
GROUP BY cliente_id, vendedor_id, ano;

-- 4.6. view_base_venda_produto_ano
DROP VIEW IF EXISTS view_base_venda_produto_ano CASCADE;
CREATE VIEW view_base_venda_produto_ano AS
SELECT
    cliente_id as cliente_id,
    produto_id as produto_id,
    vendedor_id as vendedor_id,
    ano as ano,
    sum(janeiro) AS janeiro,
    sum(fevereiro) AS fevereiro,
    sum(marco) AS marco,
    sum(abril) AS abril,
    sum(maio) AS maio,
    sum(junho) AS junho,
    sum(julho) AS julho,
    sum(agosto) AS agosto,
    sum(setembro) AS setembro,
    sum(outubro) AS outubro,
    sum(novembro) AS novembro,
    sum(dezembro) AS dezembro 
FROM view_base_venda_produto
GROUP BY cliente_id, produto_id, vendedor_id, ano;

-- 4.7. view_venda_categoria_ano
DROP VIEW IF EXISTS view_venda_categoria_ano CASCADE;
CREATE VIEW view_venda_categoria_ano AS
SELECT 
    id as id,
    vendedor_id as vendedor_id,
    categoria_cod_erp as cod_erp,
    categoria_descricao as descricao,
    ano as ano,
    sum(janeiro) as janeiro,
    sum(fevereiro) as fevereiro,
    sum(marco) as marco,
    sum(abril) as abril,
    sum(maio) as maio,
    sum(junho) as junho,
    sum(julho) as julho,
    sum(agosto) as agosto,
    sum(setembro) as setembro,
    sum(outubro) as outubro,
    sum(novembro) as novembro,
    sum(dezembro) as dezembro
FROM view_venda_categoria_mes
GROUP BY view_venda_categoria_mes.id, vendedor_id, ano, categoria_cod_erp, categoria_descricao;

-- 4.8. view_venda_mes_vendedor
DROP VIEW IF EXISTS view_venda_mes_vendedor CASCADE;
CREATE VIEW view_venda_mes_vendedor AS
SELECT 
    vendedor1_id as vendedor1_id,
    ano as ano,
    CASE WHEN mes = '01' THEN qtd ELSE 0 END AS janeiro,
    CASE WHEN mes = '02' THEN qtd ELSE 0 END AS fevereiro,
    CASE WHEN mes = '03' THEN qtd ELSE 0 END AS marco,
    CASE WHEN mes = '04' THEN qtd ELSE 0 END AS abril,
    CASE WHEN mes = '05' THEN qtd ELSE 0 END AS maio,
    CASE WHEN mes = '06' THEN qtd ELSE 0 END AS junho,
    CASE WHEN mes = '07' THEN qtd ELSE 0 END AS julho,
    CASE WHEN mes = '08' THEN qtd ELSE 0 END AS agosto,
    CASE WHEN mes = '09' THEN qtd ELSE 0 END AS setembro,
    CASE WHEN mes = '10' THEN qtd ELSE 0 END AS outubro,
    CASE WHEN mes = '11' THEN qtd ELSE 0 END AS novembro,
    CASE WHEN mes = '12' THEN qtd ELSE 0 END AS dezembro 
FROM view_venda_mes;


-- ==========================================
-- 5. CRIAÇÃO DAS VIEWS (ORDEM DIRETA DE DEPENDÊNCIAS - LAYER 3)
-- ==========================================

-- 5.1. view_qtd_venda_mes_vendedor
DROP VIEW IF EXISTS view_qtd_venda_mes_vendedor CASCADE;
CREATE VIEW view_qtd_venda_mes_vendedor AS
SELECT 
    vendedor1_id as vendedor1_id,
    ano as ano,
    sum(janeiro) as janeiro,
    sum(fevereiro) as fevereiro,
    sum(marco) as marco,
    sum(abril) as abril,
    sum(maio) as maio,
    sum(junho) as junho,
    sum(julho) as julho,
    sum(agosto) as agosto,
    sum(setembro) as setembro,
    sum(outubro) as outubro,
    sum(novembro) as novembro,
    sum(dezembro) as dezembro
FROM view_venda_mes_vendedor
GROUP BY vendedor1_id, ano;

