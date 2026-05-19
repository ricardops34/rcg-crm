# Risk Register — RCG Reversa

Este documento identifica, analisa e propõe mitigação para os riscos do projeto de migração RCG.

## 1. Riscos Técnicos

| ID | Risco | Impacto | Probabilidade | Mitigação |
| :--- | :--- | :--- | :--- | :--- |
| **TEC-01** | **Inconsistência de Sessão** | Alto | Alta | Implementar um "Identity Bridge". O NestJS emite JWT; o legado PHP deve validar este JWT ou ler a sessão do Redis. |
| **TEC-02** | **Divergência de Regra (Shadow Logic)** | Médio | Alta | Realizar "Shadow Testing" no módulo de Cobrança: processar no novo e comparar logs com o legado antes da virada. |
| **TEC-03** | **Performance do ORM (TypeORM)** | Médio | Média | Utilizar o padrão Active Record do TypeORM para queries simples e Query Builder para operações complexas/BI. |
| **TEC-04** | **Conflito de Escrita (Race Condition)** | Alto | Baixa | Evitar que ambos os sistemas editem os mesmos campos de uma tabela simultaneamente. Definir "Dono do Dado" por módulo migrado. |
| **TEC-05** | **Complexidade do Traefik** | Baixo | Média | Documentar rigorosamente as regras de roteamento (labels do Docker Compose) para evitar loops ou 404s. |

## 2. Riscos de Negócio

| ID | Risco | Impacto | Probabilidade | Mitigação |
| :--- | :--- | :--- | :--- | :--- |
| **BUS-01** | **Rejeição à nova UX** | Médio | Baixa | Utilizar PO-UI para manter consistência visual e realizar homologação antecipada com usuários do módulo Vendedor. |
| **BUS-02** | **Perda de Histórico de Auditoria** | Alto | Baixa | Garantir que o Change Log (CAD-02) do novo sistema escreva no mesmo formato ou tabela que o legado. |
| **BUS-03** | **Indisponibilidade em Cutover** | Alto | Média | Planejar janelas de manutenção curtas para a virada de roteamento de módulos críticos. |

## 3. Matriz de Prioridade

- **Críticos (Imediato)**: TEC-01 (Sessão), TEC-02 (Regras de Cobrança).
- **Importantes**: TEC-03 (Performance), BUS-01 (Adoção).
- **Monitorar**: TEC-05 (Infra).

## 4. Plano de Contingência (Rollback)

Para cada módulo migrado via Strangler Fig:
1. Manter o container do legado ativo.
2. Em caso de erro crítico no novo módulo, reverter a regra de Path no Traefik para apontar de volta ao legado.
3. Tempo estimado de rollback: < 2 minutos.
