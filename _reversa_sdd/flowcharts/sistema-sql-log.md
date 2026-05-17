graph TD
    A[Qualquer Query SQL] --> B{Log SQL Ativo?}
    B -- Sim --> C[Capturar SQL Command]
    C --> D[Capturar Stack Trace PHP]
    D --> E[Obter IP e Usuário]
    E --> F[Inserir em system_sql_log no banco log]
    F --> G[Continuar Execução]
    B -- Não --> G
