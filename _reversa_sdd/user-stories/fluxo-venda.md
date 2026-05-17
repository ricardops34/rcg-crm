# User Story: Fluxo de Venda Consultiva (CRM -> Orçamento)

Como **Vendedor**, desejo realizar um atendimento consultivo baseado no histórico do cliente para converter um orçamento em venda.

## Critérios de Aceite

1. **Análise de Oportunidade:** Ao visitar um cliente, o vendedor deve acessar a ficha 360 (`PosisaoClienteFormView`) e identificar a média de compra (`MVC`). 🟢
2. **Registro de Contato:** Deve-se registrar o início do atendimento no calendário CRM (`AtendimentoCalendarForm`). 🟢
3. **Cotação Inteligente:** Ao abrir o `OrcamentoForm`, o sistema deve carregar automaticamente a tabela de preços do cliente selecionado. 🟢
4. **Negociação de Preços:** O vendedor pode aplicar descontos nos itens, respeitando as travas de permissão (Admin/Supervisor). 🟡
5. **Conversão:** Após a aprovação do cliente, o vendedor altera o status para "Ganho", o que dispara (manual ou automaticamente) a criação do Pedido no ERP. 🟢

## Fluxo de Valor
`Atendimento (CRM)` -> `Média de Venda (MVC)` -> `Orçamento (Master-Detail)` -> `Faturamento (NFe)`.
