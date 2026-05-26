# Mapa de Relacionamento: Tabelas e Views

Base analisada em 2026-05-26:
- `database/erp_online-pgsql.sql`
- `database/view_postgres.sql`

## Objetivo

Este documento descreve:

- relacionamentos logicos entre tabelas transacionais
- relacionamentos por dominio
- dependencias entre views e materialized views
- caminho de dados das funcionalidades principais do sistema

Observacao:
- o DDL fornecido nao explicita todas as `FOREIGN KEY`
- os relacionamentos abaixo foram inferidos por nome de coluna, uso no codigo e SQL das views

## Visao Macro

Os grandes eixos do banco sao:

1. Cadastro comercial
   - `cliente`, `vendedor`, `supervisor`, `condicao_pagamento`, `tabela_preco`, `regiao_cliente`
2. CRM e agenda
   - `atendimento`, `atendimento_tipo`, `vendedor_atendimento`
3. Faturamento e analytics
   - `nota_saida`, `nota_saida_item`, `meta_vendedor_mes`, `meta_vendedor_categoria`
4. Financeiro
   - `titulo_receber`, `negociacao`, `negociacao_titulo_receber`
5. Catalogo e estoque
   - `produto`, `categoria`, `sub_categoria`, `estoque`, `armazem`, `tabela_preco_item`

## Relacionamentos Principais Entre Tabelas

### Cliente e Comercial

- `cliente.vendedor_id -> vendedor.id`
- `cliente.condicao_pagamento_id -> condicao_pagamento.id`
- `cliente.tabela_preco_id -> tabela_preco.id`
- `cliente.regiao_cliente_id -> regiao_cliente.id`
- `cliente.municipio_id -> municipio.id`
- `municipio.estado_id -> estado.id`
- `cliente.tipo_cliente_id -> tipo_cliente.id`
- `cliente.motivo_bloqueio_id -> motivo_bloqueio.id`
- `cliente.situacao_cadastral_id -> situacao_cadastral.id`

Relacionamentos derivados:
- `cliente_contato.cliente_id -> cliente.id`
- `cliente_condicao.cliente_id -> cliente.id`
- `cliente_cnae.cliente_id -> cliente.id`
- `cliente_socios.cliente_id -> cliente.id`
- `cliente_historico.cliente_id -> cliente.id`
- `cliente_acesso.cliente_id -> cliente.id`
- `cliente_atualizacao.cliente_id -> cliente.id`

### Vendedor e Hierarquia Comercial

- `vendedor.supervisor_id -> supervisor.id`
- `supervisor_vendedor.vendedor_id -> vendedor.id`
- `supervisor_vendedor.supervisor_id -> supervisor.id`
- `vendedor_cliente.vendedor_id -> vendedor.id`
- `vendedor_cliente.cliente_id -> cliente.id`
- `vendedor_atendimento.vendedor_id -> vendedor.id`

### CRM e Atendimento

- `atendimento.atendimento_tipo_id -> atendimento_tipo.id`
- `atendimento.vendedor_id -> vendedor.id`
- `atendimento.cliente_id -> cliente.id`
- `atendimento.orcamento_id -> orcamento.id`
- `atendimento.nota_saida_id -> nota_saida.id`

Relações paralelas/legadas:
- `cliente_atendimento.cliente_id -> cliente.id`
- `cliente_atendimento.vendedor_id -> vendedor.id`

### Orcamento e Pedido

- `orcamento.cliente_id -> cliente.id`
- `orcamento.vendedor_id -> vendedor.id`
- `orcamento.tabela_preco_id -> tabela_preco.id`
- `orcamento.condicao_pagamento_id -> condicao_pagamento.id`
- `orcamento.orcamento_estado_id -> orcamento_estado.id`
- `orcamento_item.orcamento_id -> orcamento.id`
- `orcamento_item.produto_id -> produto.id`
- `orcamento_historico.orcamento_id -> orcamento.id`
- `orcamento_historico.orcamento_estado_id -> orcamento_estado.id`

