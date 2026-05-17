# Lacunas Resolvidas e Pendentes — rcg

## Severidade: Crítica 🔴
*Nenhuma lacuna crítica pendente. Todas as dúvidas de arquitetura e segurança foram resolvidas nas rodadas de perguntas.*

## Severidade: Moderada 🟡

### G-01: Cálculo de Juros na Cobrança
- **Descrição:** O sistema não possui a fórmula de cálculo de juros/multa parametrizada no PHP.
- **Status:** Resolvido operacionalmente. O rebuild deve manter o saldo seco na lista e permitir simulação manual no detalhe.

### G-02: Conversão de Orçamento para Pedido
- **Descrição:** Lógica de conversão manual/automática entre tabelas de bancos diferentes.
- **Status:** Planejado para ser implementado como escrita física no banco legado.

## Severidade: Cosmética 🔵

### G-03: Geolocalização de Atendimentos
- **Descrição:** Ausência de evidência de captura de coordenadas GPS no CRM legado.
- **Status:** Mantido fora do escopo inicial de rebuild.
