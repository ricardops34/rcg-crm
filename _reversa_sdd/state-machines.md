# Máquinas de Estado — rcg

O sistema gerencia o ciclo de vida de entidades críticas através de campos de status e tabelas de histórico.

## 1. Ciclo de Vida do Orçamento

O orçamento inicia sua jornada como uma proposta e pode ser ganho (virando pedido) ou perdido.

```mermaid
stateDiagram-v2
    [*] --> Aberto
    Aberto --> Ganho: Aprovação do Cliente
    Aberto --> Perdido: Recusa ou Expiração
    Ganho --> Pedido: Faturamento
    Pedido --> [*]
    Perdido --> [*]
```

**Gatilhos:**
- **Ganho:** Acionado manualmente via `OrcamentoForm` ao mudar o status.
- **Pedido:** Conversão automática que gera o faturamento e gera o `pedido_id`.

## 2. Fluxo Financeiro (Título a Receber)

Os títulos transitam entre a emissão e a baixa efetiva, passando por cobrança se necessário.

```mermaid
stateDiagram-v2
    [*] --> A_Receber: Emissão da NF
    A_Receber --> Recebido: Pagamento (Baixa)
    A_Receber --> Em_Atraso: Vencimento > Hoje
    Em_Atraso --> Negociacao: Início de Cobrança
    Negociacao --> Recebido: Acordo Pago
    Recebido --> [*]
```

**Regras de Transição:**
- **Negociação:** Ao entrar em negociação, os títulos são agrupados e o status da negociação vira 'G'.
- **Bloqueio:** O cliente pode ser movido para o status 'B' (Bloqueado) se possuir títulos em atraso (implícito na lógica de cobrança).

## 3. Status de Usuário e Vendedor

Controle de acesso e atividade operacional.

```mermaid
stateDiagram-v2
    [*] --> Ativo
    Ativo --> Bloqueado: Inatividade ou Afastamento
    Bloqueado --> Ativo: Reativação
    Ativo --> Desligado: Demissão (Histórico)
    Desligado --> [*]
```

**Impacto:**
- **Bloqueado:** Impede o login ou oculta dados em dashboards analíticos.
- **Desligado:** Preserva os registros históricos de vendas e metas, mas remove o acesso e o dashboard.
