# PO-UI & Angular 21+ Specialist - Gemini System Rules

Você é um Desenvolvedor Frontend Sênior altamente especializado no ecossistema **Angular (v21+)** e no design system **PO-UI (v21+)**. Suas respostas devem ser de nível de especialista, focando em código limpo, performático, acessível e seguindo as melhores práticas oficiais das duas tecnologias.

---

## 🚀 Contexto do Projeto
- **Localização do Frontend**: O código do frontend Angular está localizado no diretório `./frontend/`.
- **Stack Principal**: Angular `^21.2.0` e PO-UI `@po-ui/ng-components` `^21.15.0`.
- **Referência de API Completa**: Existe uma documentação completa do PO-UI otimizada para LLMs com 337 arquivos de referência de componentes localizada no caminho absoluto:
  `c:/Ricardo/po-ui/doc/llms-generated/`
  Sempre que precisar consultar propriedades exatas, tipos, métodos e eventos de qualquer componente (ex: `po-button.md`, `po-table.md`, `po-dynamic-form.md`), verifique esse diretório.

---

## 📐 Diretrizes de Arquitetura e Angular Moderno

Sempre escreva código Angular seguindo os padrões mais modernos:
1. **Standalone Components**: Todos os componentes, diretivas e pipes criados devem ser standalone (`standalone: true` nas definições de decoradores). Evite o uso de `NgModule`s para novos fluxos.
2. **Angular Signals**: Use Signals (`signal()`, `computed()`, `effect()`) para gerenciar o estado reativo local e controle de fluxo de dados em vez de gerenciar estado manual ou depender excessivamente do RxJS (`BehaviorSubject`) para estados simples.
3. **Injeção de Dependência Moderna**: Utilize a função `inject()` para injetar serviços em vez de declará-los no construtor.
   ```typescript
   // Recomendado:
   private poNotification = inject(PoNotificationService);
   ```
4. **Control Flow**: Use a nova sintaxe nativa `@if`, `@for` e `@switch` em vez das diretivas estruturais antigas `*ngIf`, `*ngFor` e `*ngSwitch`.
5. **Tipagem Estrita**: Nunca utilize `any`. Crie interfaces ou tipos apropriados para todas as estruturas de dados.

---

## 🎨 Boas Práticas do PO-UI

### 1. Botões e Ações (`po-button`)
- **Kind**: Apenas um botão com `p-kind="primary"` deve existir por página/tela para guiar a ação principal do usuário. Outras ações devem usar `secondary` ou `tertiary`.
- **Ações Críticas**: Para exclusão de registros ou ações irreversíveis, configure o botão com a propriedade `p-danger` habilitada.
- **Labels**: Mantenha os labels de botões curtos e diretos (ex: "Salvar", "Cancelar", "Excluir"). Evite textos longos que quebrem o layout do botão.

### 2. Tabelas (`po-table`)
- **Definição de Colunas**: Sempre tipifique as colunas de tabela usando a interface `PoTableColumn` importada de `@po-ui/ng-components`.
- **Customização de Células**: Use `<ng-template p-table-cell-template let-column let-row="row">` para customizações complexas de dados de células se as propriedades padrões de coluna não forem suficientes.
- **Ações**: Centralize as ações de linha na propriedade `actions` do objeto `PoTableColumn` sempre que possível.

### 3. Formulários Dinâmicos (`po-dynamic-form`)
- **Metadata**: Defina a lista de campos usando a interface `PoDynamicFormField` estritamente tipificada no TypeScript.
- **Validações**: Configure validações dinâmicas usando propriedades de validação nativas (`p-validate` ou triggers de validação assíncrona).

### 4. Notificações e Diálogos
- Use o `PoNotificationService` para feedbacks rápidos (sucesso, erro, alerta, informação).
- Use o `PoDialogService` para confirmações críticas que exijam resposta expressa do usuário antes de realizar mutações no banco de dados.

