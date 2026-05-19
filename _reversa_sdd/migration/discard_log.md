# Discard Log — Reversa

Este documento registra as funcionalidades e regras de negócio do legado RCG que foram descartadas durante a migração, com a devida justificativa técnica ou de negócio.

| Artefato / Regra | Módulo | Justificativa | Evidência |
|-----------------|--------|---------------|-----------|
| Geolocalização de Atendimentos | CRM / Vendedor | Funcionalidade ausente no legado e mantida fora do escopo inicial de rebuild. | `G-03` |
| Gestão de Sessão via Filesystem | Infra / Admin | Incompatível com a stack escalável proposta (NestJS + Redis). | `Q-02`, `ADR 004` |
| Hashing de Senha MD5 (Nativo) | Admin | Descartado em favor do Bcrypt. Mantido apenas como gatilho para migração no primeiro acesso. | `ADR 001`, `Q-01` |
