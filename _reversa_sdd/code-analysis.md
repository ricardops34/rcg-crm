# Análise Técnica: Módulo Desenvolvimento

O módulo `desenvolvimento` contém componentes em estágio de construção, protótipos de novas telas e ferramentas de teste da equipe técnica.

## 1. Motor de Orçamentos (OrcamentoForm)

Protótipo de um novo fluxo de vendas baseado em Orçamentos que podem ser convertidos em Pedidos.
- **Complexidade:** O `OrcamentoForm` utiliza o `BuilderMasterDetailTrait` para gerenciar os itens do orçamento em tempo real.
- **Buscas Inteligentes (SeekButtons):** Implementa janelas de busca avançada para Clientes e Produtos (`ClienteSeekWindow`, `ViewProdutoOrcamentoSeekWindow`), automatizando o preenchimento de campos como Tabela de Preço, Condição de Pagamento e Vendedor.
- **Cálculos:** Gerencia descontos, acréscimos e o valor total por item e consolidado.

## 2. Emissão de Boletos (BoletoBradesco)

Integração com a biblioteca `OpenBoleto` para geração de cobranças bancárias.
- **Fluxo:** Recupera os dados do título (`TituloReceber`), configura o Sacado (Cliente) e Cedente (Empresa), e gera a saída HTML do boleto para o Bradesco.
- **Saída:** O boleto é salvo em `app/output/boleto.html` e aberto via `TPage::openFile`.

## 3. Ferramentas de Manutenção e Protótipos

- **DashboardGerenciaClonado:** Cópia de segurança ou área de testes para modificações no painel estratégico.
- **ClienteAtualizacaoSimpleList:** Interface simplificada para visualizar solicitações de alteração de dados cadastrais.
- **ProdutoCard:** Uma visão em formato de "cards" para o catálogo de produtos, possivelmente focada em tablets ou visualização mobile.
- **ViewProdutoEstoquePrecoSeekWindow:** Ferramenta de busca específica que consolida Saldo em Estoque e Preço de Tabela em uma única visualização para o vendedor durante a venda.