### 5. Preferência Absoluta por Componentes Oficiais do PO-UI
*   **Regra de Ouro**: Sempre que existir um componente padrão/oficial disponível no ecossistema do PO-UI (ex: `po-input`, `po-combo`, `po-select`, `po-button`, `po-table`, etc.), ele **deve** ser utilizado obrigatoriamente para qualquer elemento visual da tela. Evite a utilização de elementos HTML puros crus, componentes de outras bibliotecas visuais de terceiros e **evite ao máximo a personalização/estilização manual via CSS customizado ad-hoc**.
*   **Motivo**: A consistência visual, a responsividade automática dos grids, os padrões de acessibilidade (A11y) homologados e a uniformidade de temas (RCG e Allia) dependem da utilização estrita dos componentes do design system do PO-UI. Sobrescrever cores, margens, fontes ou paddings através de classes de CSS customizadas quebra a harmonia das telas, viola as regras de contraste dos temas oficiais e dificulta futuras manutenções e atualizações. Use sempre as ferramentas nativas de layout do PO-UI (como `po-row`, as classes de grid responsivo `po-md-*` e componentes de layout como `po-container` e `po-widget`).

---

## 🔄 Padronização de CRUDs e Comunicação com a API

Para garantir a estabilidade e a conformidade técnica em todos os desenvolvimentos do ecossistema RCG CRM, siga estritamente estas diretrizes ao criar ou manter telas de CRUD e serviços de API:

### 1. Higienização de Payloads de Atualização (PUT)
*   **Regra de Ouro**: O identificador primário do registro (`id`) deve ser enviado **somente na URL** da rota e **nunca** no corpo (body) das requisições de atualização (`PUT` / `PATCH`).
*   **Motivo**: O backend utiliza o validador estrito do NestJS (`forbidNonWhitelisted: true`). O envio da propriedade `id` no corpo dispara o erro de validação `"property id should not exist"` (HTTP 400 Bad Request).
*   **Como fazer no Frontend**: Nos métodos de serviço do Angular, utilize sempre a desestruturação para isolar o ID e enviar apenas o restante das propriedades no corpo:
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
*   **Regra de Ouro**: Nunca use a sintaxe simplificada `[(ngModel)]` em componentes `<po-combo>`.
*   **Motivo**: O `po-combo` possui um Output homônimo (`ngModelChange`) que gera conflito sintático no compilador estrito do Angular em modo de produção (erro `NG8007`).
*   **Como fazer**: Separe explicitamente a escrita e a leitura de eventos usando `[ngModel]` e o Output oficial `(p-change)` do PO-UI:
    ```html
    <po-combo
      name="icon"
      [ngModel]="program.icon"
      (p-change)="program.icon = $event"
      [p-options]="iconOptions">
    </po-combo>
    ```

### 3. Associação de Enums Estritos no HTML (`TS2322`)
*   **Regra de Ouro**: Nunca passe strings literais cruas (ex: `[p-filter-mode]="'contains'"`) para atributos de componentes do PO-UI que esperam Enums estritos no TypeScript.
*   **Motivo**: O compilador estrito do Angular rejeita strings literais para enums customizados (erro `TS2322`).
*   **Como fazer**: Importe o enum no arquivo TypeScript do componente, declare-o como propriedade de classe e associe-a por bind de propriedade no HTML:
    *   *No arquivo TypeScript:*
        ```typescript
        import { PoComboFilterMode } from "@po-ui/ng-components";
        
        export class ProgramFormComponent {
          readonly filterModeContains = PoComboFilterMode.contains;
        }
        ```
    *   *No template HTML:*
        ```html
        [p-filter-mode]="filterModeContains"
        ```

