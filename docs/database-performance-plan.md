# Plano de Ajuste de Performance do Banco

Base da analise:
- `database/erp_online-pgsql.sql`
- `database/view_postgres.sql`
- consultas do frontend/backend atualmente usadas por MCV, agenda comercial, cliente 360, vendas e financeiro

Data da analise: 2026-05-26

## Diagnostico Resumido

Os principais gargalos potenciais do modelo atual sao:

1. Quase nao ha indices transacionais declarados no DDL base.
2. O analytics depende fortemente de `view sobre view`.
3. Materialized views importantes (`mvc`, `view_vendedor_venda_mes`, `view_total_catogoria_mes`, `pivot_venda_mes_cliente`) fazem joins pesados sobre tabelas grandes.
4. Campos monetarios usam `float`, inadequado para financeiro e agregacoes.
5. Ano e mes aparecem como `char`/`varchar` em varias tabelas, piorando seletividade, comparacao e padronizacao.
6. Ha sinais de chaves estrangeiras e unicidade sendo tratadas mais por convencao do que por restricao formal.

## Melhorias Prioritarias

### Prioridade 1: indices para consultas reais do sistema

Criar primeiro os indices que afetam diretamente telas e APIs existentes.

#### CRM e agenda

- `atendimento (vendedor_id, horario_inicial)` `WHERE dt_delete IS NULL`
- `atendimento (cliente_id, horario_inicial DESC)` `WHERE dt_delete IS NULL`
- `atendimento (atendimento_tipo_id, horario_inicial)` `WHERE dt_delete IS NULL`

Impacto esperado:
- melhora agenda comercial
- melhora historico de atendimento do cliente
- melhora filtros por vendedor e tipo

#### MCV e financeiro

- `titulo_receber (cliente_id, venc_real)` `WHERE reg_ativo = 'S' AND saldo > 0`
- `titulo_receber (cliente_id, saldo, venc_real)` `WHERE reg_ativo = 'S'`
- `cliente (vendedor_id, reg_ativo)`
- `cliente (municipio_id)`
- `cliente (regiao_cliente_id)`
- `cliente (ultima_compra)`
- `cliente (ultimo_atendimento)`

Impacto esperado:
- refresh mais rapido da materialized view `mvc`
- filtros do MCV mais estaveis
- ganho em cliente 360 e cobranca

#### Faturamento e analytics

- `nota_saida (vendedor1_id, dt_emissao)` `WHERE reg_ativo = 'S'`
- `nota_saida (cliente_id, dt_emissao)` `WHERE reg_ativo = 'S'`
- `nota_saida (ano, mes, vendedor1_id)` `WHERE reg_ativo = 'S'`
- `nota_saida (COALESCE(dt_nfe, dt_emissao))` `WHERE reg_ativo = 'S'`
- `nota_saida_item (nota_saida_id)`
- `nota_saida_item (produto_id, ano, mes)`
- `nota_saida_item (cliente_id, ano, mes)`
- `nota_saida_item (vendedor1_id, ano, mes)`

Impacto esperado:
- acelera views de vendas
- acelera agendas que misturam faturamento e CRM
- reduz custo de refresh das materialized views

#### Metas e precificacao

- `meta_vendedor_mes (vendedor_id, ano, mes)` `WHERE dt_delete IS NULL`
- `meta_vendedor_categoria (meta_vendedor_mes_id, cod_erp)` `WHERE dt_delete IS NULL`
- `tabela_preco_item (tabela_preco_id, produto_id)`
- `estoque (produto_id, armazem_id)`

Impacto esperado:
- acelera dashboards e catalogo/preco

### Prioridade 2: integridade e modelagem

#### Adicionar restricoes formais

Recomendado adicionar `FOREIGN KEY` nas relacoes mais criticas:

- `cliente.vendedor_id -> vendedor.id`
- `cliente.municipio_id -> municipio.id`
- `cliente.condicao_pagamento_id -> condicao_pagamento.id`
- `atendimento.cliente_id -> cliente.id`
- `atendimento.vendedor_id -> vendedor.id`
- `atendimento.atendimento_tipo_id -> atendimento_tipo.id`
- `titulo_receber.cliente_id -> cliente.id`
- `nota_saida.cliente_id -> cliente.id`
- `nota_saida_item.nota_saida_id -> nota_saida.id`
- `meta_vendedor_mes.vendedor_id -> vendedor.id`
- `meta_vendedor_categoria.meta_vendedor_mes_id -> meta_vendedor_mes.id`

Beneficios:
- melhora consistencia
- ajuda o otimizador com cardinalidade real
- reduz lixo relacional

#### Adicionar unicidade de negocio

Casos recomendados:

- `vendedor_cliente (vendedor_id, cliente_id)`
- `meta_vendedor_mes (vendedor_id, ano, mes, tipo)` considerando `dt_delete IS NULL`
- `tabela_preco_item (tabela_preco_id, produto_id)`
- `cliente_acesso (cliente_id, user_id)` ou `login`

### Prioridade 3: tipos de dados

#### Trocar `float` por `numeric`

Tabelas com maior urgencia:

