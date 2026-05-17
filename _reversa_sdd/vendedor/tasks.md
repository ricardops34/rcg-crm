# Vendedor, Tarefas de Implementação

## Pré-requisitos
- [ ] Unidades `cadastros` e `gerencia` (Metas) funcionais.
- [ ] Banco de dados `erp_online` com as views de pivoteamento de vendas.
- [ ] Sessão do usuário configurada para persistir `vendedor_id`.

## Tarefas

- [ ] T-01, Implementar Motor de Cálculo CRM (MVC)
  - Origem no legado: `app/model/Mvc.php`
  - Critério de pronto: Função capaz de calcular média móvel de compras (3 meses) e identificar quedas de consumo por cliente.
  - Confiança: 🟢

- [ ] T-02, Criar Dashboard Pessoal do Vendedor
  - Origem no legado: `app/control/vendedor/DashboardVendedor.php`
  - Critério de pronto: Exibir KPIs de performance travados para o ID do usuário logado, com gráficos de meta vs realizado.
  - Confiança: 🟢

- [ ] T-03, Sistema de Agenda de Atendimentos
  - Origem no legado: `app/model/Atendimento.php` e `AtendimentoForm.php`
  - Critério de pronto: Permitir agendar e registrar o relato de visitas/ligações com data e hora.
  - Confiança: 🟢

- [ ] T-04, Ficha 360 do Cliente (Posição)
  - Origem no legado: `app/control/vendedor/PosisaoClienteFormView.php`
  - Critério de pronto: Tela consolidada que exibe saldo financeiro, últimos pedidos e contatos em uma única visualização para o vendedor.
  - Confiança: 🟢

- [ ] T-05, Filtros Compulsórios de Segurança (Isolamento)
  - Origem no legado: Lógica de `TCriteria` aplicada em todos os controladores da pasta `vendedor`.
  - Critério de pronto: Garantir que nenhuma query desta unit retorne dados de clientes que não pertençam ao vendedor logado.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Logar como Vendedor A e tentar acessar o dashboard do Vendedor B via alteração de parâmetro (deve ser bloqueado).
- [ ] TT-02, Validar se o cálculo da "Diferença MVC" bate com a subtração matemática manual do histórico de faturamento.
- [ ] TT-03, Testar sobreposição de horários na agenda de atendimentos.
- [ ] TT-04, Verificar se o indicador de "Clientes Positivados" conta corretamente apenas um registro por CNPJ no mês.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar histórico de atendimentos e visitas.

## Ordem Sugerida
1. T-05: Segurança é o requisito nº 1.
2. T-02: Fornece o feedback de valor imediato para o usuário final.
3. T-01 e T-04: Inteligência comercial.
4. T-03: Organização operacional.

## Lacunas Pendentes (🔴)
- Definir como serão tratados os vendedores que atendem clientes compartilhados (se existir este cenário no negócio).
