graph TD
    A[Acesso ao DashboardVendedor] --> B{Sessão: é Supervisor?}
    B -- Sim --> C[Habilitar Filtro: Vendedor Livre]
    B -- Não --> D[Travar Filtro para Vendedor Atual]
    C --> E[Selecionar Mês/Ano/Vendedor]
    D --> E
    E --> F[Carregar Indicadores BIndicator]
    F --> G[Query: ViewBaseClienteMes count -> Positivados]
    F --> H[Query: ViewBaseVenda sum -> Devoluções]
    F --> I[Query: ViewBaseClienteMes not in carteira -> Não Atendidos]
    E --> J[Carregar Gráficos BTableChart]
    J --> K[Query: Vendas Categoria vs Objetivo]
    K --> L[Exibir Painel Consolidado]
