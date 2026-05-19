# Target Business Rules — Reversa

Este documento consolida as regras de negócio confirmadas para migração do legado RCG para a stack Reversa (NestJS/Angular), operando sob o paradigma **Híbrido (Equilibrado)**.

## 1. Módulo Admin (Segurança e Governança) — [ESTRATÉGIA: MODERNIZAR]

| ID | Regra de Negócio | Status | Nota de Modernização |
|----|------------------|--------|----------------------|
| ADM-01 | Autenticação de Usuários Ativos | MIGRAR | Implementar via Guards do NestJS e JWT Stateless. |
| ADM-02 | Rehash de Senha (MD5 -> Bcrypt) | MIGRAR | Conversão transparente no primeiro login; suporte a algoritmos modernos (Argon2 opcional). |
| ADM-03 | Seleção de Unidade (Multi-unit) | MIGRAR | Persistir unidade ativa no Payload do JWT para evitar consultas repetitivas. |
| ADM-04 | RBAC (Controle por Perfil) | MIGRAR | Implementar permissões granulares via Decorators e interceptores (Modernização). |
| ADM-05 | Segundo Fator (2FA TOTP) | MIGRAR | Integrar como Middleware nativo no fluxo de login. |
| ADM-06 | Aceite de Termos de Uso | MIGRAR | Armazenar log de aceite em banco documental ou tabela de auditoria desacoplada. |
| ADM-07 | Trava de Sessão Única | MIGRAR | Controlar sessões ativas via Redis para suporte a escalabilidade horizontal. |

## 2. Módulo Cadastros (Master Data) — [ESTRATÉGIA: CONSERVADOR]

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| CAD-01 | Validação de CPF/CNPJ | MIGRAR | Verificação de máscara e integridade; consulta RFB para CNPJ. |
| CAD-02 | Auditoria (Change Log) | MIGRAR | Registro de deltas via Interceptor genérico (TypeORM Subscriber). |
| CAD-03 | Bloqueio de Cliente ('B') | MIGRAR | Restrição mantida no nível de serviço de pedidos. |
| CAD-04 | Vínculo Obrigatório | MIGRAR | Cliente deve ter Vendedor e Condição de Pagamento padrão. |
| CAD-05 | Tabelas de Preço Ativas | MIGRAR | Filtro de tabelas por status 'Ativo' no momento da venda. |

## 3. Módulo Cobrança (Financeiro) — [ESTRATÉGIA: MODERNIZAR]

| ID | Regra de Negócio | Status | Nota de Modernização |
|----|------------------|--------|----------------------|
| COB-01 | Inadimplência Forçada | MIGRAR | Lógica de seleção obrigatória movida para o Backend (Service Layer). |
| COB-02 | Destaque Visual de Atraso | MIGRAR | Configuração de cores via Design Tokens no frontend Angular. |
| COB-03 | Situação de Carteira (0-6) | MIGRAR | Transformar em um Domain Type com fluxos de transição claros. |
| COB-04 | Cálculo de Dias de Atraso | MIGRAR | Cálculo dinâmico em tempo real, eliminando dependência de campos calculados no banco. |
| COB-05 | Agregação de Saldos | MIGRAR | Migrar lógica das Views SQL para Read Models ou Caching via Redis (BI leve). |
| COB-06 | Simulação de Juros/Multa | MIGRAR | Implementar calculadora dinâmica no Frontend com validação de regras no Backend. |

## 4. Módulo Vendedor (CRM e BI) — [ESTRATÉGIA: MODERNIZAR]

| ID | Regra de Negócio | Status | Nota de Modernização |
|----|------------------|--------|----------------------|
| VEN-01 | Isolamento de Carteira | MIGRAR | Multi-tenancy lógico aplicado em nível de Repository. |
| VEN-02 | Cálculo de MVC | MIGRAR | Desacoplar regra de cálculo (Service) e permitir processamento assíncrono. |
| VEN-03 | Dashboard de Performance | MIGRAR | Utilizar WebSockets ou Long Polling para atualizações de metas em tempo real. |
| VEN-04 | Posição 360 do Cliente | MIGRAR | Interface unificada agregando micro-serviços ou módulos internos. |
| VEN-05 | Níveis de Supervisão | MIGRAR | Suportar estrutura hierárquica recursiva (N níveis) vs os 2 níveis do legado. |

## 5. Módulo Gerência (Estratégia) — [ESTRATÉGIA: MODERNIZAR]

| ID | Regra de Negócio | Status | Nota de Modernização |
|----|------------------|--------|----------------------|
| GER-01 | Cascateamento de Metas | MIGRAR | Lógica de distribuição automática baseada em histórico ou peso de categoria. |
| GER-02 | Integridade de Metas | MIGRAR | Implementar Sagas ou Transações ACID para garantir consistência no desdobramento. |
| GER-03 | Atingimento Gerencial | MIGRAR | Agregação em memória ou via visões materializadas para alta performance. |
| GER-04 | Indicador de Clientes Órfãos| MIGRAR | Alerta proativo via Job agendado ou Trigger de evento. |

## 6. Regras Transversais — [ESTRATÉGIA: MISTA / LEGACY BRIDGE]

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| TRX-01 | Preenchimento Inteligente | MIGRAR | Carga reativa (RxJS) no frontend ao selecionar o cliente. |
| TRX-02 | Cálculo de Itens | MIGRAR | Centralizar lógica de cálculo em biblioteca compartilhada (TS) entre Front/Back. |
| TRX-03 | Conversão Orçamento -> Pedido | MIGRAR | Manter persistência dupla (Legacy Bridge) mas isolada em camada de infra. |
| TRX-04 | Visualizador DANFE | MIGRAR | Streaming de PDF gerado on-the-fly ou integração via API Totvs. |