- `pedido.cliente_id -> cliente.id`
- `pedido.cliente_entrega_id -> cliente.id`
- `pedido.vendedor1_id -> vendedor.id`
- `pedido.vendedor2_id -> vendedor.id`
- `pedido.transportadora_id -> transportadora.id`
- `pedido.condicao_pagamento_id -> condicao_pagamento.id`
- `pedido.tabela_id -> tabela_preco.id`
- `pedido.pedido_estado_id -> pedido_estado.id`
- `pedido.orcamento_id -> orcamento.id`
- `pedido.nota_saida_id -> nota_saida.id`
- `pedido_item.pedido_id -> pedido.id`
- `pedido_item.produto_id -> produto.id`
- `pedido_item.armazem_id -> armazem.id`

### Faturamento

- `nota_saida.cliente_id -> cliente.id`
- `nota_saida.fornecedor_id -> fornecedor.id`
- `nota_saida.vendedor1_id -> vendedor.id`
- `nota_saida.vendedor2_id -> vendedor.id`
- `nota_saida.filial_id -> filial.id`
- `nota_saida.condicao_id -> condicao_pagamento.id`

- `nota_saida_item.nota_saida_id -> nota_saida.id`
- `nota_saida_item.produto_id -> produto.id`
- `nota_saida_item.cliente_id -> cliente.id`
- `nota_saida_item.vendedor1_id -> vendedor.id`
- `nota_saida_item.vendedor2_id -> vendedor.id`
- `nota_saida_item.pedido_item_id -> pedido_item.id`

- `notasaida_xml.nota_saida_id -> nota_saida.id`

Estrutura equivalente em compras:
- `nota_entrada.fornecedor_id -> fornecedor.id`
- `nota_entrada.cliente_id -> cliente.id`
- `nota_entrada.vendedor1_id -> vendedor.id`
- `nota_entrada_item.nota_entrada_id -> nota_entrada.id`
- `nota_entrada_item.produto_id -> produto.id`

### Financeiro

- `titulo_receber.cliente_id -> cliente.id`
- `titulo_receber.vendedor_id -> vendedor.id`
- `titulo_receber.natureza_id -> natureza.id`
- `titulo_receber.pedido_id -> pedido.id`
- `titulo_receber.nota_fiscal_id -> nota_saida.id`

- `negociacao.cliente_id -> cliente.id`
- `negociacao.vendedor_id -> vendedor.id`
- `negociacao.atendimento_tipo_id -> atendimento_tipo.id`
- `negociacao.atendimento_id -> atendimento.id`
- `negociacao_titulo_receber.negociacao_id -> negociacao.id`
- `negociacao_titulo_receber.titulo_receber_id -> titulo_receber.id`

### Produto, Estoque e Preco

- `produto.categoria_id -> categoria.id`
- `produto.sub_categoria_id -> sub_categoria.id`
- `sub_categoria.categoria_id -> categoria.id`
- `produto.fabricante_id -> fabricante.id`
- `produto.armazem_id -> armazem.id`

- `estoque.produto_id -> produto.id`
- `estoque.armazem_id -> armazem.id`

- `produto_caracteristica.produto_id -> produto.id`
- `produto_caracteristica.caracteristica_id -> caracteristica.id`
- `ficha_tecnica.produto_id -> produto.id`

- `tabela_preco_item.tabela_preco_id -> tabela_preco.id`
- `tabela_preco_item.produto_id -> produto.id`

### Metas

- `meta_vendedor_mes.vendedor_id -> vendedor.id`
- `meta_vendedor_categoria.meta_vendedor_mes_id -> meta_vendedor_mes.id`
- `meta_vendedor_categoria.categoria_id -> categoria.id`

## Relacionamentos Por Funcionalidade

### 1. MCV

Caminho principal:

- `cliente`
  - junta com `vendedor`, `municipio`, `estado`, `regiao_cliente`
- `titulo_receber`
  - usado para status financeiro do cliente
- `nota_saida` + `nota_saida_item`
  - origem das metricas de venda
