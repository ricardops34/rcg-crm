# Supervisor, Tarefas de Implementação

## Pré-requisitos
- [ ] Módulo `admin` configurado para autenticação de supervisores.
- [ ] Banco de dados `erp_online` com suporte a tabelas de ligação N:N.
- [ ] Driver de UI (React/Vue/Angular) com suporte a eventos de mudança em selects.

## Tarefas

- [ ] T-01, Implementar Cadastro de Supervisor e Login
  - Origem no legado: `app/model/Supervisor.php`
  - Critério de pronto: Vincular um registro de supervisor a um usuário administrativo, permitindo a identificação da liderança no login.
  - Confiança: 🟢

- [ ] T-02, Criar Mecanismo de Vinculação de Equipe (N:N)
  - Origem no legado: `app/model/SupervisorVendedor.php`
  - Critério de pronto: Implementar a tabela de junção que define quais vendedores pertencem a qual supervisor.
  - Confiança: 🟢

- [ ] T-03, Desenvolver Filtro de Equipe Dinâmico (AJAX)
  - Origem no legado: Lógica de `onSupervisorid` no `DashboardSupervisor.php`
  - Critério de pronto: Ao selecionar um supervisor, o sistema deve filtrar automaticamente os vendedores disponíveis nos campos de busca e relatórios.
  - Confiança: 🟢

- [ ] T-04, Implementar Métricas de Saúde da Carteira Subordinada
  - Origem no legado: Métodos `get_clientes_ativos` e `get_clientes_bloqueados`
  - Critério de pronto: Função que agrega a contagem de clientes por status para cada membro da equipe do supervisor.
  - Confiança: 🟢

- [ ] T-05, Criar Dashboard do Supervisor (Painel Operacional)
  - Origem no legado: `app/control/supervisor/DashboardSupervisor.php`
  - Critério de pronto: Painel que consolida os KPIs da equipe liderada em uma única visão.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Validar se um vendedor recém-adicionado a uma equipe aparece no filtro do supervisor imediatamente.
- [ ] TT-02, Testar a inativação (desligamento) de um supervisor e garantir que ele suma das opções de filtro ativo.
- [ ] TT-03, Verificar se um administrador (sem supervisor_id) consegue ver todos os vendedores sem restrição.
- [ ] TT-04, Validar se a contagem de clientes ativos bate com a soma individual dos cadastros de cada vendedor.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar a tabela `supervisor` e os vínculos de `supervisor_vendedor` garantindo a integridade dos IDs.

## Ordem Sugerida
1. T-01 e T-02: Definem a estrutura de dados hierárquica.
2. T-03: Implementação da lógica de UI/UX para navegação.
3. T-04 e T-05: Telas de monitoramento.

## Lacunas Pendentes (🔴)
- Definir se a hierarquia pode ter múltiplos níveis (ex: Gerente Nacional -> Gerente Regional -> Supervisor -> Vendedor) ou se manterá o modelo plano atual.
