---
schemaVersion: 1
generatedAt: 2026-05-19T15:05:00Z
reversa:
  version: "1.2.39"
kind: topology_decision
producedBy: designer
hash: "sha256:TODO"
---

# Topology Decision

> Decisão consciente sobre como organizar o sistema novo: preservar a topologia do legado, adotar uma topologia moderna ou aplicar um híbrido.
> Este artefato é leitura obrigatória do próprio Designer (para decompor bounded contexts) e do agente de codificação (para criar a árvore de pastas).

## Topologia do legado detectada
- **Padrão organizacional**: Módulos por domínio (Monolito Modular no Adianti)
- **Confiança**: 🟢 CONFIRMADO
- **Evidências**:
  - Código organizado em pastas que representam domínios de negócio (`admin`, `cadastros`, `cobranca`, `vendedor`, etc.) conforme `_reversa_sdd/architecture.md`.
  - Separação clara no `app/control/` e `app/model/`.
- **Mapa da árvore legada** (resumido):
  ```
  app/
  ├── control/ (Controllers)
  │   ├── admin/
  │   ├── cadastros/
  │   ├── cobranca/
  │   └── ...
  ├── model/ (Models - Active Record)
  │   ├── admin/
  │   ├── cadastros/
  │   └── ...
  └── view/ (Telas Adianti)
  ```

## Diagnóstico estrutural
- **Acoplamento**: Médio (Acoplamento forte com o banco via Active Record).
- **Coesão por módulo**: Alta (Domínios bem definidos).
- **Módulos órfãos / mortos**: Nenhum identificado.
- **Camadas redundantes**: Nenhuma.
- **Violações de fronteira**: Uso de Views SQL para agregação cruza domínios de forma oculta.
- **Mistura de paradigmas/estilos**: Predominantemente OO Clássico (Active Record).
- **Avaliação geral**: Parcialmente problemático (devido ao acoplamento com o banco e dificuldade de escala horizontal).

## Topologia moderna proposta
- **Padrão**: DDD com Bounded Contexts + Clean Architecture (NestJS Modules)
- **Justificativa**: NestJS favorece a modularização. DDD ajuda a isolar os domínios complexos (Vendas/Cobrança) e permite modernizar o core enquanto mantém o restante estável.
- **Ganhos concretos esperados**:
  - Testabilidade isolada por domínio.
  - Facilidade de extrair módulos para microserviços no futuro (se necessário).
  - Organização limpa e tipada.
- **Esboço da árvore proposta**:
  ```
  src/
  ├── modules/
  │   ├── auth/ (Modernizado)
  │   ├── commercial/ (Híbrido - Vendedor/Metas)
  │   ├── billing/ (Híbrido - Cobrança)
  │   ├── master-data/ (Conservador - Cadastros)
  │   └── analytics/ (Modernizado - BI)
  ```

## Opções apresentadas ao usuário
1. **Preservar topologia legada** (conservador)
2. **Adotar topologia moderna proposta** (transformacional)
3. **Híbrido** (equilibrado) - **ESCOLHIDA**

## Decisão do usuário
- **Escolha**: 3
- **Justificativa do usuário**: Abordagem equilibrada para modernizar a UX e o core mantendo a fidelidade funcional.
- **Decidido em**: 2026-05-19T15:05:00Z

## Mapeamento legado → novo
| Módulo / pasta legada | Bounded context novo | Tipo | Observações |
|---|---|---|---|
| admin | auth / admin | modernizado | JWT, Redis, NestJS Guards. |
| cadastros | master-data | preservado | Padrão conservador (Active Record/TypeORM). |
| cobranca | billing | híbrido | Regras legadas em serviços modernos. |
| vendedor / gerencia | commercial / analytics | modernizado | Dashboards reativos no Angular. |
| sistema | core / config | preservado | Parâmetros e logs. |

## Implicações pendentes para próximos passos do Designer
| Etapa do Designer | Implicação | Como honrar |
|---|---|---|
| Bounded contexts | Seguir o mapeamento acima. | Criar módulos NestJS correspondentes. |
| target_architecture | Definir camadas por módulo. | Módulos 'modernizados' usam Repositories; 'preservados' usam BaseEntity. |
| target_data_model | Mapear tabelas legadas. | Manter nomes de colunas do legado para facilitar migração. |

## Notas
A estratégia de Strangler Fig será aplicada via Traefik, permitindo que cada Bounded Context novo assuma rotas específicas conforme for implementado.
