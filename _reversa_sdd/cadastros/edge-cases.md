# Cadastros — Casos de Borda (Edge Cases)

Este documento descreve o comportamento do sistema em situações extremas na unit de Cadastros.

## 1. Produto sem Preço na Tabela
- **Cenário:** Um produto está ativo, mas não foi incluído na `tabela_preco_item` de uma tabela específica vinculada ao cliente.
- **Comportamento Legado:** O sistema permite a seleção do produto no orçamento, mas exibe valor zero. 🟢
- **Risco:** Venda com valor incorreto por falha operacional de cadastro. No rebuild, recomenda-se travar a venda de produtos sem preço.

## 2. CNPJ Inválido ou Formatado Incorretamente
- **Cenário:** O usuário digita um CNPJ com máscara errada ou números que falham no algoritmo de dígito verificador.
- **Comportamento Legado:** O formulário possui validadores de máscara e validação no servidor via PHP. Se falhar, exibe mensagem de erro e impede o `store()`. 🟢
- **Risco:** Dados sujos na base caso a validação de banco (`CONSTRAINT`) não seja tão rigorosa quanto a do PHP.

## 3. Cliente Bloqueado com Pedido em Aberto
- **Cenário:** Um cliente é movido para o status 'B' (Bloqueado) enquanto possui um orçamento no estado 'Aberto'.
- **Comportamento Legado:** O sistema bloqueia a seleção do cliente para *novos* orçamentos, mas a conversão do orçamento antigo em pedido pode passar se não houver checagem no momento do faturamento. 🟡
- **Risco:** Faturamento indevido para inadimplentes.

## 4. Alteração de Categoria de Produto
- **Cenário:** Um produto é movido de uma categoria A para uma categoria B.
- **Comportamento Legado:** As metas de venda por categoria (Módulo Gerência) baseiam-se na categoria *atual* do produto. 🟢
- **Impacto:** Isso altera retroativamente os relatórios de performance histórica se o produto teve vendas na categoria antiga. 🟡