- `titulo_receber`
- `nota_saida`
- `nota_saida_item`
- `nota_entrada`
- `nota_entrada_item`
- `meta_vendedor_mes`
- `meta_vendedor_categoria`
- `pedido_item`
- `orcamento_item`

Beneficios:
- evita erro acumulado em somas e percentuais
- melhora previsibilidade de relatorios

#### Padronizar `ano` e `mes`

Substituir gradualmente:

- `ano char(4)` -> `smallint` ou `integer`
- `mes char(2)` -> `smallint`

Beneficios:
- comparacoes mais simples
- melhores indices compostos
- menos casting em views

### Prioridade 4: otimizar materialized views

#### `mvc`

Hoje o maior ponto sensivel e `financeiro_status`, que usa subqueries por cliente.

Melhorias sugeridas:

- criar indice parcial forte em `titulo_receber`
- avaliar tabela ou view auxiliar com status financeiro por cliente
- refresh em janela controlada, com `REFRESH MATERIALIZED VIEW CONCURRENTLY`

#### `pivot_venda_mes_cliente`

Melhorias sugeridas:

- garantir indices fortes em `nota_saida`, `nota_saida_item`, `cliente`, `vendedor`
- avaliar reducao do escopo anual no refresh, se o negocio permitir

#### `view_vendedor_venda_mes` e `view_total_catogoria_mes`

Melhorias sugeridas:

- materializar apenas apos fechamento/atualizacao relevante
- executar `ANALYZE` apos refresh em ambientes com grande volume

### Prioridade 5: busca textual

Filtros por nome e fantasia podem evoluir com `pg_trgm`.

Sugestao:

- habilitar extensao `pg_trgm`
- criar indices GIN:
  - `cliente.razao`
  - `cliente.fantasia`
  - `cliente.cod_erp`
  - `vendedor.nome`
  - `produto.descricao`

Beneficios:
- melhora `ILIKE '%texto%'`
- ajuda filtros do MCV e cadastros

### Prioridade 6: particionamento

Se o volume historico estiver alto, considerar particionar:

- `nota_saida`
- `nota_saida_item`
- `titulo_receber`
- eventualmente `atendimento`

Criterio recomendado:
- particionamento por ano ou por ano/mes

Aplicar apenas depois de:
- medir volume real
- estabilizar indices
- revisar jobs de carga/refresh

## Plano de Execucao

### Fase 1: baixo risco, alto retorno

1. Criar indices nas tabelas de CRM, financeiro e faturamento usadas pelo MCV e agenda.
2. Rodar `EXPLAIN ANALYZE` nas consultas:
   - `/analytics/mvc/table`
   - agenda comercial
   - cliente 360 atendimentos
   - inadimplencia
3. Medir tempo de refresh das materialized views.

### Fase 2: integridade e consistencia

1. Adicionar `FOREIGN KEY` nas relacoes principais.
2. Adicionar chaves unicas de negocio.
3. Corrigir dados orfaos antes de aplicar constraints.

Arquivos gerados para esta fase:
- `database/performance-phase2-audit.sql`
- `database/performance-phase2-constraints.sql`

### Fase 3: modelagem numerica

1. Migrar colunas financeiras para `numeric`.
2. Padronizar colunas `ano` e `mes`.
3. Remover casts repetidos nas views.

### Fase 4: analytics

1. Revisar as views mais profundas (`view_base_venda`, `view_venda_categoria`, `view_vendedor_venda`).
2. Reduzir encadeamento de views onde houver repeticao.
3. Reavaliar o desenho de `mvc` e `pivot_venda_mes_cliente`.

## Script Inicial Recomendado

Primeiro lote de indices recomendado:

```sql
CREATE INDEX IF NOT EXISTS idx_atendimento_vendedor_inicio
  ON atendimento (vendedor_id, horario_inicial)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_atendimento_cliente_inicio
  ON atendimento (cliente_id, horario_inicial DESC)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_titulo_receber_cliente_venc
  ON titulo_receber (cliente_id, venc_real)
  WHERE reg_ativo = 'S' AND saldo > 0;

CREATE INDEX IF NOT EXISTS idx_cliente_vendedor_regativo
  ON cliente (vendedor_id, reg_ativo);

CREATE INDEX IF NOT EXISTS idx_nota_saida_vendedor_emissao
  ON nota_saida (vendedor1_id, dt_emissao)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_cliente_emissao
  ON nota_saida (cliente_id, dt_emissao)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_ano_mes_vendedor
  ON nota_saida (ano, mes, vendedor1_id)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_nota
  ON nota_saida_item (nota_saida_id);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_produto_ano_mes
  ON nota_saida_item (produto_id, ano, mes);

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_mes_lookup
  ON meta_vendedor_mes (vendedor_id, ano, mes)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_categoria_lookup
  ON meta_vendedor_categoria (meta_vendedor_mes_id, cod_erp)
  WHERE dt_delete IS NULL;
```

## Riscos e Cuidados

- Criar indice em tabela grande exige janela controlada.
- Adicionar `FOREIGN KEY` pode falhar se houver dados inconsistentes.
- Migrar `float` para `numeric` precisa de teste em relatorios e integracoes.
- Particionamento exige revisao das rotinas de carga e das views.
