# Vendedor, Design Técnico

## Interface

### Models e Componentes Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `DashboardVendedor` | TPage | `HTML` | Painel de controle individual do representante. 🟢 |
| `Mvc.get_venda_mes` | `($mes, $ano)` | `float` | Retorna faturamento do cliente em um período. 🟢 |
| `Mvc.get_dif_media` | `()` | `float` | Calcula delta (Mês Atual vs Média 3 meses). 🟢 |
| `PosisaoClienteFormView`| TPage | `HTML` | Ficha técnica 360 do cliente da carteira. 🟢 |

## Fluxo Principal (Painel de Metas Individual)

1. **Início:** O vendedor loga no sistema.
2. **Contextualização:** O sistema obtém `vendedor_id` da `TSession`. 🟢
3. **Consulta de Meta:**
    - Busca em `meta_vendedor_mes` o valor objetivo para o `vendedor_id`, `mes` e `ano` correntes. 🟢
4. **Cálculo de Realizado:**
    - Consulta `ViewVendedorVendaMes` para obter a soma de faturamento líquido. 🟢
5. **Renderização de Gráficos:**
    - Utiliza o componente `BTableChart` para exibir a barra de progresso (%) comparando Meta vs Realizado.
    - Exibe indicadores coloridos (`BIndicator`) para Vendas, Devoluções e Clientes Positivados. 🟢

## Fluxo de Análise CRM (Média de Venda - MVC)

Lógica embarcada no Active Record `Mvc` para suportar a inteligência de vendas.

1. **Pivoteamento:** O banco de dados fornece as colunas `janeiro` a `dezembro` via View Pivot.
2. **Cálculo de Média Móvel:**
    - O método `get_venda_media_tres()` percorre os últimos 3 meses no histórico. 🟢
3. **Identificação de Tendência:**
    - `get_dif_media()` subtrai a média dos 3 meses do valor comprado no mês atual.
    - Valores negativos indicam queda de consumo do cliente, disparando alerta visual no grid. 🟢

## Dependências

- **Adianti BI Components:** BIndicator, BTableChart. 🟢
- **Database Views:** `ViewVendedorVendaMes`, `ViewBaseClienteMes`. 🟢
- **SystemUsers:** Necessário para isolamento de `vendedor_id` na sessão. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Isolamento de Carteira via Sessão | `app/control/vendedor/DashboardVendedor.php` | 🟢 |
| Lógica de BI no Active Record | `app/model/Mvc.php` | 🟢 |
| Uso de Views para Positivação | `app/database/erp_online-pgsql.sql` | 🟢 |

## Estado Interno

- **Sessão:** A variável `vendedor_id` é o coração de todo o escopo desta unit.
- **Transactional State:** Atendimentos agendados possuem status (Pendente, Realizado, Cancelado). 🟡

## Observabilidade

- **Logs de Atendimento:** Cada interação com o cliente gera um registro em `atendimento` com data e hora. 🟢

## Riscos e Lacunas

- 🔴 **Comissão:** Não foi encontrada lógica explícita de cálculo de comissão sobre vendas no código analisado (pode residir no ERP legado ou em triggers de banco).
- 🟡 **Geolocalização:** Embora existam referências a `Regiao`, não há evidência de captura de coordenadas GPS durante os atendimentos na agenda no código PHP.
