# Admin — Casos de Borda (Edge Cases)

Este documento descreve o comportamento do sistema em situações extremas ou não triviais identificadas no código.

## 1. Falha em Serviços Externos

### reCAPTCHA Fora do Ar
- **Cenário:** O sistema está configurado para usar reCAPTCHA, mas a API do Google não responde ou o token é inválido.
- **Comportamento Legado:** O sistema lança uma `Exception` e impede o login. 🟢
- **Risco no Rebuild:** Se não houver um fallback ou tratamento de timeout, o sistema pode ficar inacessível por falha de terceiros.

### Falha no Servidor de E-mail (2FA)
- **Cenário:** O usuário possui 2FA via e-mail ativo, mas o SMTP falha ao enviar o código.
- **Comportamento Legado:** O código é gerado no banco, mas a exceção do PHPMailer interrompe o fluxo, deixando o usuário preso na tela de login sem o código. 🟢
- **Sugestão de Melhoria:** Implementar fila de re-tentativa ou opção de código de backup.

## 2. Inconsistências de Dados

### Usuário sem Grupo (Perfil Vazio)
- **Cenário:** Um usuário é criado no banco `permission`, mas não é vinculado a nenhum `system_group`.
- **Comportamento Legado:** O login é permitido, mas o menu lateral e a Frontpage aparecem vazios (sem permissão de programas). 🟢
- **Risco:** O usuário pode achar que o sistema está quebrado.

### Hash de Senha Corrompido
- **Cenário:** O campo `password` contém um valor que não é MD5 válido nem Bcrypt.
- **Comportamento Legado:** O método `password_verify` retorna falso e o login falha silenciosamente ("Usuário ou senha incorretos"). 🟢

## 3. Concorrência e Sessão

### Single Session Ativo
- **Cenário:** O usuário "A" loga no navegador 1. Em seguida, loga no navegador 2.
- **Comportamento Legado:** O sistema Adianti, se configurado com `single_session = '1'`, invalida a sessão anterior (geralmente via arquivo de sessão no server). 🟡
- **Risco no Rebuild:** Em ambientes clusterizados (Docker/Kubernetes), isso exige persistência de sessão em Redis para funcionar corretamente.

## 4. Expiração de Termos de Uso
- **Cenário:** O sistema atualiza o conteúdo da política de privacidade.
- **Comportamento Legado:** O campo `accepted_term_policy` deve ser resetado para 'N' via script de banco (não há gatilho automático no código). No próximo login, o usuário é forçado a aceitar novamente. 🟡
