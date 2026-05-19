# Ambiguity Log — Reversa

Este documento registra regras de negócio, lacunas ou processos do legado RCG que requerem decisão humana antes ou durante a implementação no novo sistema.

| ID | Ambiguidade / Lacuna | Impacto | Recomendação do Curator | Status |
|----|----------------------|---------|-------------------------|--------|
| AMB-01 | Trava de Sessão Única | Médio | Resolvido: Mover para Redes/JWT sob paradigma Híbrido (ADM-07). | RESOLVIDO |
| AMB-02 | Sugestão Dinâmica de Metas | Baixo | Manter inserção manual no MVP; adicionar lógica "Ano Anterior + X%" em fase posterior. | AGUARDANDO |
| AMB-03 | Lógica de Comissão | Alto | Investigar se a regra reside no ERP Totvs (integração) ou se deve ser replicada no Reversa. | AGUARDANDO |
| AMB-04 | Retenção de Logs | Baixo | Definir política de expurgo automático (ex: 6 meses) para evitar crescimento excessivo do banco. | AGUARDANDO |
| AMB-05 | Níveis de Supervisão | Médio | Resolvido: Implementar hierarquia recursiva no backend (VEN-05). | RESOLVIDO |
| AMB-06 | Simulação de Juros/Multa | Médio | Resolvido: Implementar no Frontend com validação Backend (COB-06). | RESOLVIDO |
| AMB-07 | Indicador de Clientes Órfãos | Baixo | Resolvido: Implementar via Job/Alertas (GER-04). | RESOLVIDO |
| AMB-08 | Consistência de Views Pivot | Médio | As views pivot (`pivot_vendas`) devem ser substituídas por agregação em nível de aplicação ou BI especializado? | AGUARDANDO |
