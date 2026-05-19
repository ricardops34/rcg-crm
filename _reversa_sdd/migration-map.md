# Mapa de Migração Modular — Sistema RCG

Este documento lista todos os módulos e rotinas identificados no sistema legado, agrupados por domínio e prioridade de migração.

## 1. Módulo: Segurança, Acesso e Permissões (Core)
*Base fundamental que garante a integridade e o controle de acesso do sistema.*

### 1.1. Autenticação e Sessão
| Rotina | Descrição | Origem Legada | Status |
|--------|-----------|---------------|--------|
| **Multi-Hash Auth** | Suporte a MD5 (legado) com auto-migração para Bcrypt | `SystemUsers::authenticate` | 🟢 Resolvido |
| **2FA (Email/TOTP)** | Segundo fator de autenticação configurável por perfil | `System2FAForm` | 🟢 Resolvido |
| **Gestão de Sessão** | Controle de Single Session e expiração por inatividade | `TSession` / `application.ini`| 🟢 Resolvido |
| **Recuperação de Senha**| Fluxo de reset via token por e-mail | `SystemUsers::resetPassword` | 🟡 Parcial |
| **reCAPTCHA** | Proteção contra brute-force no login (v2/v3) | `LoginForm` | 🟢 Resolvido |

### 1.2. Autorização e RBAC (Controle de Acesso)
| Rotina | Descrição | Origem Legada | Status |
|--------|-----------|---------------|--------|
| **Gestão de Perfis** | Agrupamento de permissões por Grupos (Admin/Vendedor/etc) | `SystemGroup` | 🟢 Resolvido |
| **Permissões de Tela** | Controle de acesso granular por Controller/Programa | `SystemProgram` | 🟢 Resolvido |
| **Multi-Unidade** | Seleção de unidade (Filial) no login para isolamento de dados | `LoginForm::onLogin` | 🟢 Resolvido |
| **Aceite de Termos** | Bloqueio de acesso até aceite dos Termos de Uso/LGPD | `accepted_term_policy` | 🟢 Resolvido |

### 1.3. Restrição de Escopo de Dados (Filtros de Segurança)
| Rotina | Descrição | Origem Legada | Status |
|--------|-----------|---------------|--------|
| **Escopo Vendedor** | Filtro automático: vê apenas seus clientes e títulos | `vendedor_id` check | 🟢 Resolvido |
| **Escopo Supervisor** | Filtro automático: vê apenas dados de sua equipe direta | `supervisor_vendedor` | 🟢 Resolvido |
| **Filtro de Unidade** | Isolamento de registros por Filial logada | `system_unit_id` check | 🟢 Resolvido |

### 1.4. Auditoria e Conformidade
| Rotina | Descrição | Origem Legada | Status |
|--------|-----------|---------------|--------|
| **Audit Trail (CRUD)** | Log de "quem alterou o que" em cada campo (mestre/detalhe) | `SystemChangeLogTrait` | 🟢 Resolvido |
| **Log de Acessos** | Registro de logins, logouts e tentativas falhas | `system_access_log` | 🟢 Resolvido |
| **Log de SQL** | Rastreabilidade de queries executadas por usuário | `system_sql_log` | 🟢 Resolvido |


## 2. Módulo: Cadastros Base
*Entidades mestres necessárias para vendas e financeiro.*

| Rotina | Descrição | Origem Legada (PHP) | Status Reversa |
|--------|-----------|---------------------|----------------|
| **Clientes** | Cadastro multi-aba (Fiscais/Comerciais)| `ClienteForm` | 🟢 Resolvido |
| **Produtos** | Catálogo com Saldo e Preço | `ProdutoForm` | 🟢 Resolvido |
| **Vendedores** | Gestão de Representantes e Vínculos | `VendedorForm` | 🟢 Resolvido |
| **Tabelas de Preço**| Vigência de preços por produto | `TabelaPrecoForm` | 🟢 Resolvido |
| **Condições Pgto** | Prazos e formas de recebimento | `CondicaoPagamento` | 🟢 Resolvido |

