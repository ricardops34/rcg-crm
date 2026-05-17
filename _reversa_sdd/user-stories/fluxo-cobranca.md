# User Story: Fluxo de Recuperação de Crédito (Cobrança Ativa)

Como **Operador de Cobrança**, desejo identificar clientes inadimplentes e agrupar seus débitos em uma negociação formal.

## Critérios de Aceite

1. **Identificação:** O operador filtra a `NegociacaoList` por "Somente Vencidos" para priorizar contatos. 🟢
2. **Visualização de Alerta:** O sistema deve destacar faturas com atraso superior a 0 dias em amarelo/vermelho. 🟢
3. **Formalização de Acordo:** Ao selecionar os títulos, o sistema obriga a inclusão de todos os débitos vencidos para evitar "esquecimentos" propositais. 🟢
4. **Registro de Negociação:** A gravação gera um novo cabeçalho de negociação (Status 'G') vinculado aos títulos selecionados. 🟢
5. **Acompanhamento B2B:** O cliente final deve conseguir visualizar o status da sua negociação no portal `meu_cliente`. 🟡

## Fluxo de Valor
`View Títulos Atrasados` -> `Seleção Obrigatória de Vencidos` -> `Geração de Negociação` -> `Acompanhamento B2B`.
