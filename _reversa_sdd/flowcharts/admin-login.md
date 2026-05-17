graph TD
    A[Início Login] --> B{Validar reCAPTCHA?}
    B -- Sim --> C[Verificar Google reCAPTCHA]
    B -- Não --> D[Autenticar Usuário]
    C --> D
    D --> E{Multi-unit Ativo?}
    E -- Sim --> F{Unidade Selecionada?}
    F -- Não --> G[Exibir Diálogo de Unidade]
    F -- Sim --> H{Termos Aceitos?}
    G --> F
    E -- Não --> H
    H -- Não --> I[Exibir Termos de Uso]
    I --> J{Aceitou?}
    J -- Sim --> K[Registrar Aceite]
    K --> L{2FA Ativo?}
    H -- Sim --> L
    L -- Sim --> M{Tipo de 2FA?}
    M -- E-mail --> N[Enviar Código E-mail]
    M -- Google --> O[Validar Google Auth]
    N --> P[Exibir Form 2FA]
    P --> Q{Código Válido?}
    Q -- Sim --> R[Carregar Sessão]
    O -- Sim --> R
    L -- Não --> R
    R --> S[Registrar Log Acesso]
    S --> T[Redirecionar Frontpage]
