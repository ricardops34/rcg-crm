# Target Screens Inventory — Reversa

Este documento mapeia as telas do legado RCG para os novos componentes Angular + PO-UI, definindo a estratégia de modernização aplicada.

## 1. Módulo Admin (Segurança)
| Tela Legada | Estratégia | Novo Componente/Rota | Justificativa |
| :--- | :--- | :--- | :--- |
| Login (TPage) | **Modernizado** | `po-page-login` (`/auth/login`) | Suporte a JWT, Rehash de senha e 2FA. |
| Seleção de Unidade | **Modernizado** | `po-combo` em Modal | Persistência no JWT e melhor UX. |
| Usuários/Perfis | **Preservado** | `po-page-dynamic-edit` | CRUD administrativo padrão. |

## 2. Módulo Cadastros (Master Data)
| Tela Legada | Estratégia | Novo Componente/Rota | Justificativa |
| :--- | :--- | :--- | :--- |
| Cadastro de Cliente | **Preservado** | `po-page-edit` + `po-dynamic-form` | Manter fidelidade às validações complexas. |
| Listagem de Clientes | **Preservado** | `po-page-list` + `po-table` | Funcionalidade de filtros e busca preservada. |
| Cadastro de Vendedores| **Preservado** | `po-page-edit` | Simplicidade e velocidade de migração. |

## 3. Módulo Cobrança (Financeiro)
| Tela Legada | Estratégia | Novo Componente/Rota | Justificativa |
| :--- | :--- | :--- | :--- |
| Negociação de Títulos | **Modernizado** | `po-stepper` + `po-grid` | Fluxo complexo de simulação de juros/multa. |
| Carteira de Cobrança | **Modernizado** | `po-table` (Advanced View) | Visualização rica de status (0-6) e cores de atraso. |
| Dashboard Cobrança | **Modernizado** | `po-chart` + `po-widget` | Visibilidade estratégica de inadimplência. |

## 4. Módulo Vendedor & Gerência (Analytics)
| Tela Legada | Estratégia | Novo Componente/Rota | Justificativa |
| :--- | :--- | :--- | :--- |
| Dashboard Vendedor | **Modernizado** | `po-chart` (Gauge/Line) | Foco em performance e gamificação (Metas). |
| Dashboard Gerencial | **Modernizado** | `po-chart` (Donut/Bar) | Visão consolidada de unidades e supervisores. |
| Cadastro de Objetivos | **Modernizado** | `po-page-edit` + `po-grid` | Mestre-detalhe para distribuição de metas. |
| MVC (Média de Venda) | **Modernizado** | `po-table` (Pivot View) | Re-engenharia de performance para grandes massas de dados. |

## 5. Módulo Desenvolvimento (Vendas)
| Tela Legada | Estratégia | Novo Componente/Rota | Justificativa |
| :--- | :--- | :--- | :--- |
| OrcamentoForm | **Modernizado** | `po-page-edit` + `po-grid` | Transição de Venda Clássica para Venda Reativa. |
| CatalogoProdutos | **Modernizado** | `po-list-view` (Cards) | Melhoria na experiência de tablet/mobile. |