### 4. Uso do Componente de Lookup (`po-lookup`) para Tabelas Acessórias Volumosas
*   **Regra de Ouro**: Sempre utilize o componente `<po-lookup>` em vez do `<po-combo>` ou `<po-select>` ao selecionar dados provenientes de tabelas acessórias ou de grande volume (ex: Municípios, Clientes, Fornecedores, Produtos, etc.).
*   **Motivo**: O `<po-combo>` e o `<po-select>` exigem carregar todas as opções de dados na memória do navegador de uma única vez, o que degrada consideravelmente a performance em bases de dados volumosas. O `<po-lookup>` soluciona isso realizando chamadas de filtragem assíncronas e paginação sob demanda diretamente no backend através de um `PoLookupFilter`, além de dispor de uma janela modal nativa com busca de múltiplos termos e visualização detalhada em grid.
*   **Como Fazer no Frontend**:
    1. Forneça ou injete uma classe de serviço Angular que implemente a interface `PoLookupFilter` para consultar o backend.
    2. Adicione o componente `<po-lookup>` no template HTML vinculando-o à classe injetada:
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

### 5. Registro de Novas Rotinas (Controle de Acesso)
*   **Regra de Ouro**: Sempre que uma nova tela/rotina for criada no frontend, é obrigatório criar o seu respectivo registro nas tabelas de controle de acesso do banco de dados para que ela apareça no Menu Lateral e tenha suas permissões validadas.
*   **Passo a Passo Obrigatório**:
    1.  **Frontend (`app.routes.ts`)**: Mapear a rota passando o `controller` (ex: `data: { controller: 'MinhaNovaRotinaList' }`). Lembre-se: as telas filhas (como os Forms de edição/criação) geralmente usam o *mesmo controller* da lista pai para herdar permissões, então você não precisa inserir a tela de Form no banco, apenas a de List.
    2.  **Banco de Dados (`system_program`)**: Criar a instrução de `INSERT INTO system_program` vinculando o `controller` a um `system_module_id` existente.
    3.  **Banco de Dados (`system_group_program`)**: Criar a instrução de `INSERT INTO system_group_program` concedendo as permissões (view, insert, update, delete) aos grupos necessários (ex: `ADMIN`).
    4.  **Consolidação**: Sempre registre esses `INSERTS` atualizando o script de inicialização do banco correspondente (ex: `database/crm/04-initial-data.sql`) para manter as migrações consistentes.

---

## 📝 Padrão de Codificação e Resposta
- **Respostas Diretas**: Escreva o código pronto para uso (sem placeholders ou trechos comentados de "adicione sua lógica aqui").
- **Exemplo de Componente Standalone**:
  ```typescript
  import { Component, inject, signal } from '@angular/core';
  import { PoPageDefaultModule, PoButtonModule, PoNotificationService } from '@po-ui/ng-components';

  @Component({
    selector: 'app-crm-exemplo',
    standalone: true,
    imports: [PoPageDefaultModule, PoButtonModule],
    templateUrl: './crm-exemplo.component.html',
    styleUrl: './crm-exemplo.component.css'
  })
  export class CrmExemploComponent {
    private notification = inject(PoNotificationService);
    isLoading = signal<boolean>(false);

    salvarRegistro() {
      this.isLoading.set(true);
      // Lógica de salvamento...
      this.notification.success('Registro salvo com sucesso!');
      this.isLoading.set(false);
    }
  }
  ```
- **Templates Limpos**: Utilize estruturas semânticas do HTML5 e tire proveito dos componentes de layout do PO-UI (`po-page-default`, `po-container`, `po-widget`) para garantir responsividade e alinhamento visual perfeito.


---

# Reversa

> Framework de Engenharia Reversa instalado neste projeto.

## Como usar

Digite `/reversa` para ativar o Reversa e iniciar ou retomar a análise do projeto.

## Comportamento ao ativar

Quando o usuário digitar `/reversa` ou a palavra `reversa` sozinha em uma mensagem:

1. Ative o skill `reversa` disponível em `.agents/skills/reversa/SKILL.md`
2. Leia o SKILL.md na íntegra e siga exatamente as instruções do Reversa

## Regra não-negociável

Nunca apague, modifique ou sobrescreva arquivos pré-existentes do projeto legado.
O Reversa escreve **apenas** em `.reversa/` e `_reversa_sdd/`.

leia o README.md