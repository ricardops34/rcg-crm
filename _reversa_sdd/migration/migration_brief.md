---
schemaVersion: 1
generatedAt: 2026-05-19T14:40:00Z
reversa:
  version: "1.2.39"
kind: migration_brief
producedBy: orchestrator
hash: "sha256:TODO"
---

# Migration Brief

> Documento de critério de migração coletado em entrevista no início do `/reversa-migrate`.
> Consumido pelos seis agentes do Time de Migração. Não pergunta paradigma (responsabilidade do Paradigm Advisor) nem apetite (derivado em `paradigm_decision.md`).

## Objetivo da migração
Modernização da UX (User Experience) e atualização tecnológica da stack (linguagem moderna). O sistema legado em PHP/Adianti está sendo substituído para ganhar agilidade, manutenibilidade e uma interface mais rica.

## Métricas de sucesso
- Interface moderna utilizando Angular e PO-UI.
- Backend robusto e tipado com NestJS (TypeScript).
- Paridade funcional com o sistema legado RCG.
- Facilidade de manutenção e extensão do código.

## Restrições
- **Prazo**: Não especificado.
- **Orçamento**: Não especificado.
- **Técnicas**: Manter compatibilidade com o banco de dados PostgreSQL existente.
- **Operacionais**: Garantir a migração transparente de dados e usuários.

## Fatores de risco conhecidos
- Complexidade na conversão de regras de negócio implícitas do PHP para NestJS.
- Paridade de UI entre os componentes do Adianti e PO-UI.

## Stakeholders
| Nome / papel | Responsabilidade na migração |
|---|---|
| Ricardo | Owner / Decisor Principal |

## Stack alvo
- **Linguagem**: TypeScript (Node.js)
- **Framework**: NestJS (Backend) / Angular + PO-UI (Frontend)
- **Banco**: PostgreSQL (Mantido)
- **Mensageria**: A definir conforme necessidade (Redis para cache já identificado).
- **Infra**: VPS com Docker (Portainer/Traefik).

## Escopo declarado
- **Incluído**: Todos os módulos identificados no SDD (_admin, cadastros, cobranca, vendedor, gerencia, supervisor, meu_cliente, relatorios, communication, sistema, desenvolvimento_).
- **Excluído**: Nenhum módulo descartado até o momento.

## Notas livres
A migração deve seguir os ADRs já estabelecidos (004 - NestJS Stack e 005 - Angular PO-UI Stack).
