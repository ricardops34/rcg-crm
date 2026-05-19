# language: pt
Funcionalidade: Negociação de Títulos (Cobrança)
  Como um analista de cobrança
  Eu quero simular e efetivar negociações de títulos em atraso
  Para garantir que os cálculos de juros e multas sejam idênticos ao legado (Paridade de Centavos)

  Contexto:
    Dado que existe o título "123/01" com valor nominal de R$ 1.000,00
    E o título está vencido há 10 dias
    E a regra de juros é 1% ao mês e multa de 2%

  Cenário: Simulação de Juros e Multa (Modernizado)
    Quando eu seleciono o título "123/01" na tela de negociação
    E visualizo o resumo do cálculo no novo componente "po-grid"
    Então o valor da multa deve ser exatamente R$ 20,00 (2%)
    E o valor dos juros de mora deve ser exatamente R$ 3,33 (1% pro-rata)
    E o valor total atualizado deve ser R$ 1.023,33
    E estes valores devem ser idênticos aos gerados pela classe "TituloReceber" do legado

  Cenário: Efetivação de Acordo
    Dado que o analista concorda com o valor de R$ 1.023,33
    Quando eu clico em "Confirmar Negociação"
    Então o sistema deve criar um novo registro na tabela "tituloreceber_acordo"
    E deve atualizar a situação da carteira do título original para "5" (Em Negociação)
    E deve disparar o evento assíncrono de "Notificação de Acordo" via NestJS
