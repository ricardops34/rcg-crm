# Admin — Fluxos Detalhados

Este documento detalha as interações e decisões em fluxos complexos da unit Admin.

## 1. Fluxo de Decisão de Unidade (Multi-unit)

Este fluxo ocorre após a validação de login e antes do carregamento do Dashboard.

```mermaid
graph TD
    A[Autenticação OK] --> B{Config: multiunit?}
    B -- Não --> C[Carregar Unidade Padrão]
    B -- Sim --> D{Sessão tem unit_id?}
    D -- Sim --> E[Validar Acesso à Unidade]
    D -- Não --> F[Buscar Unidades do Usuário]
    F --> G{Qtd Unidades?}
    G -- 1 --> H[Auto-selecionar e Salvar na Sessão]
    G -- >1 --> I[Exibir Tela: Seleção de Unidade]
    I --> J[Usuário Escolhe]
    J --> H
    H --> K[Redirecionar Frontpage]
    E -- Erro --> I
```

## 2. Fluxo de Desafio 2FA (Google Authenticator)

```mermaid
sequenceDiagram
    participant U as Usuário
    participant L as LoginForm
    participant S as System2FAGoogleForm
    participant A as Google Auth App
    
    U->>L: Submete Login/Senha
    L->>L: Valida Credenciais
    L-->>U: Redireciona para Desafio 2FA
    U->>A: Abre App no Celular
    A-->>U: Exibe Código de 6 dígitos
    U->>S: Digita Código
    S->>S: Valida via biblioteca TOTP
    alt Código Válido
        S-->>U: Inicia Sessão e Frontpage
    else Código Inválido
        S-->>U: Mensagem de Erro (Tente novamente)
    end
```

## 3. Fluxo de Aceite de Termos e Privacidade

Ocorre apenas se `accepted_term_policy == 'N'`.

1. **Intercepção:** Após o login, se a flag estiver negativa, o sistema redireciona para `SystemTermsService`.
2. **Visualização:** O sistema carrega o texto dos termos (armazenado em `system_preference` ou arquivo local).
3. **Ação:** O usuário clica em "Aceito e Desejo Continuar".
4. **Persistência:** 
    - Atualiza `system_users.accepted_term_policy = 'Y'`.
    - Grava `accepted_term_policy_at = NOW()`.
    - Registra log de IP do aceite. 🟢
5. **Liberação:** Segue para o fluxo de Multi-unidade ou Frontpage.
