# Plano de Exploração — rcg

> Criado pelo Reversa em 2026-05-15
> Marque cada tarefa com ✅ quando concluída.
> Você pode editar este plano antes de iniciar: adicione, remova ou reordene tarefas conforme necessário.

---

## Fase 1: Reconhecimento 🔍

- [x] **Scout** — Mapeamento de estrutura de pastas e tecnologias
- [x] **Scout** — Análise de dependências e gerenciadores de pacotes
- [x] **Scout** — Identificação de entry points, CI/CD e configurações

## Decisão de organização das specs 🗂️

> Entre o Scout e o Arqueólogo, o Reversa pergunta como você quer organizar as specs (por módulo, caso de uso, endpoint, híbrida, por features ou customizada). A escolha fica persistida em `.reversa/config.toml` na seção `[specs]` e não será reperguntada em execuções futuras. Para reapresentar o menu, remova manualmente a seção.

## Fase 2: Escavação 🏗️

- [x] **Arqueólogo** — Análise do módulo `admin`
- [x] **Arqueólogo** — Análise do módulo `cadastros`
- [x] **Arqueólogo** — Análise do módulo `cobranca`
- [x] **Arqueólogo** — Análise do módulo `vendedor`
- [x] **Arqueólogo** — Análise do módulo `gerencia`
- [x] **Arqueólogo** — Análise do módulo `supervisor`
- [x] **Arqueólogo** — Análise do módulo `meu_cliente`
- [x] **Arqueólogo** — Análise do módulo `relatorios`
- [x] **Arqueólogo** — Análise do módulo `communication`
- [x] **Arqueólogo** — Análise do módulo `sistema`
- [x] **Arqueólogo** — Análise do módulo `desenvolvimento`

## Fase 3: Interpretação 🧠

- [x] **Detetive** — Arqueologia Git e ADRs retroativos
- [x] **Detetive** — Regras de negócio implícitas e máquinas de estado
- [x] **Detetive** — Matriz de permissões (RBAC/ACL)
- [x] **Arquiteto** — Diagramas C4 (Contexto, Containers, Componentes)
- [x] **Arquiteto** — ERD completo e integrações externas
- [x] **Arquiteto** — Spec Impact Matrix

## Fase 4: Geração 📝

- [x] **Redator** — Geração de Especificações Executáveis (SDD)

📋 Plano de geração, 11 units, 52 arquivos no total ✅

Units:
  [x] 1. **admin**/requirements.md, design.md, tasks.md, edge-cases.md, flows.md, contracts.md
  [x] 7. **cadastros**/requirements.md, design.md, tasks.md, edge-cases.md, flows.md
  [x] 8. **cobranca**/requirements.md, design.md, tasks.md, edge-cases.md, flows.md
  [x] 9. **communication**/requirements.md, design.md, tasks.md, edge-cases.md, contracts.md
  [x] 10. **desenvolvimento**/requirements.md, design.md, tasks.md, edge-cases.md
  [x] 11. **gerencia**/requirements.md, design.md, tasks.md, edge-cases.md, flows.md
  [x] 12. **meu_cliente**/requirements.md, design.md, tasks.md, edge-cases.md, contracts.md
  [x] 13. **relatorios**/requirements.md, design.md, tasks.md, edge-cases.md, contracts.md
  [x] 14. **sistema**/requirements.md, design.md, tasks.md, edge-cases.md
  [x] 15. **supervisor**/requirements.md, design.md, tasks.md, edge-cases.md
  [x] 16. **vendedor**/requirements.md, design.md, tasks.md, edge-cases.md, flows.md
  [x] 17. **api-rest**/requirements.md, design.md, tasks.md

Globais:
  [x] 18. **openapi**/api.yaml
  [x] 18. **user-stories**/fluxo-venda.md
  [x] 19. **user-stories**/fluxo-cobranca.md
  [x] 20. **traceability**/code-spec-matrix.md

- [x] **Redator** — OpenAPI (se aplicável)
- [x] **Redator** — User Stories (se aplicável)
- [x] **Redator** — Code/Spec Matrix

## Fase 5: Revisão ✅

- [x] **Revisor** — Revisão cruzada de specs
- [x] **Revisor** — Resolução de lacunas com o usuário
- [x] **Revisor** — Relatório de confiança final

---

## Agentes Independentes

> Execute estes agentes quando os recursos estiverem disponíveis — podem rodar em qualquer fase.

- [x] **Visor** — Análise de interface via screenshots
- [x] **Data Master** — Análise completa do banco de dados
- [ ] **Design System** — Extração de tokens de design
- [ ] **Tracer** — Análise dinâmica (requer sistema acessível)

---

## Próximo passo

Após o Time de Descoberta concluir e o `_reversa_sdd/` estar populado, você pode disparar um dos fluxos seguintes:

- `/reversa-migrate`: orquestrador do **Time de Migração** (Paradigm Advisor → Curator → Strategist → Designer → Screen Translator → Inspector). Gera as specs do sistema novo. Saída em `_reversa_sdd/migration/` e `_reversa_sdd/screens/`.
- `/reversa-reconstructor`: gera plano bottom-up para reimplementar o software a partir das specs do legado (uma tarefa por sessão).
