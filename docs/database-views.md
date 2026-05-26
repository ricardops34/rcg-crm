# Documentacao do Banco: Views e Materialized Views

Fonte analisada em 2026-05-26:
- `database/view_postgres.sql`

## Visao Geral

As views do sistema estao organizadas em camadas:

- Views de base e consolidacao de vendas
- Views analiticas por cliente, vendedor, categoria e regiao
- Materialized views usadas por dashboard e MCV
- Pivots mensais para consumo rapido no frontend e analytics

## Materialized Views Criticas

### `mvc`

- Papel: base do MCV, com cliente, vendedor, localizacao, dias sem compra e status financeiro.
- Dependencias: `cliente`, `municipio`, `estado`, `vendedor`, `regiao_cliente`, `titulo_receber`
- Indices existentes:
  - `idx_mvc_id` em `(id)`
  - `idx_mvc_vendedor` em `(vendedor_id)`
- Observacao: o calculo de `financeiro_status` usa subconsultas correlacionadas em `titulo_receber`.

### `view_total_catogoria_mes`

- Papel: consolidacao mensal por categoria/vendedor com objetivo, valor total, valor liquido e percentual.
- Dependencias: `view_venda_categoria`, `categoria`, `vendedor`, `meta_vendedor_mes`, `meta_vendedor_categoria`
- Indice existente:
  - `idx_total_categoria_mes` em `(id, COALESCE(vendedor_id, 0), ano, mes)`

### `view_vendedor_venda_mes`

- Papel: total mensal por vendedor, com meta e percentuais.
- Dependencias: `view_vendedor_venda`, `vendedor`, `meta_vendedor_mes`
- Indice existente:
  - `idx_vendedor_venda_mes` em `(vendedor_id, ano, mes)`

### `pivot_venda_mes_cliente`

- Papel: pivot anual de vendas por cliente e vendedor, base do MCV.
- Dependencias: `view_ano_base_cliente`, `nota_saida`, `nota_saida_item`, `cliente`, `vendedor`
- Indice existente:
  - `idx_pivot_venda_mes_cliente` em `(cliente_id, ano, nota_saida_vendedor_id)`

## Inventario Completo

Formato:
- `nome`: tipo
- `dependencias`: tabelas ou views referenciadas em `FROM`/`JOIN`
- `papel`: descricao funcional inferida pelo nome e SQL

### Views de cliente

- `cliente_atendido_mes`: view
  - dependencias: `nota_saida`
  - papel: clientes atendidos/positivados por mes
- `cliente_indicadores`: view
  - dependencias: `cliente`, `vendedor`, `municipio`, `estado`
  - papel: indicadores cadastrais e comerciais do cliente
- `cliente_notafiscal`: view
  - dependencias: `nota_saida`
  - papel: notas por cliente
- `cliente_positivado`: view
  - dependencias: `nota_saida`, `meta_vendedor_mes`, `cliente`, `vendedor`
  - papel: clientes positivados versus meta
- `clienteseekview`: view
  - dependencias: `cliente`
  - papel: busca simplificada de cliente
- `cliente_top10`: view
  - dependencias: `nota_saida`, `vendedor`, `titulo_receber`, `cliente`, `municipio`, `estado`, `regiao_cliente`
  - papel: ranking/top clientes com informacoes financeiras
- `view_cliente_saldo_titulo`: view
  - dependencias: `titulo_receber`, `cliente`
  - papel: saldo financeiro por cliente
- `view_cliente_vendedor`: view
  - dependencias: `cliente`, `vendedor`
  - papel: relacionamento cliente-vendedor
- `view_titulo_cliente`: view
  - dependencias: `titulo_receber`, `cliente`, `vendedor`
  - papel: detalhamento de titulos por cliente
- `view_venda_cliente`: view
  - dependencias: `nota_saida_item`, `nota_saida`, `vendedor`, `produto`, `cliente`
  - papel: vendas detalhadas por cliente
- `view_venda_cliente_mes`: view
  - dependencias: `view_venda_cliente`
  - papel: consolidacao mensal de vendas por cliente
- `pivot_cliente_atendido_mes`: view
  - dependencias: `cliente_atendido_mes`
  - papel: pivot mensal de atendimento/positivacao
- `pivot_vendas`: view
  - dependencias: `venda_mes_cliente`, `cliente`, `vendedor`, `vendas_ano_mes`
  - papel: pivot auxiliar de vendas
- `view_ano_base_cliente`: view
  - dependencias: `view_ano_base`
  - papel: ano base para pivots de cliente
- `view_base_cliente`: view
  - dependencias: `view_base_venda`
  - papel: base agregada de cliente
- `view_base_cliente_mes`: view
  - dependencias: `view_base_cliente`
  - papel: base mensal de cliente
- `view_base_venda_cliente`: view
  - dependencias: `view_base_venda`
  - papel: base analitica de venda por cliente
