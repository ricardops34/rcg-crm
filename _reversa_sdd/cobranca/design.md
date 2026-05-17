# Cobrança, Design Técnico

## Interface

### Models e Views Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `TituloReceber` | Active Record | - | Mapeia tabela física `titulo_receber`. 🟢 |
| `Negociacao.onGerar` | `($param)` | `void` | Inicia o workflow de validação de títulos. 🟢 |
| `ViewTituloCliente` | View BI | - | Consolida dados de Cliente + Títulos + Atrasos. 🟢 |
| `ViewClienteSaldoTitulo`| View BI | - | Agrega somatórios de saldo `vencido` e `aberto`. 🟢 |

## Fluxo Principal (Negociação de Dívida)

1. **Acesso:** O operador abre `NegociacaoList`.
2. **Listagem:** O sistema carrega dados da `ViewTituloCliente`, aplicando transformadores de cor para itens em atraso (`#FFF9A7`). 🟢
3. **Seleção:** O operador seleciona um ou mais títulos via Checkbox.
4. **Validação Operacional (`onGerar`):** 
    - O sistema conta quantos títulos vencidos (saldo > 0 e venc_real < hoje) o cliente possui no banco.
    - Se a quantidade selecionada for menor que a quantidade total de vencidos, interrompe com `TMessage`. 🟢
5. **Confirmação:** Exibe diálogo de confirmação "Deseja gerar a negociação?".
6. **Efetivação (`onYes`):**
    - Inicia `TTransaction`.
    - Cria `Negociacao` (status='G').
    - Loop nos IDs selecionados: cria `NegociacaoTituloReceber` para cada um. 🟢
    - Fecha `TTransaction`.

## Fluxos Alternativos

- **Visualização de Detalhes:** Link na coluna de Numero do Título abre o `TituloReceberForm` em modo leitura. 🟢
- **Baixa de Título:** Ocorre fora desta unit (provavelmente via importação de arquivo de retorno bancário CNAB ou processo de caixa), refletindo imediatamente na `ViewTituloCliente` através do campo `saldo = 0`. 🟡

## Dependências

- **Adianti TDataGrid:** Utilizado para a listagem complexa com seleções. 🟢
- **Database Views:** Inteligência de agregação reside no PostgreSQL. 🟢
- **AtendimentoTipo:** Utiliza a constante `AtendimentoTipo::COBRANCA` para classificar o registro. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Abstração via Views de BI | `app/model/ViewTituloCliente.php` | 🟢 |
| Validação de negócio no Controller | `app/control/cobranca/NegociacaoList.php:onGerar` | 🟢 |
| Formatação condicional via Transformers | `NegociacaoList.php:setTransformer` | 🟢 |

## Estado Interno

- **Negociação:** Status fixo 'G' (Gerada) no legado. A evolução para 'Paga' ou 'Cancelada' parece ser controlada por integrações externas ou campos adicionais não mapeados nos formulários básicos. 🟡

## Observabilidade

- **SQL Log:** Rastreia a criação do cabeçalho de negociação e os itens vinculados. 🟢
- **Maiores Atrasos:** Monitorado via coluna calculada na `ViewClienteSaldoTitulo`. 🟢

## Riscos e Lacunas

- 🔴 **Cálculo de Juros/Multa:** O código PHP do `NegociacaoList` não aplica cálculos de juros em tempo real; ele apenas agrupa os saldos atuais. A aplicação de juros parece ocorrer no momento da emissão do boleto ou liquidação.
- 🟡 **Status do Título:** A coluna `situacao` (0 a 6) indica o portador, mas a regra de transição entre esses números (ex: enviar para Advogado) não está clara nos controllers analisados.
