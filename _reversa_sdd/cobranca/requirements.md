# Cobrança — Requisitos

## Visão Geral
Esta unit gerencia os processos financeiros de Contas a Receber, focando na recuperação de créditos e negociação de dívidas. Ela fornece as ferramentas para que o departamento financeiro monitore a inadimplência e formalize acordos de pagamento.

## Responsabilidades
- Monitorar faturas e parcelas em aberto via Títulos a Receber. 🟢
- Identificar e destacar clientes inadimplentes. 🟢
- Agrupar débitos vencidos em Negociações formais. 🟢
- Registrar o histórico de tentativas de contato e cobrança. 🟢
- Calcular saldos, atrasos e maior atraso por cliente. 🟢

## Regras de Negócio
- **Obrigatoriedade de Seleção:** Ao gerar uma nova negociação, o sistema exige que *todos* os títulos vencidos do cliente sejam selecionados. Não é permitido negociar apenas parte do passivo vencido. 🟢
- **Status de Negociação:** Uma negociação nasce com o status 'G' (Gerada) e deve ser vinculada a um `atendimento_id`. 🟢
- **Cálculo de Atraso:** O atraso é contado em dias corridos a partir da data de `venc_real` até o `curdate()`. 🟢
- **Destaque Visual:** Títulos com saldo > 0 e vencimento <= hoje devem ser exibidos com formatação de alerta (Vermelho/Amarelo). 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Listagem de Inadimplência | Must | Exibir grid consolidado de clientes com saldo vencido e maior atraso. |
| RF-02 | Seleção de Títulos | Must | Permitir seleção múltipla de faturas para composição de acordo. |
| RF-03 | Geração de Negociação | Must | Criar registro em `negociacao` e vincular os títulos selecionados. |
| RF-04 | Consulta de Saldo | Must | Calcular em tempo real o somatório de aberto vs vencido. |
| RF-05 | Filtro por Vendedor | Should | Permitir filtrar inadimplentes por carteira de vendedor. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Performance | Uso de Views SQL para agregação de saldos | `_reversa_sdd/database/business-rules.md` | 🟢 |
| Usabilidade | Feedback visual de atraso (CSS dinâmico) | `app/control/cobranca/NegociacaoList.php` | 🟢 |
| Integridade | Chaves estrangeiras obrigatórias entre Negociação e Títulos | `app/database/erp_online-pgsql.sql` | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Tentativa de Negociação Parcial
Dado que o cliente possui 3 títulos vencidos
Quando o operador selecionar apenas 2 títulos e clicar em "Gerar"
Então o sistema deve exibir alerta "É obrigatório selecionar TODOS os títulos vencidos" e impedir a gravação

Cenário: Visualização de Saldo
Dado que um cliente possui R$ 500,00 vencidos e R$ 1.000,00 a vencer
Quando o financeiro abrir a tela de cobrança
Então o sistema deve exibir: Vencido = 500,00 / Aberto = 1.500,00 / Quantidade = nº total de faturas
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Controle de Títulos | Must | Base do Contas a Receber. |
| Bloqueio de Negociação Parcial | Must | Regra de integridade financeira do ERP. |
| Histórico de Atendimento | Should | Importante para CRM, mas secundário à liquidação financeira. |
| Filtro de Supervisor | Could | Facilita a gestão, mas não impede a operação de cobrança. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/model/TituloReceber.php` | `TituloReceber` | 🟢 |
| `app/control/cobranca/NegociacaoList.php`| `onGerar()` | 🟢 |
| `app/model/Negociacao.php` | `Negociacao` | 🟢 |
| `app/model/ViewTituloCliente.php` | `ViewTituloCliente` | 🟢 |
