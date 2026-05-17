graph TD
    A[Botão: Gerar DANFE] --> B[Obter Chave da NFe]
    B --> C[TTransaction: open portal_erp]
    C --> D[Query: NotasaidaXml where chave]
    D --> E{Existe XML?}
    E -- Sim --> F[Salvar XML em app/output]
    F --> G[Instanciar NFePHP Danfe]
    G --> H[Renderizar PDF com Logo]
    H --> I[Salvar PDF em app/output]
    I --> J[TPage::openFile PDF]
    E -- Não --> K[Mensagem: Nota não localizada]
    J --> L[Redirecionar p/ Lista de Notas]
