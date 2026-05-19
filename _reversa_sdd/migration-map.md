# Mapa de Migração Modular — Sistema RCG

Este documento lista todos os módulos e rotinas identificados no sistema legado, agrupados por domínio e prioridade de migração.

## 1. Módulo: Segurança e Admin (Core)
*Base fundamental para qualquer acesso ao sistema.*

| Rotina | Descrição | Origem Legada (PHP) | Status Reversa |
|--------|-----------|---------------------|----------------|
| **Autenticação** | Login com Rehash MD5 -> Bcrypt | `LoginForm` | 🟢 Resolvido |
| **2FA** | Segundo fator via E-mail/Google | `System2FAForm` | 🟢 Resolvido |
| **RBAC** | Gestão de Programas e Grupos | `SystemUsers` | 🟢 Resolvido |
| **Log de Alterações**| Auditoria de campos (Audit Trail) | `SystemChangeLogTrait` | 🟢 Resolvido |
| **Multi-Unidade** | Filtro de dados por Unidade/Filial | `system_unit_id` | 🟢 Resolvido |

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

---
**Nota sobre a Estratégia de Migração:**
As rotinas marcadas como 🟢 estão prontas para serem implementadas no novo sistema (NestJS/Angular) pois suas regras de negócio e estruturas de dados estão 100% mapeadas. Rotinas 🟡 possuem dependências externas (como o ERP legado) que exigem atenção especial na camada de Service.
