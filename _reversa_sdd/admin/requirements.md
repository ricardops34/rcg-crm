# Admin — Requisitos

## Visão Geral
Esta unit gerencia a segurança, autenticação e o Controle de Acesso Baseado em Funções (RBAC). É o núcleo de governança do sistema, garantindo que usuários possuam as credenciais corretas e acessem apenas os dados e funcionalidades permitidos para o seu papel e unidade.

## Responsabilidades
- Autenticação centralizada de usuários administrativos. 🟢
- Controle de acesso multi-unidade (filiais). 🟢
- Gestão de perfis (Grupos) e permissões de programas. 🟢
- Implementação de segurança de segundo fator (2FA). 🟢
- Auditoria de acessos e aceites de termos de uso. 🟢

## Regras de Negócio
- **Validação de Usuário:** Apenas usuários com status 'Ativo' podem logar. 🟢
- **Segurança de Senha:** O sistema deve suportar hashes Bcrypt e realizar a migração automática de hashes legados (MD5) no momento do login. 🟢 (Ref: ADR 001)
- **Multi-unidade:** Se o sistema for multi-unit, o usuário deve selecionar a unidade de trabalho após a autenticação bem-sucedida. 🟢
- **Termos de Uso:** O usuário é obrigado a aceitar a política de privacidade/termos de uso antes do primeiro acesso. 🟢
- **2FA Obrigatório:** Se configurado, o sistema deve exigir um código de verificação via E-mail ou Google Authenticator antes de liberar a sessão. 🟢
- **Single Session:** O sistema pode restringir o login a apenas uma sessão ativa por vez (opcional via parâmetro). 🟡

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Login de Usuário | Must | Validar login/senha e iniciar sessão Adianti. |
| RF-02 | Rehash de Senha | Must | Converter MD5 para Bcrypt de forma transparente no login. |
| RF-03 | Seleção de Unidade | Must | Bloquear acesso até que uma unidade válida seja escolhida em sistemas multiunit. |
| RF-04 | Validação de 2FA | Should | Solicitar token se o usuário tiver 2FA habilitado. |
| RF-05 | Registro de Aceite | Should | Armazenar data/hora e IP do aceite dos termos de uso. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Segurança | Criptografia Bcrypt para senhas | `app/model/admin/SystemUsers.php` | 🟢 |
| Segurança | Proteção contra força bruta (reCAPTCHA) | `app/control/admin/LoginForm.php` | 🟢 |
| Performance | Carregamento de permissões em cache de sessão | `app/control/admin/LoginForm.php` | 🟢 |
| Segurança | Suporte a 2FA (RFC 6238 / TOTP) | `app/control/admin/System2FAGoogleForm.php` | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Login bem-sucedido com migração de senha
Dado que o usuário "ricardo" possui senha em hash MD5
Quando ele submeter a senha correta no LoginForm
Então o sistema deve permitir o acesso
E o hash da senha no banco deve ser atualizado para Bcrypt automaticamente

Cenário: Bloqueio por status inativo
Dado que o usuário "antigo" está com status 'N'
Quando ele tentar logar
Então o sistema deve exibir mensagem "Usuário inativo" e impedir o acesso

Cenário: Exigência de 2FA
Dado que o usuário possui 2FA Google ativado
Quando ele realizar o login com sucesso
Então o sistema deve exibir a tela de desafio TOTP antes de abrir a Frontpage
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Autenticação Básica | Must | Porta de entrada do sistema. |
| RBAC (Permissões) | Must | Define o escopo de dados de todos os outros módulos. |
| Rehash de Senha | Should | Segurança evolutiva, mas o sistema funcionaria com hashes antigos. |
| 2FA | Should | Camada extra de segurança recomendada para ERPs. |
| ReCAPTCHA | Could | Proteção contra robôs, importante mas não impede o fluxo funcional. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/control/admin/LoginForm.php` | `LoginForm` | 🟢 |
| `app/model/admin/SystemUsers.php` | `SystemUsers` | 🟢 |
| `app/service/CustomAuthenticationService.php` | `onLogin` | 🟢 |
| `app/control/admin/System2FAForm.php` | `System2FAForm` | 🟢 |
