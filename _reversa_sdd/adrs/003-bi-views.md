# ADR 003: Abstração de Dashboards via Views de BI

## Status
Aceito (Retroativo) 🟢

## Contexto
Dashboards como o do Vendedor e Gerência exigem cálculos complexos de agregação (sum, avg, count) e pivoteamento de meses. Executar essas lógicas diretamente no PHP via loops seria ineficiente e difícil de manter.

## Decisão
Mover a inteligência de cálculo de indicadores para a camada de banco de dados através de **Database Views** (`view_vendedor_venda_mes`, `view_base_cliente_mes`). O sistema Adianti consome essas views como se fossem tabelas comuns (`TRecord`), permitindo o uso de componentes visuais (`BIndicator`) sem escrever SQL complexo no Controller.

## Alternativas consideradas
- **Cálculo em Tempo de Execução (PHP):** Lento para grandes volumes de dados.
- **Tabelas de Cache (Materializadas):** Complexas de sincronizar em tempo real.

## Consequências
- **Positivas:** Performance otimizada (o banco faz o que sabe melhor); lógica de negócio centralizada no SQL; facilidade de reuso entre dashboards web e mobile.
- **Negativas:** Dificulta a portabilidade total entre bancos (as views podem conter dialetos específicos); a lógica de negócio fica "escondida" no banco.
