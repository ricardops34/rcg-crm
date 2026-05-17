# Vendedor — Casos de Borda (Edge Cases)

## 1. Vendedor sem Vínculo de ID de Sessão
- **Cenário:** Um usuário possui perfil "Vendedor" no RBAC, mas seu cadastro em `vendedor` (tabela de negócio) não foi realizado ou vinculado.
- **Comportamento Legado:** O dashboard exibe valores zerados e os grids de clientes ficam vazios (devido ao `TSession::getValue("vendedor_id")` retornar nulo no TCriteria). 🟢
- **Risco:** O sistema "parece" quebrado para o usuário novo. Recomendação: exibir mensagem de erro clara ("Vendedor não vinculado").

## 2. Cliente com Venda Superior à Meta da Categoria
- **Cenário:** O vendedor bate 200% da meta de uma categoria específica, mas está com 50% na meta global.
- **Comportamento Legado:** Os gráficos de barra de progresso (TableChart) exibem o estouro visualmente (barra cheia ou ultrapassando o limite). 🟢
- **Impacto:** O sistema permite faturar infinitamente além da meta; a meta é apenas um indicador de referência (benchmark), não uma trava de faturamento.

## 3. Atendimento Retroativo ou Futuro
- **Cenário:** O vendedor tenta registrar uma visita ocorrida há 30 dias ou agendar para o ano que vem.
- **Comportamento Legado:** O formulário valida apenas a obrigatoriedade dos campos de data/hora. Não há trava de "período permitido" para registros CRM. 🟡
- **Risco:** Dados históricos inconsistentes (vendedor "ajeitando" a agenda do passado).

## 4. Cliente Inativo na Lista MVC
- **Cenário:** Um cliente não compra há 12 meses e está com status 'B'.
- **Comportamento Legado:** O sistema exibe o cliente na lista MVC se ele possuir qualquer histórico no ano fiscal selecionado. 🟢
- **Sugestão:** Adicionar filtro "Ocultar Bloqueados" para focar em clientes recuperáveis.
