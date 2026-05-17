# Admin, Design Técnico

## Interface

### Endpoints / Classes Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `LoginForm.onLogin` | `($param: array)` | `void` | Processa a submissão do formulário de login. 🟢 |
| `SystemUsers.validate` | `($login, $password)` | `SystemUsers` | Valida credenciais e verifica status do usuário. 🟢 |
| `SystemUsers.authenticate`| `($login, $password)` | `SystemUsers` | Executa a lógica de hash (Bcrypt/MD5) e rehash. 🟢 |
| `System2FAForm.onCheck` | `($param: array)` | `void` | Valida o código de segundo fator. 🟢 |
| `TwoFactorEmailService` | Service Class | - | Motor de geração e envio de tokens por e-mail. 🟢 |
| `LdapAuthenticationService`| Service Class | - | Provedor opcional para autenticação via LDAP. 🟢 |
| `ApplicationAuthenticationService`| Service Class | - | Camada de abstração para múltiplos provedores de login. 🟢 |

## Fluxo Principal (Autenticação)

1. **Início:** O usuário acessa `LoginForm`.
2. **Submissão:** Ao clicar em login, invoca `onLogin`.
3. **reCAPTCHA:** Se ativo nas configurações, valida o token junto ao Google. `app/control/admin/LoginForm.php:120`. 🟢
4. **Validação Básica:** Chama `SystemUsers::validate($login, $password)`. 🟢
    - Se falhar, lança exceção e limpa o campo de senha.
5. **Rehash Transparente:** Dentro de `validate`, chama `authenticate` que aplica a lógica do ADR 001 (MD5 -> Bcrypt). 🟢
6. **Multi-Unidade:** Verifica `ini['general']['multiunit']`. Se ativo e `system_unit_id` não estiver na sessão, redireciona para escolha de unidade. 🟢
7. **Termos de Uso:** Verifica `accepted_term_policy`. Se 'N', redireciona para formulário de aceite. 🟢
8. **2FA:** Verifica se `two_factor_enabled` é 'Y'. Se sim, despacha código (E-mail) ou aguarda TOTP (Google) na tela de desafio. 🟢
9. **Sessão:** Carrega permissões do usuário em `TSession` e registra o acesso via `SystemAccessLogService`. 🟢

## Fluxos Alternativos

- **Logout:** Invocado por `LoginForm::onLogout`, limpa a sessão e registra o log de saída. 🟢
- **Esqueci a Senha:** Fluxo de recuperação via e-mail configurado em `SystemUsers` (indica uso de SMTP via `phpmailer`). 🟡

## Dependências

- **Adianti Framework:** Core da aplicação (Persistência, Sessão, UI). 🟢
- **NFePHP / OpenBoleto:** (Indireto) Usados após login para funções fiscais. 🟢
- **Google reCAPTCHA / Authenticator:** Serviços externos de segurança. 🟢
- **PHPMailer:** Para envio de tokens 2FA e recuperação de senha. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Migração orgânica de hashes | `app/model/admin/SystemUsers.php` | 🟢 |
| Controle de sessão centralizado em TSession | `app/control/admin/LoginForm.php` | 🟢 |
| RBAC via grupos e programas | `app/model/admin/SystemUsers.php:getPrograms` | 🟢 |

## Estado Interno

O módulo `admin` mantém o estado da aplicação na sessão (`TSession`):
- `userid`: ID do usuário logado.
- `userunitid`: ID da unidade ativa.
- `usergroupids`: Array de grupos do usuário.
- `programs`: Mapa de permissões para cada Controller.

## Observabilidade

- **Logs de Acesso:** Registrados na tabela `system_access_log` no banco `log`. 🟢
- **Logs de Notificação:** Registrados em `system_access_notification_log`. 🟢

## Riscos e Lacunas

- 🔴 **Reset de Senha:** A lógica de geração do token de reset não foi totalmente mapeada nos models analisados.
- 🟡 **Single Session:** A flag `single_session` em `application.ini` sugere um controle de concorrência que requer validação se derruba sessões via banco ou filesystem.
