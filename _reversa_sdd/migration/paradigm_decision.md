---
schemaVersion: 1
generatedAt: 2026-05-19T14:50:00Z
reversa:
  version: "1.2.39"
kind: paradigm_decision
producedBy: paradigm_advisor
hash: "sha256:TODO"
---

# Paradigm Decision

> Decisão consciente sobre como tratar a mudança (ou ausência) de paradigma entre o legado e a stack alvo.
> Este artefato é leitura obrigatória primeiro para qualquer agente posterior e para o agente de codificação.

## Paradigma do legado detectado
- **Paradigma principal**: OO clássico
- **Confiança**: 🟢 CONFIRMADO
- **Evidências**:
  - Uso extensivo do Adianti Framework 4.2 (`_reversa_sdd/inventory.md`).
  - Padrão Active Record (`TRecord`) identificado no DDL e modelos (`_reversa_sdd/architecture.md`).
  - Herança forte em componentes de UI e Controllers (`_reversa_sdd/php-mapping.md`).
- **Variações observadas**:
  - Procedural: Scripts utilitários e de manutenção (`db-mad-manager.php`).

## Stack alvo declarada
- Linguagem: TypeScript (Node.js 20)
- Framework: NestJS (Backend) / Angular + PO-UI (Frontend)
- Infra: Docker

## Paradigma natural inferido
- **Paradigma**: OO com DI (Injeção de Dependências) + Event-driven leve.
- **Justificativa**: NestJS é estruturado em torno de módulos, provedores e controladores com injeção de dependência via construtor. Node.js é naturalmente assíncrono.
- **Alternativas viáveis**: Active Record (via TypeORM BaseEntity) para manter proximidade com o modelo mental do legado.

## Gap identificado
- **Severidade**: Médio
- **Implicações concretas**:
  - **Transição Active Record -> Repository**: O legado usa `$cliente->store()`. No paradigma natural, seria `repo.save(cliente)`. 
  - **Sincronia -> Assincronismo**: O PHP bloqueia a execução; o Node.js usa Promises/Async-Await.
  - **Gestão de Estado**: O Adianti mantém estado em sessão PHP; NestJS/Angular exige gestão de estado explícita ou Stateless JWT.
  - **Injeção de Dependências**: O legado instancia classes diretamente ou via Singleton; o NestJS exige registro em módulos.

## Opções apresentadas ao usuário
1. **Adotar paradigma natural da stack** (transformacional)
2. **Forçar paradigma similar ao legado** (conservador)
3. **Híbrido** (equilibrado) - **ESCOLHIDA**

## Decisão do usuário
- **Escolha**: 3
- **Justificativa do usuário**: Abordagem equilibrada entre paridade e modernização.
- **Decidido em**: 2026-05-19T14:55:00Z

## Apetite derivado
- `derived_appetite`: balanced

## Implicações pendentes para próximos agentes
| Agente | Implicação | Como honrar |
|---|---|---|
| Curator | Equilibrar o que é mantido e o que é modernizado. | Aplicar padrões transformacionais em módulos críticos e manter conservadorismo em cadastros simples. |
| Strategist | Estratégia mista de migração. | Priorizar modernização em áreas de alto valor (Auth, BI) e paridade rápida no restante. |
| Designer | Arquitetura flexível. | Combinar repositórios (DI) com modelos Active Record onde houver ganho de performance/velocidade. |
| Inspector | Validar equilíbrio entre paridade e modernidade. | Garantir que a UX seja moderna sem perder a fidelidade às regras de negócio complexas. |

## Notas
O usuário priorizou performance. Na stack Node.js, isso significa evitar camadas de abstração desnecessárias e manter a lógica próxima aos dados, o que corrobora com a escolha do paradigma conservador (Active Record) neste contexto.
