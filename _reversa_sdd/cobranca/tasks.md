# Cobrança, Tarefas de Implementação

## Pré-requisitos
- [ ] Banco de dados `erp_online` com suporte a Views SQL.
- [ ] Tabelas `titulo_receber`, `negociacao` e `negociacao_titulo_receber` criadas.
- [ ] Módulo `cadastros` (Cliente) funcional.

## Tarefas

- [ ] T-01, Implementar Entidades Financeiras (Títulos)
  - Origem no legado: `app/model/TituloReceber.php`
  - Critério de pronto: Model capaz de representar faturas com `saldo`, `vencimento` e `situacao`.
  - Confiança: 🟢

- [ ] T-02, Criar Views de Agregação de Saldo (BI)
  - Origem no legado: `app/database/erp_online-pgsql.sql:CREATE VIEW view_cliente_saldo_titulo`
  - Critério de pronto: O banco deve retornar somatórios de `vencido`, `aberto` e `MaiorAtraso` por cliente automaticamente.
  - Confiança: 🟢

- [ ] T-03, Criar Listagem de Cobrança com Destaques Visuais
  - Origem no legado: `app/control/cobranca/NegociacaoList.php`
  - Critério de pronto: Grade de títulos que colore linhas em atraso e permite seleção via checkbox.
  - Confiança: 🟢

- [ ] T-04, Implementar Motor de Validação de Negociação
  - Origem no legado: `app/control/cobranca/NegociacaoList.php:onGerar()`
  - Critério de pronto: Bloquear a gravação se o operador não selecionar todos os títulos vencidos do cliente.
  - Confiança: 🟢

- [ ] T-05, Workflow de Registro de Acordo
  - Origem no legado: `app/model/Negociacao.php`
  - Critério de pronto: Persistir o cabeçalho da negociação e os vínculos N:N com os títulos selecionados.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Validar se um cliente com saldo 0 não aparece na lista de inadimplentes.
- [ ] TT-02, Forçar falha de validação tentando negociar apenas 1 de 2 títulos vencidos.
- [ ] TT-03, Verificar se os transformadores de cor (`CSS`) aplicam o estilo correto para títulos com vencimento hoje.
- [ ] TT-04, Garantir que a negociação gera um log de atendimento vinculado (CRM).

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar faturas em aberto (`saldo > 0`) garantindo a consistência dos campos `venc_real` e `venc_orig`.

## Ordem Sugerida
1. T-02: As Views são o coração da lógica de cobrança.
2. T-01 e T-03: Permitem a visualização da inadimplência.
3. T-04 e T-05: Fecham o ciclo operacional de negociação.

## Lacunas Pendentes (🔴)
- Validar se a nova linguagem deve implementar o cálculo de juros/multas no frontend ou se manterá o padrão legado (cálculo apenas na emissão do boleto).
