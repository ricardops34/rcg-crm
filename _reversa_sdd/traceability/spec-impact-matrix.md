# Matriz de Impacto de Especificações (Spec Impact Matrix)

Este documento mapeia as dependências entre os componentes funcionais do sistema para orientar mudanças e evoluções.

| Componente | Impacta em | Razão do Impacto |
|------------|------------|------------------|
| **Admin (Segurança)** | Todos os módulos | Centraliza o RBAC; mudanças na sessão afetam o filtro de dados (escopo) de todos os módulos. |
| **Cadastros (Cliente)**| Vendedor, Cobrança, Relatórios | Alterações no modelo de Cliente afetam a agenda, dashboards CRM e a identificação fiscal nas notas. |
| **Cadastros (Produto)**| Desenvolvimento, Relatórios | Mudanças em Produto impactam o motor de Orçamentos e o detalhamento de itens de Nota Fiscal. |
| **Metas (Gerência)** | Vendedor | A alteração na estrutura de metas impacta diretamente o dashboard e ranking de performance dos vendedores. |
| **Cobrança** | Meu Cliente | Mudanças na regra de títulos vencidos afetam a visualização financeira no portal B2B do cliente. |
| **Sistema (Logs)** | Todos os módulos | A desativação ou mudança nas Traits de Auditoria remove a rastreabilidade de escrita de todo o ERP. |
| **Sistema (Parâmetros)**| Relatórios, Desenvolvimento | Parâmetros como URLs de webservice ou flags de faturamento afetam a emissão de DANFEs e conversão de orçamentos. |

## Relações Críticas (Cadeia de Valor)

1.  **Fluxo de Venda:** `Produto` -> `Tabela Preço` -> `Orçamento` -> `Pedido` -> `Nota Saída`.
2.  **Fluxo de Recebimento:** `Nota Saída` -> `Título Receber` -> `Negociação`.
3.  **Fluxo de Gestão:** `Vendedor` -> `Supervisor` -> `Meta` -> `Dashboard Gerência`.
