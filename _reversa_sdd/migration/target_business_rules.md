# Target Business Rules — Reversa

Este documento consolida as regras de negócio confirmadas para migração do legado RCG para a stack Reversa (NestJS/Angular), mantendo o paradigma Conservador (Active Record).

## 1. Módulo Admin (Segurança e Governança)

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| ADM-01 | Autenticação de Usuários Ativos | MIGRAR | Somente usuários com `status = 'A'` podem logar. (`requirements.md`) |
| ADM-02 | Rehash de Senha (MD5 -> Bcrypt) | MIGRAR | Conversão transparente no login conforme ADR 001. (`requirements.md`, `Q-01`) |
| ADM-03 | Seleção de Unidade (Multi-unit) | MIGRAR | Bloqueio de acesso até escolha de unidade válida após login. (`requirements.md`) |
| ADM-04 | RBAC (Controle por Perfil) | MIGRAR | Filtro de menus e dados baseado no papel (Vendedor/Supervisor/Admin). (`domain.md`) |
| ADM-05 | Segundo Fator (2FA TOTP) | MIGRAR | Exigência de token para usuários configurados. (`requirements.md`) |
| ADM-06 | Aceite de Termos de Uso | MIGRAR | Registro de IP e data/hora do aceite antes do primeiro acesso. (`requirements.md`) |

## 2. Módulo Cadastros (Master Data)

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| CAD-01 | Validação de CPF/CNPJ | MIGRAR | Verificação de máscara e integridade; consulta RFB para CNPJ. (`requirements.md`) |
| CAD-02 | Auditoria (Change Log) | MIGRAR | Registro obrigatório de deltas para Clientes e Produtos (ADR 002). (`requirements.md`) |
| CAD-03 | Bloqueio de Cliente ('B') | MIGRAR | Clientes bloqueados não podem gerar novos orçamentos/pedidos. (`requirements.md`, `domain.md`) |
| CAD-04 | Vínculo Obrigatório | MIGRAR | Cliente deve ter Vendedor e Condição de Pagamento padrão. (`requirements.md`) |
| CAD-05 | Tabelas de Preço Ativas | MIGRAR | Apenas tabelas com status 'Ativo' podem ser usadas em novas vendas. (`requirements.md`) |

## 3. Módulo Cobrança (Financeiro)

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| COB-01 | Inadimplência Forçada | MIGRAR | Obrigatório selecionar TODOS os títulos vencidos em negociações. (`domain.md`, `requirements.md`) |
| COB-02 | Destaque Visual de Atraso | MIGRAR | Fundo amarelo (#FFF9A7) e valores em vermelho para títulos vencidos. (`domain.md`) |
| COB-03 | Situação de Carteira (0-6) | MIGRAR | Determina o fluxo de cobrança (Simples, Advogado, Judicial). (`domain.md`) |
| COB-04 | Cálculo de Dias de Atraso | MIGRAR | Contagem em dias corridos desde `venc_real` até a data atual. (`requirements.md`) |
| COB-05 | Agregação via Views SQL | MIGRAR | Saldos e status devem ser lidos da `view_cliente_saldo_titulo`. (`G-01`, `requirements.md`) |

## 4. Módulo Vendedor (CRM e BI)

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| VEN-01 | Isolamento de Carteira | MIGRAR | Vendedores acessam apenas dados vinculados ao seu `vendedor_id`. (`requirements.md`) |
| VEN-02 | Cálculo de MVC | MIGRAR | Performance do cliente vs média ponderada dos últimos 3 meses. (`domain.md`, `requirements.md`) |
| VEN-03 | Dashboard de Performance | MIGRAR | Exibição de Vendas Líquidas (Vendas - Devoluções) vs Meta. (`requirements.md`) |
| VEN-04 | Posição 360 do Cliente | MIGRAR | Visão consolidada de dados fiscais, financeiros e histórico de pedidos. (`requirements.md`) |

## 5. Módulo Gerência (Estratégia)

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| GER-01 | Cascateamento de Metas | MIGRAR | Divisão de meta global em sub-metas por categoria de produto. (`requirements.md`) |
| GER-02 | Integridade de Metas | MIGRAR | Bloqueio de exclusão de meta principal se houver desdobramentos. (`domain.md`, `requirements.md`) |
| GER-03 | Atingimento Gerencial | MIGRAR | Cálculo baseado no realizado consolidado de toda a equipe supervisionada. (`requirements.md`) |

## 6. Regras Transversais

| ID | Regra de Negócio | Status | Justificativa / Evidência |
|----|------------------|--------|---------------------------|
| TRX-01 | Preenchimento Inteligente | MIGRAR | Carga automática de tabela/pagamento ao selecionar cliente no orçamento. (`domain.md`) |
| TRX-02 | Cálculo de Itens | MIGRAR | Total = `(Preço * Qtd) - Desconto + Acréscimo`. (`domain.md`) |
| TRX-03 | Conversão Orçamento -> Pedido | MIGRAR | Persistência física dupla para manter compatibilidade com ERP legado. (`G-02`, `Q-03`) |
| TRX-04 | Visualizador DANFE | MIGRAR | Sistema atua como visualizador de XMLs autorizados via TSS (Totvs). (`Q-07`) |
