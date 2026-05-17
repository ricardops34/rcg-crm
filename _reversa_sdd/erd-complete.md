erDiagram
    %% Segurança e Usuários
    SYSTEM_USERS ||--o{ SYSTEM_USER_GROUP : "pertence a"
    SYSTEM_GROUP ||--o{ SYSTEM_USER_GROUP : "contém"
    SYSTEM_USERS ||--o{ SYSTEM_USER_UNIT : "tem acesso"
    SYSTEM_UNIT ||--o{ SYSTEM_USER_UNIT : "vinculada a"
    SYSTEM_GROUP ||--o{ SYSTEM_GROUP_PROGRAM : "possui"
    SYSTEM_PROGRAM ||--o{ SYSTEM_GROUP_PROGRAM : "atribuído a"

    %% Comercial e Força de Vendas
    SUPERVISOR ||--o{ SUPERVISOR_VENDEDOR : "lidera"
    VENDEDOR ||--o{ SUPERVISOR_VENDEDOR : "membro de"
    VENDEDOR ||--o{ CLIENTE : "atende"
    VENDEDOR ||--o{ ATENDIMENTO : "realiza"
    CLIENTE ||--o{ ATENDIMENTO : "recebe"
    VENDEDOR ||--o{ META_VENDEDOR_MES : "possui"
    META_VENDEDOR_MES ||--o{ META_VENDEDOR_CATEGORIA : "desdobrada em"

    %% Vendas e Orçamentos
    CLIENTE ||--o{ ORCAMENTO : "solicita"
    ORCAMENTO ||--o{ ORCAMENTO_ITEM : "contém"
    ORCAMENTO ||--o| PEDIDO : "converte para"
    PEDIDO ||--o{ NOTA_SAIDA : "gera"

    %% Financeiro e Cobrança
    CLIENTE ||--o{ TITULO_RECEBER : "deve"
    TITULO_RECEBER ||--o{ NEGOCIACAO_TITULO_RECEBER : "incluído em"
    NEGOCIACAO ||--o{ NEGOCIACAO_TITULO_RECEBER : "agrupa"
    CLIENTE ||--o{ NEGOCIACAO : "negocia"
    CLIENTE ||--o| CLIENTE_ACESSO : "possui login B2B"

    %% Estrutura das Tabelas Principais (Atributos)
    CLIENTE {
        int id PK
        string cod_erp
        string razao
        string cnpj_cpf
        char status
    }
    ORCAMENTO {
        int id PK
        date emissao
        numeric valor_total
        int orcamento_estado_id FK
    }
    TITULO_RECEBER {
        int id PK
        string numero
        date venc_real
        numeric saldo
        char situacao
    }
    META_VENDEDOR_MES {
        int id PK
        int mes
        int ano
        numeric valor
    }
