# Meu Cliente, Tarefas de Implementação

## Pré-requisitos
- [ ] Banco de dados `erp_online` com a tabela `cliente_acesso` populada.
- [ ] Módulo `admin` configurado (opcional, para gerir os acessos via painel ADM).
- [ ] Views financeiras (`view_titulo_cliente`) migradas e funcionais.

## Tarefas

- [ ] T-01, Implementar Credenciais de Acesso B2B
  - Origem no legado: `app/model/ClienteAcesso.php`
  - Critério de pronto: Model capaz de representar o login e senha (vinculados ao cliente) com auditoria de alteração.
  - Confiança: 🟢

- [ ] T-02, Criar Serviço de Autenticação B2B
  - Origem no legado: `app/control/meu_cliente/LoginClienteForm.php`
  - Critério de pronto: Validar credenciais contra `cliente_acesso` e estabelecer uma sessão separada da administrativa.
  - Confiança: 🟢

- [ ] T-03, Desenvolver Painel de Autoatendimento
  - Origem no legado: `app/control/meu_cliente/PainelClienteForm.php`
  - Critério de pronto: Dashboard com links para consulta de dados, financeiro e notas, com trava de segurança obrigatória.
  - Confiança: 🟢

- [ ] T-04, Implementar Filtro Compulsório de Cliente
  - Origem no legado: Lógica de `TCriteria` aplicada nos repositórios do portal.
  - Critério de pronto: Garantir que todas as queries SQL do portal incluam automaticamente `AND cliente_id = logado`.
  - Confiança: 🟢

- [ ] T-05, Consulta de NFes e Boletos (Integração)
  - Origem no legado: Vinculação com o módulo de Relatórios.
  - Critério de pronto: Exibir PDF da DANFE e permitir acesso ao boleto bancário (via banco ou integração).
  - Confiança: 🟡

## Tarefas de Teste

- [ ] TT-01, Validar login com CPF e com CNPJ (se suportado).
- [ ] TT-02, Testar sequestro de sessão: tentar acessar dado de outro ID via URL deve falhar.
- [ ] TT-03, Verificar se o status `cliente_logado` é limpo corretamente no logout.
- [ ] TT-04, Simular alteração de senha e validar acesso com a nova credencial.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar logins e hashes da tabela `cliente_acesso`. Sugestão: realizar rehash para Bcrypt no momento da migração ou no primeiro login do novo sistema.

## Ordem Sugerida
1. T-01 e T-02: Base de acesso ao portal.
2. T-04: Requisito não funcional de segurança (Isolamento).
3. T-03 e T-05: Telas de funcionalidade.

## Lacunas Pendentes (🔴)
- Definir a estratégia de hashing de senhas: manter MD5 por retrocompatibilidade ou forçar reset de senhas para migrar para Bcrypt/Argon2.
