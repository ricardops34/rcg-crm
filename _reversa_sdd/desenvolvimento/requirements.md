# Desenvolvimento — Requisitos

## Visão Geral
Esta unit atua como uma área de "sandbox" e prototipação da equipe técnica. Ela contém implementações experimentais, como o novo motor de orçamentos (Master-Detail), geração de boletos via bibliotecas externas e componentes de busca otimizados para a força de vendas.

## Responsabilidades
- Prototipar um novo fluxo de emissão de Orçamentos de Venda. 🟢
- Fornecer componentes de busca avançada (Seek Windows) para produtos, consolidando estoque e preço. 🟢
- Experimentar integrações externas (ex: OpenBoleto para Bradesco). 🟢
- Testar novas interfaces de visualização (ex: ProdutoCard para mobile). 🟢

## Regras de Negócio
- **Preenchimento Autônomo:** Ao selecionar um cliente no novo formulário de Orçamento, o sistema deve preencher automaticamente a tabela de preço, a condição de pagamento e o vendedor associados ao cadastro do cliente. 🟢
- **Cálculo de Orçamento:** O valor total do orçamento é a soma do preço total de seus itens (Preço Unitário * Quantidade - Descontos). 🟢
- **Conversão de Estado:** Um orçamento deve possuir um ciclo de vida (`OrcamentoEstado`), permitindo que a transição para o estado "Ganho" atue como gatilho para a geração de um Pedido definitivo. 🟡
- **Emissão de Boleto:** A geração de boletos experimentais exige dados do Cedente (Filial/Empresa) e Sacado (Cliente) para renderização HTML. 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Cabeçalho de Orçamento | Must | Permitir seleção de Cliente e herdar dados comerciais padrão. |
| RF-02 | Itens do Orçamento | Must | Permitir adição de múltiplos produtos com cálculo de totais em tempo real. |
| RF-03 | Busca de Produto (Seek) | Must | Exibir grid de produtos combinando saldo de estoque e preço vigente na tabela selecionada. |
| RF-04 | Emissão de Boleto | Could | Gerar boleto bancário (formato HTML/PDF) a partir de um Título a Receber. |
| RF-05 | Histórico de Orçamento | Should | Registrar todas as mudanças de status (Aberto -> Ganho) com log de usuário. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Usabilidade | Suporte a entrada em grid (Master-Detail) | `app/control/desenvolvimento/OrcamentoForm.php` | 🟢 |
| Integração | Uso de biblioteca externa via Composer | `app/control/desenvolvimento/BoletoBradesco.php` (OpenBoleto) | 🟢 |
| Performance | Views consolidadas para busca de estoque/preço | `app/database/erp_online-pgsql.sql` (`view_produto_estoque_preco`) | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Adição de Item no Orçamento
Dado um orçamento aberto vinculado a "Tabela de Preços Atacado"
Quando o vendedor usar o SeekWindow para buscar o "Produto A"
Então o sistema deve exibir apenas o preço do "Produto A" definido na "Tabela de Preços Atacado"
E mostrar o saldo disponível em estoque atual

Cenário: Conversão de Orçamento
Dado um orçamento no status "Aberto"
Quando o status for alterado para "Ganho" e o registro for salvo
Então o sistema deve disparar a criação de um "Pedido" correspondente
E vincular o ID do pedido gerado ao orçamento
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Motor de Orçamentos | Must | Essencial para modernizar o fluxo de vendas do ERP. |
| Busca de Produtos (Estoque/Preço)| Must | Evita venda de itens sem saldo ou com preço errado. |
| Histórico de Estados | Should | Rastreabilidade da negociação comercial. |
| Geração de Boletos | Could | Trata-se de uma prova de conceito (PoC) para um banco específico. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/control/desenvolvimento/OrcamentoForm.php` | `OrcamentoForm` | 🟢 |
| `app/model/Orcamento.php` | `Orcamento` | 🟢 |
| `app/control/desenvolvimento/BoletoBradesco.php` | `BoletoBradesco` | 🟢 |
| `app/control/desenvolvimento/ViewProdutoOrcamentoSeekWindow.php`| Busca de produtos | 🟢 |
