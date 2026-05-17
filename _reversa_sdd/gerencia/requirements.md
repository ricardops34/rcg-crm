# Gerência — Requisitos

## Visão Geral
Esta unit centraliza as ferramentas de supervisão e decisão estratégica do ERP. Ela fornece visões consolidadas do desempenho comercial, gestão da estrutura hierárquica de vendas e o motor de definição de metas mensais, permitindo que gestores acompanhem o realizado versus o planejado em tempo real.

## Responsabilidades
- Consolidar indicadores de vendas (KPIs) para supervisores e administradores. 🟢
- Gerir a estrutura de representantes comerciais (Vendedores) e supervisores. 🟢
- Definir objetivos financeiros e operacionais (Metas) por período. 🟢
- Prover relatórios de evolução de vendas e rankings de performance. 🟢
- Monitorar a saúde da carteira de clientes (Atendidos vs Não Atendidos). 🟢

## Regras de Negócio
- **Hierarquia de Acesso:** Apenas usuários com perfil `Supervisor` ou `Admin` acessam os dashboards desta unit. 🟢
- **Cascateamento de Metas:** Uma meta mensal global de um vendedor pode ser subdividida em sub-metas por categoria de produto. 🟢
- **Integridade de Metas:** Não é permitido excluir uma meta mensal que já possua desdobramentos por categoria vinculados. 🟢
- **Cálculo de Atingimento:** O percentual de atingimento deve ser calculado comparando o `vlr_liquido` faturado (Vendas - Devoluções) contra o `vlr_objetivo` da meta. 🟢
- **Indicador de "Clientes Órfãos":** O sistema deve identificar clientes ativos que estão vinculados a vendedores desligados ou sem vínculo de supervisão. 🟡

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Dashboard Gerencial | Must | Painel consolidado com sugestão de venda, ticket médio e positivação. |
| RF-02 | Gestão de Metas | Must | Cadastro mestre-detalhe para definição de objetivos mensais e por categoria. |
| RF-03 | Ranking de Vendedores | Must | Lista comparativa de performance com indicadores visuais de progresso (%). |
| RF-04 | Evolução 12 Meses | Should | Relatório em formato Pivot exibindo o histórico de faturamento por cliente. |
| RF-05 | Cadastro de Vendedores | Must | Manutenção de dados de representantes e vínculo com supervisores. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Performance | Uso intensivo de SQL Pivot e Agregações no Banco | `app/database/erp_online-pgsql.sql` (Views pivot) | 🟢 |
| Segurança | Filtro de escopo por papel de usuário na sessão | `app/control/gerencia/DashboardGerencia.php` | 🟢 |
| Usabilidade | Uso de gráficos de barras e indicadores coloridos | `_reversa_sdd/gerencia/screens.md` (Screenshots) | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Tentativa de Exclusão de Meta com Itens
Dado uma meta do vendedor "João" para o mês 05/2026
E que existam metas por categoria (Ex: "Limpeza") vinculadas a ela
Quando o gestor tentar excluir a meta de João
Então o sistema deve lançar exceção "Não é possível deletar este registro..." e impedir a ação

Cenário: Cálculo de Ranking no Dashboard
Dado que a meta de um vendedor é R$ 10.000,00
E o faturamento líquido dele no mês é R$ 8.000,00
Quando o supervisor abrir o Dashboard
Então o sistema deve exibir 80% de atingimento para este vendedor
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Dashboard Consolidado | Must | Principal ferramenta de monitoramento da gestão. |
| Motor de Metas | Must | Define os parâmetros de sucesso da força de vendas. |
| Hierarquia Supervisor/Vendedor | Must | Garante que os dados sejam agrupados corretamente. |
| Relatórios Pivot (Evolução)| Should | Útil para análise de tendência, mas secundário à meta atual. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/control/gerencia/DashboardGerencia.php` | `DashboardGerencia` | 🟢 |
| `app/model/MetaVendedorMes.php` | `MetaVendedorMes` | 🟢 |
| `app/control/gerencia/VendaMesClienteList.php`| Evolução 12 meses | 🟢 |
| `app/model/Vendedor.php` | `Vendedor` | 🟢 |
