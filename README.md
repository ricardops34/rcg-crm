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
- **Frontend:** Angular (`^21.2.0`) + PO-UI (`^21.15.0`) [MODERNO - Uso obrigatório de `p-danger` em vez de `p-type="danger"` para botões de alerta/remoção]

> [!IMPORTANT]
> **Restrição de Execução de Comandos (Docker):**
> - **NÃO** tente executar comandos Docker (`docker`, `docker compose`, `docker-compose`, etc.) a partir do terminal do workspace local. O deploy e a orquestração do ambiente estão configurados de forma externa e são gerenciados remotamente na VPS.

> [!IMPORTANT]
> **Diretriz de Interação com o Usuário (Perguntas vs. Ações):**
> - Quando o usuário fizer uma **pergunta** (ex: "como ajusto X?", "onde fica Y?"), a resposta deve ser **estritamente explicativa/teórica**. **NÃO** realize nenhuma modificação de código, criação de arquivos ou alterações no banco de dados. Qualquer alteração prática só deve ser feita se o usuário ordenar explicitamente (ex: "faça", "pode alterar", "aplique").
