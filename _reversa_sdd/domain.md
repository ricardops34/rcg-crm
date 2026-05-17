# Glossário e Regras de Domínio — rcg

Este documento descreve os conceitos fundamentais de negócio e as regras implícitas extraídas do sistema.

## 1. Glossário de Entidades

| Termo | Descrição | Status/Estados |
|-------|-----------|----------------|
| **Cliente** | Entidade mestre do sistema (PJ ou PF). Concentra dados fiscais, endereço e histórico financeiro. | `A` (Ativo), `B` (Bloqueado) |
| **Vendedor** | Representante comercial vinculado ao cliente. Possui metas e agenda de atendimentos. | `A` (Ativo), `B` (Bloqueado) |
| **Supervisor**| Gestor de uma equipe de vendedores. Possui visão consolidada de metas e dashboards. | `S` (Ativo), `N` (Inativo/Desligado) |
| **Produto** | Mercadoria comercializada. Possui categorias, fabricantes e tabelas de preço. | - |
| **Orçamento** | Proposta comercial inicial. Pode conter múltiplos itens e ser convertida em pedido. | `Aberto`, `Ganho`, `Perdido` |
| **Pedido** | Venda confirmada e pronta para faturamento/entrega. | Configurado em `PedidoEstado` |
| **Título** | Parcela financeira a receber de um cliente. | `A RECEBER`, `EM ATRASO`, `RECEBIDO` |
| **Negociação**| Processo de cobrança ativa que agrupa um ou mais títulos vencidos. | `G` (Gerada) |

## 2. Regras de Negócio Críticas

### 2.1. Vendas e Orçamentos
- **Preenchimento Inteligente:** Ao selecionar um cliente em um orçamento, o sistema deve carregar automaticamente a Tabela de Preço, Condição de Pagamento e Vendedor padrão vinculados ao cadastro do cliente. 🟢 CONFIRMADO
- **Cálculo de Itens:** O valor total de um item de orçamento é derivado de `(Preço de Tabela * Quantidade) - Desconto + Acréscimo`. 🟢 CONFIRMADO
- **Conversão:** Um orçamento no estado "Ganho" gera um registro na tabela de Pedidos, mantendo a rastreabilidade via `pedido_id`. 🟡 INFERIDO

### 2.2. Financeiro e Cobrança
- **Inadimplência Forçada:** Ao iniciar uma negociação, o operador é obrigado a selecionar **todos** os títulos vencidos do cliente. O sistema bloqueia a gravação se houver títulos vencidos pendentes de seleção. 🟢 CONFIRMADO
- **Destaque Visual:** Títulos com atraso superior a 0 dias devem ser exibidos com fundo amarelo (`#FFF9A7`) e valores em vermelho nos grids de listagem. 🟢 CONFIRMADO
- **Situação de Carteira:** A situação do título (0 a 6) determina o fluxo de cobrança (Simples, Advogado, Judicial). 🟢 CONFIRMADO

### 2.3. Comercial e Metas
- **Hierarquia de Metas:** Uma meta mensal de faturamento pode ser desdobrada em metas por categoria de produto. Não é permitido excluir a meta principal se houver desdobramentos. 🟢 CONFIRMADO
- **Média de Venda (MVC):** O sistema calcula a performance do cliente comparando o mês atual contra a média ponderada dos últimos 3 meses. 🟢 CONFIRMADO

### 2.4. Segurança e Acesso
- **Multi-unidade (Filiais):** O sistema suporta múltiplas unidades. O usuário pode ser restrito a ver dados apenas de sua unidade ou escolher a unidade no login. 🟢 CONFIRMADO
- **RBAC (Role Based Access Control):** O acesso a dashboards e relatórios é filtrado pelo papel do usuário (Vendedor vs Supervisor). Um vendedor nunca vê dados de outros vendedores, a menos que possua papel de supervisor. 🟢 CONFIRMADO
