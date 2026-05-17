# Gerência — Casos de Borda (Edge Cases)

## 1. Vendedor sem Meta Definida
- **Cenário:** Um vendedor está ativo e faturando, mas o gestor não cadastrou o registro em `meta_vendedor_mes` para o mês corrente.
- **Comportamento Legado:** No ranking do dashboard, o campo `vlr_objetivo` aparece como `0` ou `null`. O cálculo do percentual de atingimento resulta em `0%` (devido ao `CASE WHEN` na View SQL que evita divisão por zero). 🟢
- **Risco:** Desmotivação do vendedor e inconsistência no ranking global.

## 2. Produto sem Categoria Vinculada
- **Cenário:** Um item é faturado, mas o cadastro do produto está com `categoria_id` nulo ou inválido.
- **Comportamento Legado:** O valor da venda entra no total global do vendedor, mas "desaparece" do gráfico de "Vendas por Categoria". 🟢
- **Impacto:** O supervisor vê o total faturado, mas a soma das categorias não bate com o total do vendedor.

## 3. Meta por Categoria Maior que a Meta Global
- **Cenário:** O gestor define uma meta global de R$ 10.000,00, mas soma R$ 15.000,00 nos detalhamentos por categoria.
- **Comportamento Legado:** O sistema não bloqueia. Os indicadores de atingimento por categoria e global funcionarão de forma independente, baseados em seus respectivos `vlr_objetivo`. 🟡
- **Nota:** Útil para forçar "super-atingimento" em linhas específicas.

## 4. Vendedor Desligado com Vendas no Mês
- **Cenário:** O vendedor faturou na primeira quinzena e foi desligado na segunda.
- **Comportamento Legado:** O status `desligado = 'S'` oculta o vendedor de novos atendimentos, mas a View de BI mantém os dados históricos para o fechamento do mês, permitindo que o supervisor veja o realizado até a data da demissão. 🟢