## 3. Módulo: Força de Vendas (Vendedor/Supervisor)
*Foco na operação comercial diária.*

| Rotina | Descrição | Origem Legada (PHP) | Status Reversa |
|--------|-----------|---------------------|----------------|
| **Dashboard Vendedor**| KPIs de atingimento e metas | `DashboardVendedor` | 🟢 Resolvido |
| **MVC** | Média de Venda do Cliente (12 meses) | `MVC` (View) | 🟢 Resolvido |
| **Agenda/Visitas** | Registro de atendimentos comerciais | `AtendimentoForm` | 🟢 Resolvido |
| **Orçamentos** | Geração de propostas comerciais | `OrcamentoForm` | 🟢 Resolvido |
| **Conversão** | Transformar Orçamento em Pedido ERP | `Orcamento::onFaturar`| 🟡 Inferido |

## 4. Módulo: Financeiro e Cobrança
*Processos de recebimento e recuperação de crédito.*

| Rotina | Descrição | Origem Legada (PHP) | Status Reversa |
|--------|-----------|---------------------|----------------|
| **Títulos a Receber** | Listagem de faturas e saldos | `TituloReceberList` | 🟢 Resolvido |
| **Negociação** | Agrupamento de dívidas vencidas | `NegociacaoList` | 🟢 Resolvido |
| **Boletos** | Geração de PDFs bancários (Bradesco) | `BoletoBradesco` | 🟢 Resolvido |
| **Fluxo Inadimplência**| Bloqueio automático por atraso | `ViewClienteSaldoTitulo`| 🟢 Resolvido |

## 5. Módulo: BI e Relatórios (Gerencial)
*Visão macro e apoio à decisão.*

| Rotina | Descrição | Origem Legada (SQL/PHP) | Status Reversa |
|--------|-----------|-------------------------|----------------|
| **Dash Gerencial** | Rankings de equipe e atingimento global| `DashboardGerencia` | 🟢 Resolvido |
| **Emissão DANFE** | Visualizador de Notas Fiscais (PDF) | `Danfe` (XML Base) | 🟢 Resolvido |
| **Metas** | Definição de objetivos Mensal/Categoria| `MetaVendedorMes` | 🟢 Resolvido |
| **Vendas por Região** | Mapa de calor de faturamento | `view_venda_regiao_mes` | 🟢 Resolvido |

## 6. Módulo: Integração (API/Bot)
*Interfaces externas e automatização.*

| Rotina | Descrição | Origem Legada (PHP) | Status Reversa |
|--------|-----------|---------------------|----------------|
| **Sincronização ERP**| Importação de dados via StoreArray | `AdiantiRecordService` | 🟢 Resolvido |
| **Consulta WhatsApp**| API para Bot de autoatendimento | `WhatsAppRestService` | 🟢 Resolvido |
| **Notificações** | Disparo de alertas via E-mail/Msg | `CommunicationService` | 🟡 Parcial |

## 7. Estrutura de Menus e Navegação (UX)
*Mapeamento da hierarquia original para fidelidade na interface Angular/PO-UI.*

- **Cadastros:** Notícias, Comunicados, Aniversários.
- **Vendedor:** Dashboard, Clientes, Agenda (Calendário), MVC (BI), Atendimentos.
- **Gerencia:** Vendedores (Equipe), Metas (Objetivos), Dashboard Estratégico, Evolução de Venda, Ranking Top 10.
- **Cobrança:** Movimentação, Negociação de Títulos.
- **Sistema:** Configurações de Estados (Pedido/Orçamento), Parâmetros, Tipos de Atendimento.
- **Administração:** Gestão de Usuários, Programas, Grupos, Logs Técnicos.

---
**Nota sobre a Estratégia de Migração:**
As rotinas marcadas como 🟢 estão prontas para serem implementadas no novo sistema (NestJS/Angular) pois suas regras de negócio e estruturas de dados estão 100% mapeadas. Rotinas 🟡 possuem dependências externas (como o ERP legado) que exigem atenção especial na camada de Service.
