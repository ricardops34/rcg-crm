# Supervisor, Design Técnico

## Interface

### Models e Componentes Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `Supervisor` | Active Record | - | Cadastro mestre do supervisor e vínculo com Login. 🟢 |
| `SupervisorVendedor` | Active Record | - | Tabela de ligação N:N para formação de equipe. 🟢 |
| `DashboardSupervisor` | TPage | `HTML` | Painel de monitoramento dinâmico por supervisor. 🟢 |
| `onSupervisorid` | `($param)` | `void` | Handler AJAX para recarregamento de combos de vendedor. 🟢 |

## Fluxo Principal (Filtragem Dinâmica de Equipe)

Este fluxo é fundamental para a usabilidade e segurança do escopo de dados do supervisor.

1. **Acesso:** O usuário abre o `DashboardSupervisor`.
2. **Seleção Inicial:** O sistema carrega o combo de Supervisores. Se for um usuário tipo `Admin`, exibe todos; se for `Supervisor`, pode já vir pré-selecionado. 🟡
3. **Trigger AJAX:** Ao selecionar um Supervisor, o sistema dispara a ação `onSupervisorid`. 🟢
4. **Lógica de Recarregamento:**
    - O sistema cria um `TCriteria` filtrando `SupervisorVendedor` pelo ID do supervisor selecionado.
    - Invoca `TDBCombo::reloadFromModel` apontando para o model `Vendedor`. 🟢
5. **Aplicação:** O campo de Vendedor é atualizado visualmente, listando apenas os membros autorizados daquela equipe específica.

## Fluxo de Cálculo de Saúde da Carteira

Lógica executada por demanda para cada membro da equipe listado no dashboard.

1. **Invocação:** O `DashboardSupervisor` (ou o model `SupervisorVendedor`) percorre os vendedores da equipe.
2. **Contagem de Ativos:** Executa query filtrando `vendedor_id` e `status = 'A'`. 🟢
3. **Contagem de Bloqueados:** Executa query filtrando `vendedor_id` e `status = 'B'`. 🟢
4. **Consolidação:** Os valores são agregados e exibidos em colunas específicas no dashboard do supervisor.

## Dependências

- **Adianti TDBCombo:** Componente central para o fluxo de recarregamento AJAX. 🟢
- **Active Record Relational Mapping:** O sistema utiliza intensamente as relações entre `Supervisor` -> `SupervisorVendedor` -> `Vendedor`. 🟢
- **SystemUsers:** Vínculo obrigatório para permitir o login do supervisor no ERP. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Abstração de Lista de Nomes (CSV) | `app/model/Supervisor.php:getIndexedArray` | 🟢 |
| Preservação de Histórico (desligado) | `app/model/Supervisor.php:addAttribute('desligado')` | 🟢 |
| Auditoria de Criação de Equipe | `app/model/SupervisorVendedor.php:CREATEDAT` | 🟢 |

## Estado Interno

- **Hierarchy State:** O sistema mantém o estado da equipe na tabela de ligação. Alterações nesta tabela refletem imediatamente em todos os filtros do sistema administrativo.

## Observabilidade

- Nenhuma estratégia específica de telemetria identificada além dos logs globais de SQL e Acesso.

## Riscos e Lacunas

- 🔴 **Vínculos Múltiplos:** A tabela `supervisor_vendedor` é uma tabela de ligação N:N, o que tecnicamente permite que um vendedor tenha dois supervisores. Requer validação se a regra de negócio do rebuild deve impor subordinação 1:N (um vendedor -> um supervisor) ou manter a flexibilidade técnica.
- 🟡 **Exclusão de Supervisor:** O código implementa deleção lógica (`dt_delete`) em alguns models, mas não ficou claro se o supervisor possui esta trava ou se é uma deleção física.
