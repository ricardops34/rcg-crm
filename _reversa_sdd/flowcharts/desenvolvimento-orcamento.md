graph TD
    A[Usuário abre OrcamentoForm] --> B[Selecionar Cliente via SeekButton]
    B --> C[Autopreencher Vendedor/Tabela/Condição]
    C --> D[Adicionar Itens via SeekButton Produto]
    D --> E[Cálculo automático: Unitario * Qtd - Desconto]
    E --> F[Salvar Orçamento]
    F --> G{Status: Ganho?}
    G -- Sim --> H[Ação: Converter em Pedido de Venda]
    G -- Não --> I[Manter em acompanhamento]
    H --> J[Gerar Pedido no banco legado]
