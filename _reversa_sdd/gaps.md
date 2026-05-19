# Lacunas Resolvidas e Pendentes — rcg

## Severidade: Crítica 🔴
*Nenhuma lacuna crítica pendente. Todas as dúvidas de arquitetura e segurança foram resolvidas nas rodadas de perguntas.*

## Severidade: Moderada 🟡

### G-01: Cálculo de Juros na Cobrança
- **Descrição:** O sistema não possui a fórmula de cálculo de juros/multa parametrizada no PHP.
- **Status:** Resolvido via análise de banco. A inteligência reside na `view_cliente_saldo_titulo`. O rebuild deve replicar as subqueries de saldo aberto/vencido. 🟢

### G-02: Conversão de Orçamento para Pedido
- **Descrição:** Lógica de conversão manual/automática entre tabelas de bancos diferentes.
- **Status:** Confirmado vínculo de FK no banco. O Rebuild deve realizar a persistência dupla para manter legado. 🟢

### G-04: Jobs e Triggers
- **Descrição:** Suspeita de triggers ou procedures ocultas.
- **Status:** Resolvido. A análise do dump SQL `erp_online-pgsql.sql` não revelou gatilhos ativos. A inteligência de BI é 100% baseada em Views. 🟢


## Severidade: Cosmética 🔵

### G-03: Geolocalização de Atendimentos
- **Descrição:** Ausência de evidência de captura de coordenadas GPS no CRM legado.
- **Status:** Mantido fora do escopo inicial de rebuild.
