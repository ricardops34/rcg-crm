graph TD
    A[Formulário ClienteForm] --> B[Aba: Dados Principais]
    A --> C[Aba: Contatos]
    A --> D[Aba: Classificação e Metas]
    B --> E{Validar Documento}
    E -- CNPJ --> F[Verificar na Base RFB]
    E -- CPF --> G[Validar Máscara]
    F --> H[Autopreencher Endereço/Razão]
    G --> I[Habilitar Campos]
    H --> I
    I --> J[Preencher Relacionamentos (Vendedor, Filial)]
    J --> K[Salvar]
    C --> K
    D --> K
    K --> L[TRecord: Cliente::store()]
    L --> M{ChangeLog Ativo?}
    M -- Sim --> N[SystemChangeLogTrait::register()]
    M -- Não --> O[Fim da Transação]
    N --> O
