---
schemaVersion: 1
generatedAt: 2026-05-19T16:15:00Z
reversa:
  version: "1.2.39"
kind: handoff
producedBy: orchestrator
hash: "sha256:TODO"
---

# Handoff para o Agente de Codificação

> Este documento é a porta de entrada para a implementação do novo sistema RCG em NestJS e Angular + PO-UI. Ele sintetiza todas as decisões do Time de Migração Reversa.

## ⚠️ Leitura obrigatória primeiro

1. **`paradigm_decision.md`**: O paradigma alvo é **Híbrido (Equilibrado)**. Combina Injeção de Dependências (DI) com padrões Active Record (TypeORM BaseEntity) para manter performance e paridade.
2. **`topology_decision.md`**: A topologia escolhida é **Híbrida**. Use DDD com Bounded Contexts para módulos core e organização simples para cadastros.
3. **`screen_modernization_decision.md`**: O modo de tradução de telas é **Híbrido**. Dashboards são modernizados (PO-UI reativo) e CRUDs são preservados (PO-UI funcional).

## Lista de artefatos produzidos

| Artefato | Produzido por | Status |
|---|---|---|
| migration_brief.md | orchestrator | ✅ Criado |
| paradigm_decision.md | paradigm_advisor | ✅ Criado |
| target_business_rules.md | curator | ✅ Criado |
| discard_log.md | curator | ✅ Criado |
| migration_strategy.md | strategist | ✅ Criado |
| risk_register.md | strategist | ✅ Criado |
| cutover_plan.md | strategist | ✅ Criado |
| topology_decision.md | designer | ✅ Criado |
| target_architecture.md | designer | ✅ Criado |
| target_domain_model.md | designer | ✅ Criado |
| target_data_model.md | designer | ✅ Criado |
| data_migration_plan.md | designer | ✅ Criado |
| screen_modernization_decision.md | screen_translator | ✅ Criado |
| target_screens.md | screen_translator | ✅ Criado |
| parity_specs.md | inspector | ✅ Criado |
| parity_tests/*.feature | inspector | ✅ 4 arquivos |
| ambiguity_log.md | orchestrator | ✅ Consolidado |

## Bloqueadores para começar a implementação
- **Nenhum bloqueador**. Todas as 8 ambiguidades detectadas foram resolvidas e marcadas no `ambiguity_log.md`.

## Próximos passos para o agente de codificação

1. **Configurar o Backend (NestJS)**:
   - Basear na estrutura de módulos definida em `target_architecture.md`.
   - Implementar `auth` com JWT/Redis e migração transparente de senhas (MD5 -> Bcrypt).
   - Configurar TypeORM com suporte a `BaseEntity` para os módulos `master-data`.
2. **Configurar o Frontend (Angular + PO-UI)**:
   - Criar os componentes de Dashboard utilizando `po-chart` e `po-widget`.
   - Implementar os cadastros utilizando `po-dynamic-form` para máxima paridade funcional.
3. **Implementar a Ponte de Roteamento (Traefik)**:
   - Configurar o roteamento por paths conforme definido no `migration_strategy.md` (Strangler Fig).
4. **Validar Paridade**:
   - Rodar os testes Gherkin em `parity_tests/` para garantir que as regras de cobrança (centavos) e permissões batem com o legado.

## Notas finais
O sistema está pronto para ser construído de forma incremental. Comece pela **Fase 1 (Admin/Auth)** para estabelecer a fundação de segurança e identidade, permitindo a coexistência com o legado via Strangler Fig.
