# ERD — rcg (ERP Online)

Mapeamento completo dos relacionamentos do banco de dados `erp_online`.

```mermaid
erDiagram
    %% Núcleo Comercial
    VENDEDOR ||--o{ CLIENTE : "atende"
    SUPERVISOR ||--o{ SUPERVISOR_VENDEDOR : "lidera"
    VENDEDOR ||--o{ SUPERVISOR_VENDEDOR : "membro"
    CLIENTE ||--o{ ATENDIMENTO : "recebe"
    VENDEDOR ||--o{ ATENDIMENTO : "realiza"
    CLIENTE ||--o{ CLIENTE_CONTATO : "possui"
    CLIENTE ||--o{ CLIENTE_SOCIOS : "tem"
    
    %% Vendas e Orçamentos
    CLIENTE ||--o{ ORCAMENTO : "solicita"
    ORCAMENTO ||--o{ ORCAMENTO_ITEM : "contém"
    PRODUTO ||--o{ ORCAMENTO_ITEM : "item de"
    ORCAMENTO ||--o| PEDIDO : "converte para"
    PEDIDO ||--o{ PEDIDO_ITEM : "contém"
    PRODUTO ||--o{ PEDIDO_ITEM : "item de"
    PEDIDO ||--o| NOTA_SAIDA : "fatura"
    NOTA_SAIDA ||--o{ NOTA_SAIDA_ITEM : "contém"
    
    %% Catálogo e Estoque
    CATEGORIA ||--o{ SUB_CATEGORIA : "agrupa"
    SUB_CATEGORIA ||--o{ PRODUTO : "classifica"
    FABRICANTE ||--o{ PRODUTO : "produz"
    PRODUTO ||--o{ ESTOQUE : "tem saldo"
    ARMAZEM ||--o{ ESTOQUE : "armazena"
    TABELA_PRECO ||--o{ TABELA_PRECO_ITEM : "define"
    PRODUTO ||--o{ TABELA_PRECO_ITEM : "preço em"

    %% Financeiro
    CLIENTE ||--o{ TITULO_RECEBER : "deve"
    NOTA_SAIDA ||--o{ TITULO_RECEBER : "gera fatura"
    TITULO_RECEBER ||--o{ NEGOCIACAO_TITULO_RECEBER : "incluído"
    NEGOCIACAO ||--o{ NEGOCIACAO_TITULO_RECEBER : "agrupa"
    CLIENTE ||--o{ NEGOCIACAO : "negocia dívida"
    
    %% Localização
    ESTADO ||--o{ MUNICIPIO : "contém"
    MUNICIPIO ||--o{ CLIENTE : "sede"
```

## Resumo Estrutural
- **Total de Tabelas:** ~60 físicas + ~30 views.
- **Banco Principal:** PostgreSQL (conforme DDL fornecido).
- **Padronização:** Chaves primárias via `SERIAL` (integers auto-incremento), auditoria via campos `dt_inclusao` e `dt_alteracao` em quase todas as tabelas.
