# Supervisor — Casos de Borda (Edge Cases)

## 1. Vendedor Ativo sem Supervisor
- **Cenário:** Um vendedor é contratado, mas ninguém o adiciona à tabela `supervisor_vendedor`.
- **Comportamento Legado:** O vendedor consegue logar e trabalhar, mas nenhum supervisor conseguirá vê-lo nos dashboards de ranking ou equipe. Apenas administradores globais terão visão sobre sua performance. 🟢
- **Risco:** Falta de monitoramento sobre novos membros da equipe.

## 2. Deleção de Vínculo com Histórico Pendente
- **Cenário:** Um vendedor é removido da equipe do Supervisor A e movido para o Supervisor B no meio do mês.
- **Comportamento Legado:** As metas e vendas já realizadas no mês podem "mudar de dono" se a View de BI utilizar apenas o vínculo *atual* da tabela de ligação. 🟡
- **Risco:** Inconsistência no faturamento mensal por supervisor. Recomenda-se tratar vínculos com data de início e fim no rebuild.

## 3. Conflito de Usuário e Supervisor
- **Cenário:** O registro de `system_users` é deletado, mas o registro em `supervisor` permanece apontando para o ID inexistente.
- **Comportamento Legado:** O sistema pode lançar erro ao tentar resolver o nome do supervisor em listagens ou permitir que o registro fique "órfão". 🟢
- **Melhoria:** Implementar deleção em cascata ou (preferencialmente) inativação lógica de ambos os lados.

## 4. Recarregamento de Combo Vazio (AJAX)
- **Cenário:** O sistema invoca `onSupervisorid` para um supervisor que não possui nenhum vendedor vinculado.
- **Comportamento Legado:** O campo de seleção de Vendedor é limpo e fica sem opções, o que é o comportamento esperado. 🟢
- **Risco:** Se o frontend não tratar o estado vazio, pode causar travamento visual ou erro de script.
