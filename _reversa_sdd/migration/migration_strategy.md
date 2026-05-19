# Migration Strategy — RCG Reversa

Este documento define a abordagem estratégica para a migração do sistema RCG (PHP/Adianti) para a nova stack (NestJS/Angular/PO-UI), operando sob o paradigma **Híbrido (Equilibrado)**.

## 1. Avaliação de Estratégias

| Estratégia | Aplicabilidade | Risco | Trade-off |
| :--- | :--- | :--- | :--- |
| **Big Bang** | Baixa | Altíssimo | Exige congelamento do legado por meses. Paridade funcional difícil de validar em ~3.000 arquivos de uma só vez. |
| **Parallel Run** | Média | Médio | Excelente para o módulo de **Cobrança**, mas exige sincronização de dados complexa ou entrada dupla. |
| **Strangler Fig** | **Alta** | **Baixo** | Permite entregas incrementais. O Traefik atua como roteador, "estrangulando" o legado módulo a módulo. |

## 2. Estratégia Recomendada: Strangler Fig (Estrangulamento)

A recomendação é a adoção do **Strangler Fig**, utilizando o **Traefik** como facilitador de roteamento. Esta abordagem permite que o sistema novo e o legado coexistam sob o mesmo domínio, com a carga sendo transferida gradualmente.

### Justificativa:
1. **Redução de Risco**: Falhas em um novo módulo não derrubam o sistema inteiro.
2. **Time-to-Market**: O módulo de **Vendedor (BI/Dashboards)** pode ir ao ar enquanto os **Cadastros** ainda rodam no legado.
3. **Feedback Contínuo**: Usuários começam a usar a nova UX (Angular/PO-UI) precocemente.
4. **Alinhamento com Infra**: O Portainer/Traefik permite configurar regras de path (ex: `/api/v2/*` -> NestJS) de forma trivial.

## 3. Road-map de Migração (Fases)

### Fase 1: Fundação e Identity Bridge (Modernizar)
- **Módulo**: `Admin`
- **Ação**: Implementar NestJS com JWT e Redis. O novo sistema passa a ser o "Provedor de Identidade".
- **Bridge**: O sistema legado deve ser ajustado para validar o JWT ou compartilhar a sessão via Redis para evitar re-login.

### Fase 2: Ganho de Valor (Modernizar)
- **Módulos**: `Vendedor` e `Gerência`
- **Ação**: Implementar Dashboards Real-time e Visão 360 do Cliente. 
- **Por que**: São os módulos com maior impacto visual e percepção de modernização (UX).

### Fase 3: Estabilização de Dados (Conservador)
- **Módulo**: `Cadastros`
- **Ação**: Migração dos formulários de Clientes e Produtos.
- **Abordagem**: Seguir o padrão PO-UI para formulários, mantendo as regras de validação do legado.

### Fase 4: Core Crítico (Parallel Run/Modernizar)
- **Módulo**: `Cobranca`
- **Ação**: Migração das regras de juros e saldos.
- **Validação**: Rodar o cálculo no NestJS em paralelo com o legado por um ciclo financeiro para garantir 100% de paridade nos centavos.

## 4. Arquitetura de Coexistência

1. **Traefik**: Gerencia o tráfego. 
   - Path `/` -> Legado (PHP)
   - Path `/v2/` -> Novo Frontend (Angular)
   - Path `/api/v1/` -> Legado (API PHP)
   - Path `/api/v2/` -> Novo Backend (NestJS)
2. **Database**: PostgreSQL único. O NestJS usa TypeORM (Active Record/Repository) enquanto o PHP continua com Adianti TRecord.
3. **Redis**: Partilha de estado de sessão e cache de permissões (RBAC).
