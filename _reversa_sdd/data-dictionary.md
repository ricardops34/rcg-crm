# Dicionário de Dados: Módulo Desenvolvimento

Entidades e tabelas em teste ou suporte técnico.

## Tabela: `orcamento`
Header de propostas comerciais.

| Campo | Tipo | Descrição | Escala de Confiança |
|-------|------|-----------|--------------------|
| `id` | Integer | PK serial | 🟢 CONFIRMADO |
| `cliente_id` | Integer | FK Cliente | 🟢 CONFIRMADO |
| `orcamento_estado_id`| Integer | FK Status (Aberto, Ganho, Perdido)| 🟢 CONFIRMADO |
| `valor_total` | Numeric | Soma dos itens | 🟢 CONFIRMADO |
| `pedido_id` | Integer | FK Pedido (gerado após conversão) | 🟢 CONFIRMADO |

## Tabela: `orcamento_item`
Itens detalhados da proposta.

| Campo | Tipo | Descrição | Escala de Confiança |
|-------|------|-----------|--------------------|
| `orcamento_id` | Integer | FK Cabeçalho | 🟢 CONFIRMADO |
| `produto_id` | Integer | FK Produto | 🟢 CONFIRMADO |
| `quantidade` | Numeric | Qtd orçada | 🟢 CONFIRMADO |
| `preco_unit` | Numeric | Preço negociado | 🟢 CONFIRMADO |

## Tabelas Auxiliares de Venda
- **`tabela_preco`**: Define as listas de preços vigentes.
- **`tabela_preco_item`**: Valor de cada produto em cada tabela.
