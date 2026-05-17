graph TD
    A[Cliente acessa LoginClienteForm] --> B[Digita CPF/CNPJ e Senha]
    B --> C[Query: ClienteAcesso login e md5 senha]
    C --> D{Encontrou?}
    D -- Não --> E[Mensagem: Login ou senha incorretos]
    D -- Sim --> F[Definir Sessão: cliente_logado=true, cliente_id]
    F --> G[Redirecionar MeuClienteFormView]
    G --> H[Verificar Permissão na Sessão]
    H -- OK --> I[Exibir Bem-vindo + Botões de Ação]
    I --> J[Meus Dados / Financeiro / Notas]
    H -- Negado --> K[Redirecionar para Login]
