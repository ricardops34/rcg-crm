graph TD
    A[Operador seleciona Títulos] --> B{Possui Títulos Selecionados?}
    B -- Não --> C[Alerta: Selecione ao menos os vencidos]
    B -- Sim --> D[Verificar títulos não selecionados no banco]
    D --> E{Existem outros títulos vencidos > 0 dias para o cliente?}
    E -- Sim --> F[Alerta: É obrigatório selecionar TODOS os títulos vencidos]
    E -- Não --> G[Exibir Confirmação de Gravação]
    G --> H{Confirma?}
    H -- Sim --> I[Criar Negociação: status = G, tipo = Cobrança]
    I --> J[Loop nos Títulos]
    J --> K[Criar NegociacaoTituloReceber]
    K --> L[Fim do Processo]
    H -- Não --> M[Cancela Operação]
