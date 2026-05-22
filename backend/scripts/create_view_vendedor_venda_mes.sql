-- Criação/Ajuste da visão de metas e vendas por mês e vendedor
-- Adapte as tabelas de origem de acordo com o seu ERP se necessário.

DROP VIEW IF EXISTS view_vendedor_venda_mes CASCADE;

CREATE OR REPLACE VIEW view_vendedor_venda_mes AS
SELECT 
    m.ano::text AS ano,
    m.mes::text AS mes,
    m.vendedor_id,
    m.valor AS vlr_objetivo,
    COALESCE(v.vlr_liquido, 0) AS vlr_liquido,
    CASE 
        WHEN m.valor > 0 THEN (COALESCE(v.vlr_liquido, 0) / m.valor) * 100
        ELSE 0 
    END AS perc_liquido
FROM meta_vendedor_mes m
LEFT JOIN (
    -- Substitua essa subquery pela lógica real de faturamento por vendedor do seu ERP!
    -- Exemplo fictício de agregação:
    -- SELECT 
    --    EXTRACT(YEAR FROM data_emissao) as ano,
    --    EXTRACT(MONTH FROM data_emissao) as mes,
    --    vendedor_id,
    --    SUM(valor_total) as vlr_liquido
    -- FROM nota_fiscal
    -- WHERE status = 'Faturado'
    -- GROUP BY 1, 2, 3
    SELECT 
        '2026' as ano, 
        '05' as mes, 
        1 as vendedor_id, 
        0 as vlr_liquido
) v ON v.ano = m.ano::text AND v.mes = m.mes::text AND v.vendedor_id = m.vendedor_id;
