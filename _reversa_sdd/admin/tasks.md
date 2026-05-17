# Admin, Tarefas de Implementação

## Pré-requisitos
- [ ] Banco de dados `permission` e `log` criados e acessíveis.
- [ ] Biblioteca de criptografia (ex: `bcrypt` ou equivalente na linguagem alvo) disponível.
- [ ] Serviço de cache ou sessão (Redis/Memcached ou nativo) configurado.
- [ ] Integração com serviço de envio de e-mails (SMTP) para 2FA.

## Tarefas

- [ ] T-01, Implementar serviço de Autenticação com rehash automático
  - Origem no legado: `app/model/admin/SystemUsers.php:validate()` e `authenticate()`
  - Critério de pronto: O sistema deve aceitar hashes MD5 legados, validar e atualizar o banco para o novo padrão de hash (Bcrypt) no primeiro login.
  - Confiança: 🟢

- [ ] T-02, Criar fluxo de Login com suporte a reCAPTCHA
  - Origem no legado: `app/control/admin/LoginForm.php:onLogin()`
  - Critério de pronto: Validar credenciais, checar status do usuário e validar token reCAPTCHA se ativo.
  - Confiança: 🟢

- [ ] T-03, Implementar Segurança de Segundo Fator (2FA)
  - Origem no legado: `app/control/admin/System2FAForm.php` e `System2FAGoogleForm.php`
  - Critério de pronto: Solicitar e validar token (E-mail ou Google TOTP) após o login básico bem-sucedido.
  - Confiança: 🟢

- [ ] T-04, Gestão de Sessão e Permissões (RBAC)
  - Origem no legado: `app/model/admin/SystemUsers.php:getPrograms()`
  - Critério de pronto: Carregar todos os programas/funcionalidades permitidos para os grupos do usuário logado na sessão ativa.
  - Confiança: 🟢

- [ ] T-05, Implementar Seleção de Unidade (Multiunit)
  - Origem no legado: `app/control/admin/LoginForm.php:145` (Fluxo de escolha de unidade)
  - Critério de pronto: Em sistemas multiunit, exigir a escolha de uma unidade válida antes de liberar o acesso à Frontpage.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Teste de login com senha MD5 (garantir migração transparente).
- [ ] TT-02, Teste de bloqueio de usuário inativo.
- [ ] TT-03, Teste de bypass/obrigatoriedade de 2FA conforme perfil.
- [ ] TT-04, Teste de expiração de sessão e limpeza de permissões.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar tabelas `system_users`, `system_group` e `system_program` do banco `permission` legado.

## Ordem Sugerida
1. T-01 e T-02: Base de qualquer acesso ao sistema.
2. T-04: Necessário para que os demais módulos filtrem seus dados corretamente.
3. T-03 e T-05: Camadas de segurança e escopo.

## Lacunas Pendentes (🔴)
- Validar se a lógica de `single_session` deve ser mantida via filesystem (como no legado) ou evoluir para persistência em banco/Redis.
