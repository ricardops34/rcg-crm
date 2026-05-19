---
schemaVersion: 1
generatedAt: 2026-05-19T16:00:00Z
reversa:
  version: "1.2.39"
kind: screen_modernization_decision
producedBy: screen_translator
hash: "sha256:TODO"
---

# Screen Modernization Decision

> Este documento define a estratégia de tradução das interfaces do legado RCG para a nova stack Angular + PO-UI.
> Baseia-se na preferência do usuário por um modelo **Híbrido**.

## 1. Detecção de Plataforma
- **Origem**: PHP Adianti Framework (Server-Rendered UI).
- **Características**: Componentes PHP que geram HTML/JS estático, forte dependência de sessão no servidor, interações baseadas em recarregamento parcial via Ajax (TPage).
- **Alvo**: Angular + PO-UI (Single Page Application).
- **Características**: Client-side rendering, reatividade total, componentes ricos pré-construídos, separação clara entre UI e API (Stateless).

## 2. Decisão de Modo: Híbrido
Conforme definido nas diretrizes de migração, a abordagem será equilibrada entre velocidade de entrega e modernização da experiência.

| Categoria de Tela | Modo | Justificativa | Componente PO-UI Alvo |
|-------------------|------|---------------|------------------------|
| **Dashboards / BI** | Modernizado | Dashboards são o core de valor para o usuário final. Exigem reatividade e visual rico. | `po-chart`, `po-widget`, `po-info` |
| **Cadastros Simples**| Preservado | Cadastros de tabelas básicas não necessitam de mudanças estruturais na UX, apenas fidelidade visual ao PO-UI. | `po-dynamic-form`, `po-table` |
| **Listagens** | Preservado | Manter a funcionalidade de filtros e paginação similar ao legado, aproveitando a robustez da `po-table`. | `po-table`, `po-page-list` |
| **Processos Complexos** | Modernizado | Telas como 'Cadastro de Objetivos' (mestre-detalhe) ganham muito com a gestão de estado do Angular. | `po-stepper`, `po-grid` |
| **Autenticação** | Modernizado | Necessário para suportar JWT e novos fluxos de segurança. | `po-page-login` |

## 3. Inventário de Tradução

| Tela Legada | Módulo | Estratégia | Componentes Chave PO-UI |
|-------------|--------|------------|-------------------------|
| Login | `admin` | Modernizado | `po-page-login` |
| Dashboard Gerencial | `gerencia` | Modernizado | `po-chart` (Donut/Bar), `po-widget`, `po-info` |
| Cadastro de Vendedores | `gerencia` | Preservado | `po-page-edit`, `po-dynamic-form` |
| Listagem de Vendedores | `gerencia` | Preservado | `po-page-list`, `po-table` (com `po-table-column-status`) |
| Cadastro de Objetivos | `gerencia` | Modernizado | `po-page-edit`, `po-grid` (para o detalhe das metas) |
| Dashboard Vendedor | `vendedor` | Modernizado | `po-chart` (Gauge/Line), `po-widget` |
| MVC (Média de Venda) | `vendedor` | Modernizado | `po-table` com custom headers ou `po-grid` para visão pivot |

## 4. Diretrizes de Design (PO-UI)
1. **Consistência**: Utilizar o tema padrão do PO-UI para garantir conformidade com o ecossistema TOTVS.
2. **Reatividade**: Dashboards devem atualizar via Observables ao alterar filtros de período.
3. **Fidelidade Funcional**: Embora a interface seja nova, os nomes dos campos e a lógica de validação devem espelhar o legado para evitar fricção no treinamento.
4. **Mobile First**: Aproveitar a responsividade nativa do PO-UI para facilitar o uso por vendedores em campo.

## 5. Próximos Passos
- O **UI Designer** deve detalhar os protótipos das telas 'Modernizadas'.
- O **Frontend Developer** deve configurar os serviços Angular para consumir as APIs NestJS mapeadas.
