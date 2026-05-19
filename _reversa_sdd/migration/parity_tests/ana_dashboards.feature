# language: pt
Funcionalidade: Dashboards de Performance (Analytics)
  Como um gestor ou vendedor
  Eu quero visualizar indicadores de performance (KPIs) modernizados
  Para garantir que os números exibidos sejam fiéis aos dados reais do legado

  Contexto:
    Dado que o vendedor "Vendedor 01" possui R$ 50.000,00 em vendas no mês atual
    E a sua meta é de R$ 100.000,00

  Cenário: Fidelidade de Dados no Dashboard Vendedor (Modernizado)
    Quando eu acesso o Dashboard do Vendedor no novo sistema
    Então o componente "po-chart" (Gauge) deve exibir 50% de atingimento
    E o componente "po-info" deve exibir o valor "R$ 50.000,00"
    E este valor deve bater exatamente com o resultado da Query SQL Legada de "vendas_vendedor_mes"

  Cenário: Reatividade de Filtros
    Quando eu altero o filtro de período para "Mês Anterior"
    Então o sistema deve realizar uma chamada assíncrona para a API v2 do NestJS
    E deve atualizar os gráficos sem recarregar a página inteira
    E os dados exibidos devem manter paridade com o relatório "Faturamento por Período" do sistema legado

  Cenário: Visão Hierárquica (Gerencial)
    Dado que sou um "Supervisor" com 3 vendedores sob minha gestão
    Quando eu acesso o Dashboard Gerencial
    Então o sistema deve consolidar os valores dos 3 vendedores corretamente
    E deve permitir o drill-down para a visão individual de cada vendedor mantendo a consistência dos dados
