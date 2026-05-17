# Vendedor — Requisitos

## Visão Geral
Esta unit provê as ferramentas de campo e CRM para a força de vendas. Ela permite que o representante acompanhe suas metas individuais, gerencie sua agenda de atendimentos e analise o comportamento de compra de sua carteira de clientes através de indicadores de BI e médias de consumo (MVC).

## Responsabilidades
- Fornecer um painel de indicadores (Dashboard) focado no atingimento da meta individual. 🟢
- Gerir a agenda de visitas e contatos comerciais (Atendimentos). 🟢
- Monitorar a frequência de compra dos clientes (Média de Venda do Cliente - MVC). 🟢
- Permitir a visualização rápida da posição financeira e comercial de cada cliente da carteira. 🟢
- Auxiliar na positivação de clientes e identificação de baixas na média de compra. 🟢

## Regras de Negócio
- **Trava de Carteira:** Um vendedor acessa exclusivamente dados (clientes, pedidos, atendimentos) vinculados ao seu `vendedor_id`. 🟢
- **Cálculo de MVC:** O sistema calcula a média de consumo do cliente baseado nos últimos 3 meses e compara com o mês atual para identificar tendências de queda. 🟢
- **Indicadores de Performance:** O dashboard do vendedor deve exibir o somatório de vendas líquidas, devoluções e o % de atingimento da meta global do mês. 🟢
- **Agenda de Atendimentos:** Contatos registrados na agenda devem estar vinculados a um `AtendimentoTipo` e possuir horário inicial/final. 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Dashboard do Vendedor | Must | Painel com KPIs de faturamento individual e barra de progresso de metas. |
| RF-02 | Agenda CRM | Must | Calendário ou listagem de atendimentos vinculados a clientes da carteira. |
| RF-03 | Análise de MVC | Must | Visualização de 12 meses de compras pivoteados por cliente. |
| RF-04 | Posição do Cliente | Must | Tela consolidada com dados cadastrais, financeiros e últimos pedidos do cliente. |
| RF-05 | Listagem de Carteira | Should | Grid de clientes com filtros por status e situação de positivação. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Segurança | Isolamento de dados via `TSession::getValue("vendedor_id")` | `app/control/vendedor/DashboardVendedor.php` | 🟢 |
| Usabilidade | Uso de indicadores visuais de BI (BIndicator) | `_reversa_sdd/vendedor/screens.md` | 🟢 |
| Integridade | Histórico de atendimento vinculados de forma N:1 | `app/model/Atendimento.php` | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Acesso ao Dashboard
Dado que o vendedor "José" está logado
Quando ele abrir a página inicial
Então o sistema deve exibir apenas o faturamento e metas de "José"
E a agenda deve listar as visitas programadas apenas para ele

Cenário: Identificação de Queda de Média (MVC)
Dado que a média de compras do Cliente A é R$ 1.000,00 nos últimos 3 meses
E no mês atual ele comprou apenas R$ 200,00
Quando o vendedor visualizar a lista MVC
Então o sistema deve exibir um indicador negativo de R$ 800,00 (Diferença)
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Dashboard de Metas | Must | Principal motivador e métrica de sucesso do representante. |
| Carteira de Clientes | Must | Sem o isolamento da carteira, a operação de campo é impossível. |
| Média de Venda (MVC) | Should | Ferramenta de BI importante para proatividade comercial. |
| Agenda de Atendimento | Should | Organiza o dia a dia, mas a venda pode ocorrer fora da agenda. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/control/vendedor/DashboardVendedor.php` | `DashboardVendedor` | 🟢 |
| `app/model/Mvc.php` | `Mvc` | 🟢 |
| `app/model/Atendimento.php` | `Atendimento` | 🟢 |
| `app/control/vendedor/PosisaoClienteFormView.php`| Visão 360 do cliente | 🟢 |