- `pivot_venda_mes_cliente`
  - consolida vendas mensais por cliente
- `mvc`
  - materialized view central da tela

Resumo:
- cliente define a carteira
- titulo_receber define o risco financeiro
- nota_saida/nota_saida_item definem historico de venda
- `mvc` e `pivot_venda_mes_cliente` entregam a base pronta para o frontend

### 2. Agenda Comercial

Caminho principal:

- `atendimento`
  - origem dos eventos CRM
- `atendimento_tipo`
  - descricao, cor, icone
- `cliente`
  - nome do cliente no evento
- `vendedor`
  - dono do atendimento
- `nota_saida`
  - eventos de venda/faturamento mostrados no calendario

Resumo:
- agenda junta CRM e vendas no mesmo calendario

### 3. Cliente 360

Caminho principal:

- `cliente`
- `titulo_receber`
- `nota_saida`
- `nota_saida_item`
- `atendimento`
- `negociacao`

Resumo:
- cliente 360 e uma visao consolidada de cadastro, financeiro, mix, notas, comodato e historico de atendimento

### 4. Dashboard e Analytics

Caminho principal:

- `view_vendedor_venda`
- `view_vendedor_venda_mes`
- `view_venda_categoria`
- `view_total_catogoria_mes`
- `meta_vendedor_mes`
- `meta_vendedor_categoria`

Resumo:
- vendas detalhadas alimentam views analiticas
- metas completam os percentuais e comparativos

## Mapa de Dependencia das Views

### Camada 0: tabelas base

- `cliente`
- `vendedor`
- `supervisor`
- `municipio`
- `estado`
- `regiao_cliente`
- `produto`
- `categoria`
- `sub_categoria`
- `estoque`
- `armazem`
- `tabela_preco`
- `tabela_preco_item`
- `nota_saida`
- `nota_saida_item`
- `titulo_receber`
- `meta_vendedor_mes`
- `meta_vendedor_categoria`
- `atendimento`
- `atendimento_tipo`

### Camada 1: views diretamente sobre tabelas

- `cliente_atendido_mes <- nota_saida`
- `cliente_indicadores <- cliente + vendedor + municipio + estado`
- `cliente_notafiscal <- nota_saida`
- `cliente_positivado <- nota_saida + meta_vendedor_mes + cliente + vendedor`
- `clienteseekview <- cliente`
- `cliente_top10 <- nota_saida + vendedor + titulo_receber + cliente + municipio + estado + regiao_cliente`
- `vendas_vendedor_mes <- nota_saida + vendedor + cliente`
- `venda_vendedor_produto <- nota_saida + nota_saida_item + vendedor + cliente + produto + categoria + sub_categoria + municipio + estado`
- `vendedor_nota_fiscal <- vendedor + nota_saida`
- `view_ano_base <- nota_saida_item`
- `view_base_venda <- nota_saida + nota_saida_item + produto + categoria + cliente`
- `view_cliente_saldo_titulo <- titulo_receber + cliente`
- `view_cliente_vendedor <- cliente + vendedor`
- `view_precos <- tabela_preco_item + tabela_preco`
- `view_titulo_cliente <- titulo_receber + cliente + vendedor`
- `view_venda_categoria <- nota_saida + nota_saida_item + vendedor + produto + categoria + cliente`
- `view_ultimo_preco <- nota_saida_item + nota_saida`
- `view_venda_cliente <- nota_saida_item + nota_saida + vendedor + produto + cliente`
- `view_venda_regiao <- regiao_cliente + nota_saida + nota_saida_item + cliente`
- `view_vendas <- nota_saida`
- `view_vendedor_cliente_status <- cliente + vendedor`
- `view_vendedor_venda <- vendedor + nota_saida + nota_saida_item + cliente + produto + categoria`
- `mvc <- cliente + municipio + estado + vendedor + regiao_cliente + titulo_receber`

### Camada 2: views sobre views

