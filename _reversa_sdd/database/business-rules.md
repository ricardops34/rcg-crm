# Regras de Negócio em Banco de Dados (Views e Constraints)

O sistema `rcg` delega grande parte da inteligência de agregação e consistência para a camada de persistência.

## 1. Inteligência via Views (BI)

O banco de dados contém uma camada rica de Views que processam indicadores em tempo real:

- **`view_cliente_saldo_titulo`**: Realiza subqueries para calcular o somatório de saldos `vencido`, `aberto` e a contagem de títulos por cliente. Centraliza a regra de "Maior Atraso".
- **`view_vendedor_venda_mes`**: Cruza dados de vendas reais (`view_vendedor_venda`) com as metas (`meta_vendedor_mes`), calculando percentuais de atingimento (`perc_total`, `perc_liquido`) no próprio SQL.
- **`mvc` (Média de Venda do Cliente)**: Consolida datas de primeira/última compra e visitas em uma única visão para facilitar a análise CRM.
- **Views Pivot**: (`pivot_venda_mes_cliente`, `pivot_vendas`) transformam registros de meses (linhas) em colunas (`janeiro` a `dezembro`), permitindo relatórios de evolução de 12 meses.

## 2. Consistência e Constraints

- **Unicidade:** Chaves de integração com ERP legado (`cod_erp`) são protegidas por `UNIQUE` constraints em `cliente` e `produto`.
- **Integridade Referencial:** Todas as relações entre Pedidos, Itens, Notas e Financeiro são garantidas por `FOREIGN KEY` explícitas, impedindo registros órfãos.
- **Defaults de BI:** Tabelas de BI como `cliente_vendedor_mes` utilizam `DEFAULT 0` para todos os meses, garantindo cálculos seguros de somatórios.

## 3. Lógica de Faturamento (Inferida)

Através da view `view_base_venda`, identificamos que o sistema considera como "Venda Efetiva" (SPED) apenas:
- Notas do tipo `'N'` (Normal).
- Que geraram títulos no financeiro (`numero_titulo <> ''`).
- Registros ativos (`reg_ativo = 'S'`).
- Espécie fiscal `'SPED'`.
