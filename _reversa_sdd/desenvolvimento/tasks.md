# Desenvolvimento, Tarefas de Implementação

## Pré-requisitos
- [ ] Módulo `cadastros` completo (Cliente, Produto, Tabelas de Preço).
- [ ] Views de banco de dados para busca de produtos implementadas.
- [ ] Biblioteca para geração de boletos configurada no projeto alvo.

## Tarefas

- [ ] T-01, Motor de Orçamentos (Cabeçalho e Itens)
  - Origem no legado: `app/model/Orcamento.php` e `app/model/OrcamentoItem.php`
  - Critério de pronto: Implementar o modelo relacional 1:N permitindo a gravação atômica da proposta comercial.
  - Confiança: 🟢

- [ ] T-02, Formulário Mestre-Detalhe de Venda
  - Origem no legado: `app/control/desenvolvimento/OrcamentoForm.php`
  - Critério de pronto: Formulário com carregamento assíncrono (AJAX) de dados do cliente e cálculos em tempo real no grid de produtos.
  - Confiança: 🟢

- [ ] T-03, Motor de Estados e Histórico (Workflow)
  - Origem no legado: `app/model/OrcamentoEstado.php` e `app/model/OrcamentoHistorico.php`
  - Critério de pronto: Permitir que o orçamento mude de status (Aberto -> Ganho) e grave o log dessa transição.
  - Confiança: 🟢

- [ ] T-04, Busca Avançada de Produtos (Estoque/Preço)
  - Origem no legado: `app/control/desenvolvimento/ViewProdutoOrcamentoSeekWindow.php`
  - Critério de pronto: Tela modal de busca que cruza produtos com a tabela de preço selecionada no cabeçalho do orçamento, exibindo saldo em estoque.
  - Confiança: 🟢

- [ ] T-05, Prova de Conceito: Geração de Boleto (Bradesco)
  - Origem no legado: `app/control/desenvolvimento/BoletoBradesco.php`
  - Critério de pronto: Componente capaz de receber parâmetros financeiros e renderizar o PDF/HTML do boleto para impressão.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Criar um orçamento e adicionar 3 itens. Validar se o total do orçamento é a soma correta dos itens com desconto.
- [ ] TT-02, Alterar o cliente do orçamento e validar se a Tabela de Preço é recalculada ou avisada ao usuário.
- [ ] TT-03, Testar restrição de mudança de estado (ex: não permitir voltar de Ganho para Aberto se assim configurado).
- [ ] TT-04, Validar linha digitável e código de barras gerados no Boleto de teste.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar histórico de orçamentos antigos, garantindo a associação correta aos IDs de clientes e produtos.

## Ordem Sugerida
1. T-03 e T-01: Base de dados e infraestrutura do orçamento.
2. T-04: Dependência visual para permitir a inclusão fácil de itens.
3. T-02: Tela principal do fluxo de vendas.
4. T-05: Módulo de boleto é independente e pode ser paralisado.

## Lacunas Pendentes (🔴)
- Definir como será feita a conversão do Orçamento em Pedido. Se o novo sistema for o ERP principal, a lógica de criação de Pedido deverá ser migrada para dentro do endpoint de transição para o estado "Ganho".
