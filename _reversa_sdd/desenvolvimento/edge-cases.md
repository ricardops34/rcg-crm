# Desenvolvimento — Casos de Borda (Edge Cases)

## 1. Mudança de Cliente após Adição de Itens
- **Cenário:** O vendedor seleciona o "Cliente A" (Tabela Varejo), adiciona 5 itens ao orçamento. Em seguida, ele troca para o "Cliente B" (Tabela Atacado).
- **Comportamento Legado:** O grid de itens já incluídos na memória pode manter os preços antigos da Tabela Varejo, causando inconsistência no valor final da proposta. 🟡
- **Recomendação:** Ao alterar o cliente (ou a tabela de preço do cabeçalho), o sistema deve varrer o grid de itens e recalcular os preços ou forçar o usuário a aprovar a reprecificação.

## 2. Inconsistência de Tabela de Preço x Produto
- **Cenário:** O vendedor busca por um produto que não está cadastrado na tabela de preços vinculada ao orçamento atual.
- **Comportamento Legado:** O componente `ViewProdutoOrcamentoSeekWindow` baseia-se num `LEFT JOIN` ou exibe valores nulos. O sistema pode permitir a adição com preço 0.00. 🟢
- **Recomendação:** Implementar trava no SeekWindow para não exibir produtos que não possuam preço na tabela ativa.

## 3. Conversão Múltipla de Orçamento (Race Condition)
- **Cenário:** O orçamento é aprovado, e dois usuários (ou duplo clique) tentam alterar o status para "Ganho" simultaneamente.
- **Comportamento Legado:** Como não há trava explícita visível no PHP, isso pode gerar dois "Pedidos" para o mesmo orçamento no banco legado. 🔴
- **Risco no Rebuild:** Faturamento duplicado. Exige implementação de travamento otimista (optimistic locking) ou idempotência na conversão de estados.
