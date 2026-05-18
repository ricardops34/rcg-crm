# Cadastros, Tarefas de Implementação

## Pré-requisitos
- [ ] Banco de dados `erp_online` criado com as tabelas `cliente`, `produto`, `tabela_preco`.
- [ ] Módulo `admin` implementado (necessário para logs e sessões).
- [ ] Mecanismo de persistência de arquivos (fotos de produtos) configurado.

## Tarefas

- [ ] T-01, Implementar Entidade Mestre de Cliente com Auditoria
  - Origem no legado: `app/model/Cliente.php` e `app/model/log/SystemChangeLogTrait.php`
  - Critério de pronto: O model deve salvar os dados e registrar o log de alterações automaticamente.
  - Confiança: 🟢

- [ ] T-02, Criar Catálogo de Produtos e Categorias
  - Origem no legado: `app/model/Produto.php`, `categoria.php` e `sub_categoria.php`
  - Critério de pronto: Implementar hierarquia de categorias e cadastro de produtos com suporte a NCM e Peso.
  - Confiança: 🟢

- [x] T-03, Implementar Motor de Tabelas de Preço
  - Origem no legado: `app/model/TabelaPreco.php` e `tabela_preco_item.php`
  - Critério de pronto: Consultar o preço unitário de um produto a partir de um ID de tabela específico.
  - Confiança: 🟢

- [ ] T-04, Criar Formulário de Cliente Multi-aba
  - Origem no legado: `app/control/cadastros/ClienteForm.php`
  - Critério de pronto: Tela capaz de gerenciar todas as informações do cliente e o mestre-detalhe de contatos em uma única transação.
  - Confiança: 🟢

- [ ] T-05, Integração de Dados ERP (cod_erp)
  - Origem no legado: `app/database/erp_online-pgsql.sql` (constraints unique)
  - Critério de pronto: Garantir que as chaves de integração externas sejam únicas e indexadas para performance.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Testar salvamento de cliente e validar criação de registro em `system_change_log`.
- [ ] TT-02, Validar bloqueio de gravação em caso de CNPJ duplicado.
- [ ] TT-03, Validar se produtos sem preço na tabela ativa são tratados corretamente.
- [ ] TT-04, Testar upload e redimensionamento de foto do produto.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar `cliente`, `produto` e `tabelas_preco` garantindo a integridade dos IDs legados (`cod_erp`).

## Ordem Sugerida
1. T-02 e T-03: Produtos e Preços são a base.
2. T-01: Clientes (com a trait de auditoria).
3. T-04: Interface de gestão.

## Lacunas Pendentes (🔴)
- Definir como será feita a validação de CNPJ externa se o webservice legado não estiver disponível (sugerir nova API).
