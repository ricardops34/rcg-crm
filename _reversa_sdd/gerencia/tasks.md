# Gerência, Tarefas de Implementação

## Pré-requisitos
- [ ] Views de BI implementadas no banco de dados (Evolução, Metas, Dashboard).
- [ ] Módulo `admin` configurado para diferenciar perfis de Supervisor e Admin.
- [ ] Biblioteca de gráficos (Chart.js ou equivalente) disponível no frontend.

## Tarefas

- [ ] T-01, Implementar Motor de Metas (Mestre-Detalhe)
  - Origem no legado: `app/model/MetaVendedorMes.php` e `MetaVendedorCategoria.php`
  - Critério de pronto: Permitir salvar uma meta financeira mensal e vincular metas parciais por categoria.
  - Confiança: 🟢

- [ ] T-02, Criar Dashboard Gerencial Consolidado
  - Origem no legado: `app/control/gerencia/DashboardGerencia.php`
  - Critério de pronto: Exibir KPIs de Sugestão de Venda, Ticket Médio e Ranking de Vendedores com filtro de período.
  - Confiança: 🟢

- [ ] T-03, Relatório de Evolução de Venda (Pivot)
  - Origem no legado: `app/control/gerencia/VendaMesClienteList.php`
  - Critério de pronto: Componente de grid que renderiza os 12 meses do ano horizontalmente a partir de dados pivoteados do SQL.
  - Confiança: 🟢

- [ ] T-04, Gestão de Estrutura de Vendas (Hierarchy)
  - Origem no legado: `app/model/Vendedor.php` e `SupervisorVendedor.php`
  - Critério de pronto: Manter cadastro de vendedores e gerir os vínculos de supervisão para filtragem de dados.
  - Confiança: 🟢

- [ ] T-05, Integração de Ranking de Performance
  - Origem no legado: View `view_vendedor_venda_mes`
  - Critério de pronto: Calcular e exibir o delta entre Realizado vs Meta (%) em listas e gráficos.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Validar se um Supervisor consegue ver apenas os vendedores da sua equipe (conforme permissões RBAC).
- [ ] TT-02, Inserir uma venda e verificar se o indicador de "Atingimento" do Dashboard é atualizado (via View).
- [ ] TT-03, Testar a trava de deleção de Meta que possua categorias vinculadas.
- [ ] TT-04, Comparar o valor total do Relatório Pivot com o somatório de notas fiscais do período (Consistência de BI).

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar histórico de metas e estrutura de supervisores legada.

## Ordem Sugerida
1. T-04: Estrutura de pessoal é a base dos filtros.
2. T-01: Metas fornecem os objetivos (benchmarks).
3. T-02 e T-03: Camada de visualização estratégica.

## Lacunas Pendentes (🔴)
- Validar se o novo sistema deve permitir o cálculo de metas dinâmicas (ex: meta do mês = venda do ano passado + 10%) ou manter o padrão de inserção manual.
