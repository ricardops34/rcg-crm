# Mapeamento Técnico: Legado PHP -> Novo Rebuild (NestJS/Angular)

Este documento traduz as rotinas funcionais para os artefatos técnicos reais do sistema legado em PHP, servindo de guia para a localização da lógica original.

## 1. Módulo: Segurança e Admin (Core)

| Rotina | Classe PHP Legada (Controller/Model) | Responsabilidade |
|--------|-------------------------------------|------------------|
| **Login / Auth** | `LoginForm.php` | Orquestração do login, reCAPTCHA e 2FA. |
| **Validação Credenciais**| `SystemUsers.php` | Lógica de Hash MD5/Bcrypt e validação de status. |
| **Sessão (RBAC)** | `TSession.php` / `SystemUsers::getPrograms()` | Carregamento de permissões e escopo de dados. |
| **Escolha de Unidade** | `LoginForm.php` (Fluxo Choice Unit) | Filtro de filial ativa na sessão. |
| **Log de Acessos** | `SystemAccessLogService.php` | Registro de histórico de entradas e saídas. |

## 2. Módulo: Força de Vendas (Vendedor/Supervisor)

| Rotina | Classe PHP Legada (Controller/Model) | Responsabilidade |
|--------|-------------------------------------|------------------|
| **Dashboard Vendedor** | `DashboardVendedor.php` | KPI de atingimento e metas individuais. |
| **MVC (Média Venda)** | `Mvc.php` | Lógica de cálculo de tendência (3 meses vs atual). |
| **Ficha do Cliente** | `PosisaoClienteFormView.php` | Visão 360 do histórico do cliente. |
| **Agenda / Visitas** | `AtendimentoForm.php` | Registro e agendamento de atendimentos. |
| **Orçamentos** | `OrcamentoForm.php` | Criação de propostas com BuilderMasterDetail. |
| **Cálculo de Itens** | `Orcamento_item.php` | Regra de (Preço * Qtd) - Desconto. |

## 3. Módulo: Cadastros Base

| Rotina | Classe PHP Legada (Controller/Model) | Responsabilidade |
|--------|-------------------------------------|------------------|
| **Cadastro Clientes** | `ClienteForm.php` | Formulário multi-aba com dados fiscais. |
| **Auditoria CRUD** | `SystemChangeLogTrait.php` | Interceptação de `store()` para log de delta. |
| **Busca Endereço** | `CEPService.php` | Integração com BrasilAPI e hidratação de IDs. |
| **Tabelas de Preço** | `TabelaPrecoForm.php` | Gestão de vigência e preços por produto. |
| **Produtos** | `ProdutoForm.php` | Cadastro mestre com vínculos de categoria/estoque. |

## 4. Módulo: Financeiro e Cobrança

| Rotina | Classe PHP Legada (Controller/Model) | Responsabilidade |
|--------|-------------------------------------|------------------|
| **Listagem Cobrança** | `NegociacaoList.php` | Interface de seleção de títulos e cores de atraso. |
| **Validação Dívida** | `NegociacaoList.php::onGerar` | Bloqueio de gravação se houver vencidos omitidos. |
| **Emissão Boleto** | `BoletoBradesco.php` | Integração com OpenBoleto para PDF. |
| **Dashboard Títulos** | `ViewTituloCliente.php` | View que calcula status (Vencido/Aberto/Recebido). |

## 5. Módulo: Gerência e BI

| Rotina | Classe PHP Legada (Controller/Model) | Responsabilidade |
|--------|-------------------------------------|------------------|
| **Dash Gerencial** | `DashboardGerencia.php` | Ranking de vendedores e atingimento global. |
| **Evolução de Venda** | `VendaMesClienteList.php` | Relatório Pivot baseado na `pivot_venda_mes_cliente`. |
| **Gestão de Metas** | `MetaVendedorMesForm.php` | Lógica Mestre-Detalhe para objetivos de venda. |
| **Emissão DANFE** | `Danfe.php` | Conversão de XML para PDF formatado. |

## 6. Módulo: Sistema (Infraestrutura)

| Rotina | Classe PHP Legada (Controller/Model) | Responsabilidade |
|--------|-------------------------------------|------------------|
| **Parâmetros Global** | `Parametro.php` / `SisFunction.php` | Lógica `getParam` com fallback. |
| **Normalização** | `SisFunction.php::NoAcento` | Tratamento de strings para banco de dados. |
| **Log de SQL** | `SystemSqlLog.php` | Gravação de queries brutas com `debug_backtrace`. |
| **Sincronização ERP** | `AdiantiRecordService.php` | Motor genérico de importação via REST. |

## 7. Mapeamento de Menus e Navegação (menu.xml)

Este mapeamento define a hierarquia de navegação e os Controllers disparados por cada item de menu.

| Módulo (Menu) | Item de Menu | Controller Legado (Action) |
|---------------|--------------|----------------------------|
| **Cadastros** | Comunicados | `BlogComunicadosList` |
| | Aniversários | `BlogAniversariosList` |
| | Notícias | `BlogNoticiasList` |
| **Vendedor** | Cadastro de Clientes | `ClienteList` |
| | Dashboard Vendedor | `DashboardVendedor` |
| | Atendimento (Calendário) | `AtendimentoCalendarFormView` |
| | MVC | `MvcList` |
| **Gerencia** | Vendedores | `VendedorList` |
| | Objetivo Mês (Metas) | `MetaVendedorMesList` |
| | Dashboard Gerencial | `DashboardGerencia` |
| | Evolução de Venda | `VendaMesClienteList` |
| | Top 10 Clientes | `ClienteTop10List` |
| **Cobrança** | Negociação | `NegociacaoList` |
| **Desenvolvimento** | Produtos | `ProdutoList` |
| | Orçamentos | `OrcamentoList` |
| | Tabela de Preço | `TabelaPrecoList` |
| | Títulos a Receber | `TituloReceberList` |
| **Supervisor** | Dashboard Supervisor | `DashboardSupervisor` |
| **Sistema** | Configuração Pedidos | `PedidoEstadoList` |
| | Parâmetros | `ParametroList` |
| | Configuração Orçamentos | `OrcamentoEstadoList` |
| | Tipos de Atendimento | `AtendimentoTipoList` |

---
### Como utilizar este guia:
1. Ao implementar uma funcionalidade no NestJS, procure pela classe PHP correspondente para entender a lógica de validação.
2. Verifique se a lógica reside no **Controller** (decisão de interface/fluxo) ou no **Model** (regra de persistência/cálculo).
3. Utilize as **Views SQL** citadas como base para suas Queries ou Repositories no TypeORM.
