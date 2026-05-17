# Cobrança — Casos de Borda (Edge Cases)

Detalhamento de comportamentos financeiros em situações críticas.

## 1. Título Vencido que Acabou de ser Pago (Race Condition)
- **Cenário:** O operador abre a tela de cobrança para o cliente X. Enquanto ele seleciona os títulos, a baixa bancária (CNAB) ocorre e zera o saldo de um dos títulos.
- **Comportamento Legado:** Ao clicar em "Gerar", o sistema faz uma nova contagem no banco. Se o saldo não for mais > 0, o título pode ser ignorado ou causar erro de integridade na FK. 🟡
- **Recomendação:** Revalidar o saldo individual de cada título selecionado dentro da transação `onYes`.

## 2. Cliente com Vários Vendedores
- **Cenário:** Um cliente possui faturas vinculadas a diferentes vendedores (vendedor1_id vs vendedor2_id).
- **Comportamento Legado:** A `Negociacao` agrupa todos os títulos do cliente sob uma única transação, independente do vendedor original da fatura. A comissão do acordo pode ser um ponto de conflito. 🟢
- **Risco:** Falta de clareza sobre quem "resolveu" a inadimplência.

## 3. Títulos com Vencimento no Fim de Semana
- **Cenário:** O vencimento original (`venc_orig`) cai em um domingo.
- **Comportamento Legado:** O sistema utiliza a coluna `venc_real` para todos os cálculos de atraso. Essa coluna já vem ajustada pelo processo de faturamento para o próximo dia útil. 🟢
- **Impacto:** O atraso só começa a contar se `curdate() > venc_real`.

## 4. Negociação de Títulos com Situações Especiais
- **Cenário:** Título em situação '5' (Advogado) ou '6' (Judicial).
- **Comportamento Legado:** O sistema exibe esses títulos no grid `ViewTituloCliente` com o portador identificado, mas permite a seleção para uma nova negociação administrativa se o saldo for > 0. 🟡
- **Risco:** Conflito de fluxos (administrativo vs jurídico).