- `pivot_cliente_atendido_mes <- cliente_atendido_mes`
- `vendas_ano_mes <- vendedor_nota_fiscal + cliente`
- `view_ano_base_cliente <- view_ano_base`
- `view_base_cliente <- view_base_venda`
- `view_base_venda_categoria <- view_base_venda`
- `view_base_venda_cliente <- view_base_venda`
- `view_base_venda_produto <- view_base_venda`
- `view_produto_estoque_preco <- produto + estoque + armazem + view_precos`
- `view_produto_orcamento <- produto + estoque + armazem + view_precos + view_venda_categoria + categoria + vendedor + meta_vendedor_mes + meta_vendedor_categoria`
- `view_venda_categoria_mes <- view_venda_categoria`
- `view_venda_cliente_mes <- view_venda_cliente`
- `view_venda_mes <- view_vendas`
- `view_venda_regiao_mes <- view_venda_regiao + view_vendedor_venda + vendedor + meta_vendedor_mes`
- `view_vendedor_venda_mes <- view_vendedor_venda + vendedor + meta_vendedor_mes`
- `view_total_catogoria_mes <- view_venda_categoria + categoria + vendedor + meta_vendedor_mes + meta_vendedor_categoria`

### Camada 3: pivots e agregacoes finais

- `pivot_venda_mes_cliente <- view_ano_base_cliente + nota_saida + nota_saida_item + cliente + vendedor`
- `pivot_vendas <- venda_mes_cliente + cliente + vendedor + vendas_ano_mes`
- `view_base_cliente_mes <- view_base_cliente`
- `view_base_venda_categoria_ano <- view_base_venda_categoria`
- `view_base_venda_cliente_ano <- view_base_venda_cliente`
- `view_base_venda_produto_ano <- view_base_venda_produto`
- `view_venda_categoria_ano <- view_venda_categoria_mes`
- `view_venda_mes_vendedor <- view_venda_mes`
- `view_qtd_venda_mes_vendedor <- view_venda_mes_vendedor`

## Materialized Views Mais Sensiveis

### `mvc`

Relacoes:
- `mvc.id -> cliente.id`
- `mvc.vendedor_id -> vendedor.id`
- `mvc.regiao_id -> regiao_cliente.id`

Dependencia funcional:
- usa `titulo_receber` para calcular `financeiro_status`

### `view_vendedor_venda_mes`

Relacoes:
- `view_vendedor_venda_mes.vendedor_id -> vendedor.id`
- `view_vendedor_venda_mes.(vendedor_id, ano, mes)` cruza com `meta_vendedor_mes`

### `view_total_catogoria_mes`

Relacoes:
- `view_total_catogoria_mes.id -> categoria.id`
- `view_total_catogoria_mes.vendedor_id -> vendedor.id`
- cruza com `meta_vendedor_mes` e `meta_vendedor_categoria`

### `pivot_venda_mes_cliente`

Relacoes:
- `pivot_venda_mes_cliente.cliente_id -> cliente.id`
- `pivot_venda_mes_cliente.cliente_vendedor_id -> cliente.vendedor_id`
- `pivot_venda_mes_cliente.nota_saida_vendedor_id -> nota_saida.vendedor1_id`

## Pontos de Atencao no Relacionamento

- Existem dois registros de agenda/CRM conceitualmente proximos:
  - `atendimento`
  - `cliente_atendimento`
  Isso sugere legado ou duplicidade de conceito.

- `nota_saida` e `nota_saida_item` sao o centro de quase todo analytics.
  Qualquer mudanca nessas tabelas impacta boa parte das views.

- `cliente`, `vendedor`, `titulo_receber` e `meta_vendedor_mes` sao entidades de alta reutilizacao.

- A ausencia de `FOREIGN KEY` explicita no DDL aumenta risco de:
  - dados orfaos
  - joins inconsistentes
  - cardinalidade mal estimada pelo otimizador

## Recomendacao de Uso Deste Mapa

Use este documento para:

- entender impacto de alteracoes de schema
- revisar queries do backend
- planejar criacao de `FOREIGN KEY`
- identificar entidades centrais para indexacao
- explicar o fluxo de dados do MCV, agenda e dashboard
