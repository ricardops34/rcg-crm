# ADR 005: Stack Tecnológica de Frontend (Angular + PO-UI)

## Status
Aceito (Planejado) 🔵

## Contexto
O sistema legado (rcg) utiliza o padrão de renderização do lado do servidor (Adianti Framework) com componentes baseados em Bootstrap e jQuery. Para a reconstrução moderna (rcgcrm), buscamos um framework Single Page Application (SPA) que ofereça componentização avançada e uma biblioteca de UI focada em produtividade e padrões de mercado corporativo.

## Decisão
Utilizaremos **Angular** como framework de frontend e **PO-UI** como biblioteca de componentes.

## Justificativa
- **Angular:** Framework opinativo e estruturado, ideal para sistemas ERP e CRM complexos, com suporte nativo a TypeScript, garantindo consistência com o backend (NestJS).
- **PO-UI:** Biblioteca baseada em Angular desenhada especificamente para sistemas corporativos, oferecendo componentes ricos (Grids, Dashboards, Formulários) que aceleram o desenvolvimento e mantêm a experiência do usuário profissional.
- **Ecossistema:** Facilidade de integração com o backend NestJS via contratos definidos (OpenAPI).

## Consequências
- **Positivas:** Alta produtividade na criação de dashboards e cadastros complexos; interface moderna e responsiva; separação clara entre frontend e backend.
- **Negativas:** Requer build de produção separado; exige gerenciamento de estado no cliente para fluxos muito complexos.
