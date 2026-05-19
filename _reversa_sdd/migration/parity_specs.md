# Parity Specifications — Reversa

Este documento define a estratégia de validação técnica para garantir que o sistema Reversa (NestJS/Angular) mantenha a equivalência comportamental com o legado RCG (PHP/Adianti), respeitando a transição do paradigma OO Clássico para OO com DI.

## 1. Estratégia de Validação por Camada

### 1.1. Backend (NestJS vs PHP)
*   **Characterization Tests (Black Box)**: Executar chamadas de API no legado e no novo sistema com o mesmo payload e comparar as respostas (JSON/Status Code).
*   **Data Parity Checks**: Scripts de validação pós-migração que comparam a integridade dos dados (ex: total de saldos em `tituloreceber` no PostgreSQL vs Dump original).
*   **Business Rule Parity**: Para regras críticas (Cálculo de Juros), utilizar **Contract Tests** que validam se a implementação `Service` no NestJS retorna o mesmo valor que o `TRecord/Model` no PHP para os mesmos inputs.

### 1.2. Frontend (Angular vs PO-UI Legacy)
*   **Visual Parity (Telas Preservadas)**: Garantir que a disposição dos campos e as máscaras de entrada sejam idênticas. Uso de snapshots de UI para detectar regressões.
*   **Screen Contract Tests (Telas Modernizadas)**: Em telas como Dashboards, a paridade não é visual (UX mudou), mas sim de **Fidelidade de Dados**. O teste deve validar se o valor exibido no `po-chart` corresponde ao valor gerado pela View SQL do legado.
*   **Workflow Parity**: Validar se o caminho crítico (ex: Cadastro -> Salvar -> Mensagem de Sucesso) segue a mesma jornada do usuário, independentemente da tecnologia de renderização.

## 2. Dimensões de Paridade para Mudança de Paradigma

| Aspecto | No Legado (PHP) | No Alvo (NestJS) | Critério de Paridade |
| :--- | :--- | :--- | :--- |
| **Persistência** | Active Record (`$obj->store()`) | Repository (`repo.save(obj)`) | O efeito colateral no Banco de Dados deve ser idêntico (mesmas tabelas/colunas). |
| **Sincronia** | Bloqueante | Assíncrono (`async/await`) | O resultado final deve ser garantido antes de retornar ao usuário. |
| **Sessão** | `$_SESSION` (Stateful) | JWT + Redis (Stateless) | O usuário deve manter suas permissões e unidade ativa durante toda a navegação. |
| **Erros** | Exceptions PHP / TMessage | Exception Filters / DTO Validation | O código de erro e a mensagem amigável devem ser preservados. |

## 3. Critérios de Aceite de Paridade (PAC)

Para que um módulo seja considerado "Migrado com Sucesso", ele deve atingir os seguintes índices:
1.  **Paridade de Dados**: 100% de equivalência em campos monetários e chaves primárias/estrangeiras.
2.  **Paridade Funcional**: 100% dos fluxos definidos nas `.feature` Gherkin devem passar no novo sistema.
3.  **Paridade Visual (Preservada)**: Desvio visual máximo de 10% (espaçamento/grid), mantendo 100% da visibilidade de campos obrigatórios.
4.  **Paridade de Performance**: O novo sistema deve ser igual ou até 30% mais rápido que o legado em operações de leitura (BI/Dashboards).

## 4. Matriz de Rastreabilidade de Testes

| Módulo | Tipo de Teste | Ferramenta Recomendada |
| :--- | :--- | :--- |
| **Auth** | Integration (Identity Bridge) | Jest + Supertest |
| **Cadastros** | E2E (Form Parity) | Cypress / Playwright |
| **Cobrança** | Unit (Math Parity) | Jest (Deep equality on cents) |
| **Analytics** | Contract (Data Fidelity) | Pact.io / Custom JSON Compare |
