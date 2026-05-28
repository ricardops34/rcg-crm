---
name: po-ui-specialist
description: Especialista frontend sênior no ecossistema Angular (v21+) and no design system PO-UI (v21+), com foco em código limpo, moderno, acessível e estritamente tipado baseado na documentação espelhada localmente no projeto.
---

# PO-UI & Angular 21+ Specialist Skill

Esta skill especializa o agente para atuar como Desenvolvedor Frontend Sênior no ecossistema **Angular (v21+)** e no design system **PO-UI (v21+)**, seguindo os padrões arquiteturais modernos do projeto legado e consultando o espelho local de documentação.

---

## 📂 Diretório de Documentação de Referência (Mirror Local)

Sempre que precisar consultar propriedades exatas, tipos, métodos e eventos de qualquer componente do PO-UI (ex: `po-button.md`, `po-table.md`, `po-dynamic-form.md`), utilize o repositório de documentação espelhado localmente:
- **Caminho Absoluto**: `c:\Patay\Ricardo\VPS\rcg-crm\docs\po-ui\doc\llms-generated\`
- **Modo de Consulta**: Faça uma busca ou leia diretamente o arquivo `.md` correspondente ao componente (ex: `po-input.md`) antes de propor código novo ou alterações em templates e controladores.

---

## 📐 Diretrizes de Arquitetura e Angular Moderno

Todo código Angular gerado deve obedecer rigorosamente aos seguintes padrões modernos:

1. **Standalone Components**:
   - Todos os componentes, diretivas e pipes criados devem ser standalone (`standalone: true` nas definições de decoradores).
   - Evite o uso de `NgModule`s para novos fluxos ou componentes.

2. **Angular Signals**:
   - Utilize Signals (`signal()`, `computed()`, `effect()`) para gerenciar o estado reativo local e controle de fluxo de dados.
   - Evite gerenciar estado manual ou depender excessivamente do RxJS (`BehaviorSubject`) para estados e reatividades locais simples.

3. **Injeção de Dependência Moderna**:
   - Utilize a função `inject()` para injetar serviços e dependências locais de forma limpa nas propriedades da classe, evitando declará-los no construtor.
   ```typescript
   private poNotification = inject(PoNotificationService);
   private parameterService = inject(ParameterService);
   ```

4. **Control Flow (Sintaxe Nativa)**:
   - Use a nova sintaxe nativa `@if`, `@for` e `@switch` no template HTML em vez das diretivas estruturais antigas `*ngIf`, `*ngFor` e `*ngSwitch`.

5. **Tipagem Estrita**:
   - Proíba o uso de `any`. Crie interfaces ou tipos apropriados para todas as estruturas de dados no TypeScript.

---

## 🎨 Boas Práticas do PO-UI

### 1. Botões e Ações (`po-button`)
- **Kind**: Apenas um botão com `p-kind="primary"` deve existir por página/tela para guiar a ação principal. Ações secundárias ou de cancelamento devem usar `secondary` ou `tertiary`.
- **Ações Críticas**: Para exclusão de registros ou ações irreversíveis, configure o botão com a propriedade `p-danger` habilitada.
- **Labels**: Mantenha os labels de botões curtos, diretos e objetivos (ex: "Salvar", "Cancelar", "Excluir").

### 2. Tabelas (`po-table`)
- **Definição de Colunas**: Sempre tipifique as colunas de tabela usando a interface `PoTableColumn` importada de `@po-ui/ng-components`.
- **Customização de Células**: Use `<ng-template p-table-cell-template let-column let-row="row">` para customizações complexas de dados de células se as propriedades padrões de coluna não forem suficientes.
- **Ações**: Centralize as ações de linha na propriedade `actions` do objeto `PoTableColumn` sempre que possível.

### 3. Formulários Dinâmicos (`po-dynamic-form`)
- **Metadata**: Defina a lista de campos usando a interface `PoDynamicFormField` estritamente tipificada no TypeScript.
- **Validações**: Configure validações dinâmicas usando propriedades de validação nativas (`p-validate` ou triggers de validação assíncrona).

### 4. Notificações e Diálogos
- Use o `PoNotificationService` para feedbacks rápidos de ações (sucesso, erro, alerta, informação).
- Use o `PoDialogService` para confirmações críticas que exijam resposta expressa do usuário (ex: exclusão de registros, desmembramento de chaves, alterações em massa) antes de realizar mutações no banco de dados.

### 5. Preferência Absoluta por Componentes Oficiais
- **Regra de Ouro**: Sempre que existir um componente padrão/oficial disponível no ecossistema do PO-UI (ex: `po-input`, `po-combo`, `po-select`, `po-button`, `po-table`, etc.), ele **deve** ser utilizado obrigatoriamente para qualquer elemento visual da tela.
- Evite a utilização de elementos HTML puros crus, componentes de outras bibliotecas visuais de terceiros e **evite ao máximo a personalização/estilização manual via CSS customizado ad-hoc**. Use sempre as ferramentas nativas de layout do PO-UI (como `po-row`, as classes de grid responsivo `po-md-*` e componentes de layout como `po-container` e `po-widget`).

---

## 🔄 Padronização de CRUDs e Comunicação com a API

### 1. Higienização de Payloads de Atualização (PUT)
- O identificador primário do registro (`id`) deve ser enviado **somente na URL** da rota e **nunca** no corpo (body) das requisições de atualização (`PUT` / `PATCH`).
- O backend utiliza o validador estrito do NestJS (`forbidNonWhitelisted: true`). O envio da propriedade `id` no corpo dispara o erro de validação `"property id should not exist"` (HTTP 400 Bad Request).
- **Frontend**: Nos métodos de serviço do Angular, utilize a desestruturação para isolar o ID e enviar apenas o restante das propriedades no corpo:
  ```typescript
  save(data: any): Observable<any> {
    if (data.id) {
      const { id, ...payload } = data;
      return this.http.put<any>(`${this.API_URL}/${id}`, payload, { headers: this.getHeaders() });
    }
    return this.http.post<any>(this.API_URL, data, { headers: this.getHeaders() });
  }
  ```

### 2. Tratamento de Two-Way Binding no `po-combo`
- Nunca use a sintaxe simplificada `[(ngModel)]` em componentes `<po-combo>`. O `po-combo` possui um Output homônimo (`ngModelChange`) que gera conflito sintático no compilador estrito do Angular em modo de produção (erro `NG8007`).
- **Como fazer**: Separe explicitamente a escrita e a leitura de eventos usando `[ngModel]` e o Output oficial `(p-change)` do PO-UI:
  ```html
  <po-combo
    name="icon"
    [ngModel]="program.icon"
    (p-change)="program.icon = $event"
    [p-options]="iconOptions">
  </po-combo>
  ```

### 3. Associação de Enums Estritos no HTML (`TS2322`)
- Nunca passe strings literais cruas (ex: `[p-filter-mode]="'contains'"`) para atributos de componentes do PO-UI que esperam Enums estritos no TypeScript (erro `TS2322` no compilador).
- **Como fazer**: Importe o enum no arquivo TypeScript do componente, declare-o como propriedade de classe e associe-a por bind de propriedade no HTML.

### 4. Uso do Componente de Lookup (`po-lookup`) para Tabelas Acessórias Volumosas
- Sempre utilize o componente `<po-lookup>` em vez do `<po-combo>` ou `<po-select>` ao selecionar dados provenientes de tabelas acessórias ou de grande volume (ex: Clientes, Fornecedores, Produtos, etc.) para garantir a performance e paginação sob demanda.
- Forneça ou injete uma classe de serviço Angular que implemente a interface `PoLookupFilter` para consultar o backend.
- Adicione o componente `<po-lookup>` no template HTML vinculando-o à classe injetada:
  ```html
  <po-lookup
    name="municipio"
    [p-filter-service]="municipioService"
    p-label="Município"
    p-field-label="name"
    p-field-value="id"
    [(ngModel)]="cliente.municipioId">
  </po-lookup>
  ```

### 5. Registro Reativo de Título e Limite de Consultas
- **Título Reativo**: Nunca use strings estáticas ou hardcoded (como `"CRM"`) para o título da barra superior ou de cabeçalhos principais do sistema. Sempre consuma reativamente a propriedade `this.parameterService.systemName()` que armazena em cache o valor do parâmetro `sys_system_name` do banco de dados.
- **Limite de Registros Dinâmico**: Nunca declare constantes fixas de paginação (como `itensPorPagina = 20`) nas telas de listagem. Sempre crie um Getter que consulte o limite global reativo do `ParameterService`:
  ```typescript
  private parameterService = inject(ParameterService);
  
  get itensPorPagina(): number {
    return this.parameterService.queryLimit();
  }
  ```

---

## 🛠️ Tratamento de Erros Comuns de Compilação no compilador estrito do Angular 21

1. **TS2322 em Propriedades Booleanas (p-disabled, p-no-border, etc.)**:
   - Em certos componentes do PO-UI com tipagens legadas, o compilador estrito do Angular pode exigir tipo `string` ao invés de `boolean` para atributos como `p-disabled` ou `p-no-border`.
   - Caso o erro `Type 'boolean' is not assignable to type 'string'` seja retornado, faça a coerção direta para string no template HTML:
     ```html
     [p-disabled]="parameter.system === 'N' ? 'true' : 'false'"
     [p-no-border]="'false'"
     ```
