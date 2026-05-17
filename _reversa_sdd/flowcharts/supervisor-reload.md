graph TD
    A[Selecionar Supervisor] --> B{Possui ID?}
    B -- Sim --> C[TCriteria: supervisor_id = selecionado]
    C --> D[TDBCombo::reloadFromModel: Vendedor]
    D --> E[Filtrar apenas subordinados]
    B -- Não --> F[TCombo::clearField: Vendedor]
    E --> G[Operador seleciona Vendedor subordinado]
    G --> H[Carregar KPIs da equipe]
