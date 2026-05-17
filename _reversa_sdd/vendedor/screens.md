# Especificação de Telas: Módulo Vendedor

Este documento detalha os componentes e comportamentos das interfaces do módulo do Vendedor.

## 1. Dashboard Vendedor
**Propósito:** Painel operacional para o representante acompanhar sua própria performance e agenda.

- **Estado:** Preenchido.
- **Contexto:** Página inicial (Frontpage) do perfil Vendedor.
- **Elementos de Interface:**
    - **KPIs Pessoais:** Meu Atingimento, Minhas Vendas, Minha Sugestão de Venda.
    - **Agenda:** Próximos atendimentos e visitas agendadas.
    - **Filtros:** Travados para o vendedor logado.
- **Screenshot:** `screenshots/dashboad-vendedor.png`

## 2. MVC (Média de Venda do Cliente)
**Propósito:** Análise detalhada do comportamento de compra de um cliente específico.

- **Estado:** Listagem Analítica / Pivot.
- **Contexto:** Acessado via "Vendedor > MVC" ou link no Dashboard.
- **Elementos de Interface:**
    - **Tabela Pivot:** Exibição de valores faturados mês a mês (12 meses).
    - **Indicadores:** Valor Médio (3 meses), Diferença para a média, Primeira e Última compra.
    - **Navegação:** Link direto para a Position/Ficha do Cliente.
- **Screenshot:** `screenshots/mcv.png`
