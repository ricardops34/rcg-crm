# Ambiguity Log — Reversa

Este documento registra regras de negócio, lacunas ou processos do legado RCG que requerem decisão humana antes ou durante a implementação no novo sistema.

| ID | Ambiguidade / Lacuna | Impacto | Recomendação do Curator | Status |
|----|----------------------|---------|-------------------------|--------|
| AMB-01 | Trava de Sessão Única | Médio | Implementar via Redis para permitir escalabilidade horizontal (conforme ADR 004). | AGUARDANDO |
| AMB-02 | Sugestão Dinâmica de Metas | Baixo | Manter inserção manual no MVP; adicionar lógica "Ano Anterior + X%" em fase posterior. | AGUARDANDO |
| AMB-03 | Lógica de Comissão | Alto | Investigar se a regra reside no ERP Totvs (integração) ou se deve ser replicada no Reversa. | AGUARDANDO |
| AMB-04 | Retenção de Logs | Baixo | Definir política de expurgo automático (ex: 6 meses) para evitar crescimento excessivo do banco. | AGUARDANDO |
| AMB-05 | Níveis de Supervisão | Médio | Suportar estrutura extensível (Gerente -> Supervisor -> Vendedor) no código, mesmo que o legado use apenas dois níveis. | AGUARDANDO |
| AMB-06 | Simulação de Juros/Multa | Médio | Adicionar cálculo dinâmico de juros simples no frontend (Angular) para simulações de negociação. | AGUARDANDO |
| AMB-07 | Indicador de Clientes Órfãos | Baixo | Implementar alerta no Dashboard Gerencial para clientes sem vendedor ativo ou sem supervisor vinculado. | AGUARDANDO |
