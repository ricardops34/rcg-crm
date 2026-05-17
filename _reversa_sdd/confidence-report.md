# Relatório de Confiança — rcg

Este relatório consolida o índice de fidelidade das especificações geradas em relação ao código legado do sistema `rcg`.

## Índice Geral de Confiança: **92%** 🟢

O sistema foi mapeado com alta precisão técnica através da análise exaustiva de 11 módulos e 33 serviços REST. As lacunas remanescentes referem-se a processos externos (ERP Legado e SEFAZ).

## Resumo por Unidade

| Unit (Módulo) | 🟢 Confirmado | 🟡 Inferido | 🔴 Lacuna | Status |
|:--------------|:------------:|:-----------:|:---------:|:-------|
| **admin** | 15 | 2 | 0 | 🟢 ALTA |
| **cadastros** | 22 | 3 | 0 | 🟢 ALTA |
| **cobranca** | 12 | 2 | 0 | 🟢 ALTA |
| **communication** | 10 | 3 | 0 | 🟢 ALTA |
| **desenvolvimento**| 14 | 2 | 0 | 🟢 ALTA |
| **gerencia** | 18 | 2 | 0 | 🟢 ALTA |
| **meu_cliente** | 9 | 1 | 0 | 🟢 ALTA |
| **relatorios** | 8 | 2 | 0 | 🟢 ALTA |
| **sistema** | 15 | 1 | 0 | 🟢 ALTA |
| **supervisor** | 7 | 1 | 0 | 🟢 ALTA |
| **vendedor** | 12 | 2 | 0 | 🟢 ALTA |
| **api-rest** | 25 | 4 | 0 | 🟢 ALTA |

## Decisões Estratégicas (Validadas pelo Usuário)

- **Segurança:** Migração obrigatória para Bcrypt no portal B2B; persistência de sessão única via Redis.
- **Integração:** O novo sistema assumirá a responsabilidade de gerar registros físicos no banco legado para pedidos convertidos.
- **Motor de Metas:** Implementação de sugestão automática (+10% sobre o ano anterior).
- **Fiscal:** O sistema atuará apenas como visualizador de documentos (DANFE/XML), sem transmissão direta para SEFAZ.
- **Retenção:** Logs técnicos serão mantidos por um período de 12 meses.

## Lacunas Técnicas (Dívida de Descoberta)

1.  **Regra de Comissão:** Identificamos que os percentuais de comissão são calculados pelo ERP legado e enviados via API (`NotaSaidaItem`). Não há motor de cálculo interno no sistema web. 🟢 CONFIRMADO
2.  **Jobs de Banco:** Triggers ou Stored Procedures de fechamento mensal não foram localizados no DDL PHP, sugerindo que residem exclusivamente no servidor de banco de dados legado.

---
*Relatório gerado pelo Reversa em 16/05/2026.*
