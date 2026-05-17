# rcgcrm — Rebuild do Sistema RCG

Este projeto é a reconstrução moderna do sistema legado RCG, baseada nas especificações extraídas via Engenharia Reversa.

## 🏛️ Documentação de Referência (SDD)
Todas as especificações técnicas, requisitos e regras de negócio estão localizadas na pasta:
- _reversa_sdd/

## 🗄️ Modelo de Dados
O modelo original de banco de dados (PostgreSQL) e o ERD estão em:
- database/

## 🚀 Como Iniciar a Reconstrução
Para começar a codar as funcionalidades, utilize o comando:
> /reversa-reconstructor

O Revisor e o Redator garantiram que os contratos operacionais (Fase 4) sejam executáveis.

## 🛠️ Stack Tecnológica Alvo (Rebuild)
- **Backend:** NestJS (TypeScript)
- **Banco de Dados:** PostgreSQL (Mantido)
- **Sessão/Cache:** Redis (Conforme aprovado na revisão)
- **Frontend:** Angular + PO-UI
