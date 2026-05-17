# ADR 004: Stack Tecnológica para o Novo Sistema (rcgcrm)

## Status
Aceito (Planejado) 🔵

## Contexto
O sistema original (rcg) foi desenvolvido em PHP (Adianti Framework), seguindo um padrão monolítico e síncrono. Para a reconstrução moderna (rcgcrm), buscamos uma stack que ofereça maior performance, escalabilidade, tipagem forte e suporte nativo a operações assíncronas para as integrações REST e WhatsApp.

## Decisão
Utilizaremos **NestJS** com **TypeScript** como framework de backend principal.

## Justificativa
- **Modularidade:** NestJS possui uma arquitetura inspirada em Angular, facilitando a organização por domínios, o que se alinha perfeitamente com a estrutura de módulos (units) extraída do legado.
- **Tipagem Forte:** O uso de TypeScript reduz erros em tempo de execução e facilita a manutenção do contrato de APIs mapeado no OpenAPI.
- **Ecossistema:** Grande oferta de bibliotecas para integração com PostgreSQL (Prisma/TypeORM), Redis e APIs de terceiros.
- **Assincronismo:** Ideal para as integrações de WhatsApp e faturamento identificadas.

## Consequências
- **Positivas:** Código mais robusto e testável; facilidade de migração para microserviços no futuro; melhor performance de rede.
- **Negativas:** Curva de aprendizado inicial maior que o PHP clássico para a equipe (se aplicável).