- `view_base_venda_cliente_ano`: view
  - dependencias: `view_base_venda_cliente`
  - papel: base anual de venda por cliente

### Views de vendedor

- `vendas_vendedor_mes`: view
  - dependencias: `nota_saida`, `vendedor`, `cliente`
  - papel: somatorio mensal por vendedor
- `vendedor_nota_fiscal`: view
  - dependencias: `vendedor`, `nota_saida`
  - papel: notas fiscais por vendedor
- `view_vendedor_cliente_status`: view
  - dependencias: `cliente`, `vendedor`
  - papel: status de clientes por vendedor
- `view_vendedor_venda`: view
  - dependencias: `vendedor`, `nota_saida`, `nota_saida_item`, `cliente`, `produto`, `categoria`
  - papel: base detalhada de vendas por vendedor
- `view_venda_mes`: view
  - dependencias: `view_vendas`
  - papel: quantidade de vendas por vendedor/mes
- `view_venda_mes_vendedor`: view
  - dependencias: `view_venda_mes`
  - papel: consolidacao por vendedor
- `view_qtd_venda_mes_vendedor`: view
  - dependencias: `view_venda_mes_vendedor`
  - papel: quantidade mensal de vendas por vendedor

### Views de categoria, produto e preco

- `venda_vendedor_produto`: view
  - dependencias: `nota_saida`, `vendedor`, `cliente`, `municipio`, `estado`, `nota_saida_item`, `produto`, `categoria`, `sub_categoria`
  - papel: vendas por vendedor e produto
- `view_precos`: view
  - dependencias: `tabela_preco_item`, `tabela_preco`
  - papel: precos ativos por tabela
- `view_venda_categoria`: view
  - dependencias: `nota_saida`, `nota_saida_item`, `vendedor`, `produto`, `categoria`, `cliente`
  - papel: vendas por categoria
- `view_ultimo_preco`: view
  - dependencias: `nota_saida_item`, `nota_saida`
  - papel: ultimo preco praticado
- `view_base_venda_categoria`: view
  - dependencias: `view_base_venda`
  - papel: base analitica por categoria
- `view_base_venda_categoria_ano`: view
  - dependencias: `view_base_venda_categoria`
  - papel: base anual por categoria
- `view_venda_categoria_mes`: view
  - dependencias: `view_venda_categoria`
  - papel: vendas mensais por categoria
- `view_venda_categoria_ano`: view
  - dependencias: `view_venda_categoria_mes`
  - papel: vendas anuais por categoria
- `view_base_venda_produto`: view
  - dependencias: `view_base_venda`
  - papel: base analitica por produto
- `view_base_venda_produto_ano`: view
  - dependencias: `view_base_venda_produto`
  - papel: base anual por produto
- `view_produto_estoque_preco`: view
  - dependencias: `produto`, `estoque`, `armazem`, `view_precos`
  - papel: catalogo com estoque e preco
- `view_produto_orcamento`: view
  - dependencias: `produto`, `estoque`, `armazem`, `view_precos`, `view_venda_categoria`, `categoria`, `vendedor`, `meta_vendedor_mes`, `meta_vendedor_categoria`
  - papel: apoio a orcamento com preco, estoque e performance

### Views de vendas gerais e regiao

- `view_ano_base`: view
  - dependencias: `nota_saida_item`
  - papel: ano base para consolidacoes
- `view_base_venda`: view
  - dependencias: `nota_saida`, `nota_saida_item`, `produto`, `categoria`, `cliente`
  - papel: camada base de analytics de vendas
- `view_venda_regiao`: view
  - dependencias: `regiao_cliente`, `nota_saida`, `nota_saida_item`, `cliente`
  - papel: vendas por regiao
- `view_venda_regiao_mes`: view
  - dependencias: `view_venda_regiao`, `view_vendedor_venda`, `vendedor`, `meta_vendedor_mes`
  - papel: consolidacao mensal por regiao
- `view_vendas`: view
  - dependencias: `nota_saida`
  - papel: base simples de vendas
- `vendas_ano_mes`: view
  - dependencias: `vendedor_nota_fiscal`, `cliente`
  - papel: consolidacao ano-mes de vendas

## Materialized Views e Indices

- `mvc`
  - indices: `idx_mvc_id`, `idx_mvc_vendedor`
- `view_total_catogoria_mes`
  - indice: `idx_total_categoria_mes`
- `view_vendedor_venda_mes`
  - indice: `idx_vendedor_venda_mes`
- `pivot_venda_mes_cliente`
  - indice: `idx_pivot_venda_mes_cliente`

## Observacoes Estruturais

- A modelagem analitica depende muito de `view sobre view`, o que aumenta custo de leitura e manutencao.
- Os objetos materializados aliviam consultas do frontend, mas o custo de `REFRESH` pode crescer rapido sem indices transacionais nas tabelas base.
- O MCV esta bem apoiado em `mvc` + `pivot_venda_mes_cliente`, mas ainda depende de refresh consistente para nao apresentar defasagem.
