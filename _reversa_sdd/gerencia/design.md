# Gerência, Design Técnico

## Interface

### Models e Views de BI Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `DashboardGerencia` | TPage | `HTML` | Painel centralizador de KPIs estratégicos. 🟢 |
| `MetaVendedorMes` | Active Record | - | Cabeçalho de meta mensal por vendedor. 🟢 |
| `ViewVendedorVendaMes` | View BI | - | Consolida faturamento real vs objetivo (meta). 🟢 |
| `PivotVendaMesCliente` | View BI | - | Pivot SQL de faturamento cliente x mês. 🟢 |

## Fluxo Principal (Gestão de Metas)

1. **Definição Global:** O gestor acessa `MetaVendedorMesList` e cria uma meta.
2. **Mestre:** Informa Vendedor, Mês, Ano e o Valor Financeiro total desejado. 🟢
3. **Detalhamento (Opcional):** Através do `MetaVendedorMesForm`, pode adicionar itens ao grid de categorias.
    - Cada item vincula uma `Categoria` a um valor parcial.
4. **Validação:** No salvamento, o sistema não exige que a soma das categorias bata com o total mestre (flexibilidade operacional). 🟢
5. **Acompanhamento:** No `DashboardGerencia`, o sistema consulta a `ViewVendedorVendaMes` que realiza o cálculo em tempo real:
    - `atingimento = (faturamento_liquido * 100) / valor_objetivo`. 🟢

## Fluxo de Relatório Pivot (Evolução de Venda)

Este fluxo demonstra como o sistema extrai tendências de longo prazo.

1. **Invocação:** `VendaMesClienteList` solicita dados para o banco.
2. **Camada SQL:** A view `pivot_venda_mes_cliente` realiza um `SUM(IF(...))` para cada mês de 01 a 12. 🟢
3. **Pivoteamento:** Linhas do banco `venda_mes_vendedor` são transformadas em colunas no result-set.
4. **Exibição:** O grid Adianti mapeia cada coluna (janeiro, fevereiro...) para uma célula da tabela, permitindo visualização horizontal. 🟢

## Dependências

- **Adianti BTableChart / BIndicator:** Componentes visuais para Dashboards. 🟢
- **PostgreSQL Views:** Toda a "inteligência" de cálculo estratégica reside nas Views de banco de dados. 🟢
- **SystemUsers Session:** O filtro de `supervisor_id` e `admin` define quais vendedores o usuário pode gerenciar. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Bloqueio de exclusão com dependentes | `app/model/MetaVendedorMes.php:onBeforeDelete` | 🟢 |
| BI movido para a camada de Banco | `_reversa_sdd/database/business-rules.md` (Views Pivot) | 🟢 |
| Gestão de Representantes via Model Vendedor | `app/model/Vendedor.php` | 🟢 |

## Estado Interno

O módulo de gerência é essencialmente analítico (Read-Only) em seus dashboards, mas mantém o estado das metas:
- `MetaVendedorMes`: Status ativo até que o período expire.
- `Vendedor`: Status `Ativo` / `Bloqueado` / `Desligado`.

## Observabilidade

- **Ranking de Atingimento:** Monitorado visualmente via barras de progresso (%) no dashboard. 🟢
- **Clientes Não Atendidos:** Calculado por exclusão na `view_vendedor_cliente_status`. 🟡

## Riscos e Lacunas

- 🔴 **Meta Automática:** Não foi encontrada lógica de sugestão de meta baseada no histórico do ano anterior; a meta parece ser 100% de inserção manual.
- 🟡 **Dialeto SQL Pivot:** As views de pivoteamento utilizam a função `IF` (comum no MySQL) mas o DDL fornecido é PostgreSQL. Requer validação se a migração de dialeto foi feita ou se utilizam `CASE WHEN`.
