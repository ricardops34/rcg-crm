# Desenvolvimento, Design Técnico

## Interface

### Models e Componentes Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `Orcamento` | Active Record | - | Cabeçalho da proposta comercial. 🟢 |
| `OrcamentoItem` | Active Record | - | Linhas de produtos do orçamento. 🟢 |
| `BoletoBradesco` | TPage | `HTML` | Renderiza boleto via biblioteca OpenBoleto. 🟢 |
| `ViewProdutoOrcamentoSeekWindow`| TWindow | - | Grid de busca com joins complexos de BD. 🟢 |

## Fluxo Principal (Orçamento de Venda)

1. **Início:** Acesso ao `OrcamentoForm`.
2. **Seleção de Cliente:**
    - O operador utiliza o `ClienteSeekWindow`.
    - Ao confirmar a seleção, métodos de `setExitAction` disparam requisições AJAX para preencher Vendedor, Condição de Pagamento e Tabela de Preços. 🟢
3. **Inclusão de Itens:**
    - O operador clica no botão para adicionar novo produto.
    - O `ViewProdutoOrcamentoSeekWindow` é aberto, filtrando produtos e exibindo preços baseados na Tabela selecionada no cabeçalho. 🟢
    - O operador insere Quantidade e Descontos. O JavaScript/PHP do grid recalcula os totais.
4. **Persistência:**
    - Ao salvar, o sistema processa os dados do cabeçalho em `Orcamento::store()`.
    - Itera sobre os itens do grid virtual, gravando registros em `OrcamentoItem`. 🟢
5. **Transições de Estado:**
    - Se o estado mudar (ex: Aberto para Ganho), um registro é criado em `OrcamentoHistorico`. 🟢

## Fluxos Alternativos

- **Emissão de Boleto (PoC):** 
    1. O controller `BoletoBradesco` recebe os parâmetros do título via URL.
    2. Instancia os objetos `Agente` (Cedente e Sacado) e `Bradesco` da biblioteca externa.
    3. Gera a renderização em HTML, salvando em `app/output/boleto.html` e exibindo em nova aba. 🟢

## Dependências

- **Adianti BuilderMasterDetailTrait:** Fornece a lógica pronta para manipular itens na memória antes do salvamento definitivo no banco. 🟢
- **OpenBoleto (Bacon/Barcode, DomPDF):** Dependências do Composer utilizadas na emissão bancária. 🟢
- **Database Views:** O grid de busca depende intimamente das views `view_produto_estoque_preco` e `view_precos`. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Delegação de joins para Views | `app/database/erp_online-pgsql.sql` | 🟢 |
| Uso de traits para grids 1:N | `app/control/desenvolvimento/OrcamentoForm.php` | 🟢 |
| Arquitetura de Estado Configurável| `app/model/OrcamentoEstado.php` | 🟢 |

## Estado Interno

O orçamento é stateful e guiado por um motor de transições simples:
- Tabela `orcamento_estado` define os status possíveis (Aberto, Ganho, Perdido).
- Tabela `orcamento_proximo_estado` atua como um mapa que restringe de quais status para quais status o usuário pode mover o orçamento (Workflow). 🟡

## Observabilidade

- Nenhuma estratégia específica de telemetria ou logging adicional encontrada nesta unit, além do padrão do framework.

## Riscos e Lacunas

- 🔴 **Conversão para Pedido:** O código de conversão automática de Orçamento Ganho em Pedido definitivo não está explícito no Form analisado, podendo depender de um gatilho de banco (Trigger) ou outra classe não coberta.
- 🟡 **Cálculo de Impostos:** Diferente da Nota Fiscal (Módulo Relatórios/Faturamento), o Orçamento parece tratar os itens apenas com "Valor Bruto", deixando cálculos complexos de ST, ICMS e IPI para o momento do faturamento no ERP legado.
