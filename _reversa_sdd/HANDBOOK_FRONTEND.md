# 📖 Handbook Frontend rcgCRM — Implementação Offline

**Data:** Maio 2026  
**Versão:** 1.0 (Offline Completo)  
**Propósito:** Guia prático para implementar novas telas, corrigir erros e manter padrões arquiteturais.

---

## 📑 Índice Rápido

1. [Setup & Estrutura](#1-setup--estrutura)
2. [Padrão de Serviço](#2-padrão-de-serviço)
3. [Padrão de Componente](#3-padrão-de-componente)
4. [Guia: Criar Nova Tela CRUD](#4-guia-criar-nova-tela-crud)
5. [Padrão de Template](#5-padrão-de-template)
6. [Diretivas & Pipes](#6-diretivas--pipes)
7. [Tratamento de Erros](#7-tratamento-de-erros)
8. [Troubleshooting](#8-troubleshooting)
9. [Snippets Prontos](#9-snippets-prontos)
10. [Checklist de Validação](#10-checklist-de-validação)

---

## 1. Setup & Estrutura

### 1.1 Estrutura de Pastas

```
frontend/src/app/
├── services/
│   ├── auth.ts                    # Autenticação
│   ├── analytics.ts               # Analytics/Dashboard
│   ├── vendedor.ts                # Vendedores
│   ├── cliente.ts                 # Clientes
│   ├── [seu-novo-servico].ts     # Novo serviço aqui
│   └── models/                    # ✅ ADICIONAR (não existe)
│       ├── auth.model.ts
│       ├── vendedor.model.ts
│       └── [seu-modelo].ts
├── interceptors/
│   ├── auth.interceptor.ts        # Bearer Token
│   └── error.interceptor.ts       # ✅ ADICIONAR (não existe)
├── guards/
│   └── auth.guard.ts              # ✅ ADICIONAR (não existe)
├── directives/                    # ✅ CRIAR (não existe)
│   ├── has-permission.directive.ts
│   └── loading.directive.ts
├── pages/
│   ├── login/
│   ├── home/
│   ├── analytics/dashboard/
│   ├── [seu-novo-modulo]/         # Nova tela/módulo aqui
│   │   ├── [pagina].ts
│   │   ├── [pagina].html
│   │   └── [pagina].css
│   └── ...
└── app.config.ts                  # Configuração global
```

### 1.2 Passos Iniciais para Nova Feature

```bash
# 1. Criar pasta do novo módulo
mkdir frontend/src/app/pages/meu-novo-modulo

# 2. Criar pasta do novo serviço
mkdir frontend/src/app/services/models (se não existir)

# 3. Criar arquivos base
touch frontend/src/app/pages/meu-novo-modulo/meu-novo-modulo.ts
touch frontend/src/app/pages/meu-novo-modulo/meu-novo-modulo.html
touch frontend/src/app/pages/meu-novo-modulo/meu-novo-modulo.css
touch frontend/src/app/services/meu-novo-servico.ts
touch frontend/src/app/services/models/meu-novo.model.ts
```

---

## 2. Padrão de Serviço

### 2.1 Template Serviço CRUD Completo

**Arquivo:** `frontend/src/app/services/seu-novo-servico.ts`

```typescript
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

// ===== IMPORTAR MODELO (tipagem forte)
import { SeuRecurso, SeuRecursoResponse } from './models/seu-novo.model';

@Injectable({
  providedIn: 'root'  // Disponível globalmente
})
export class SeuNovoServico {
  
  // ===== CONFIGURAÇÃO
  private readonly API_URL = `${environment.apiUrl}/seu-endpoint`;
  private readonly API_TIMEOUT = 30000; // 30 segundos

  // ===== INJEÇÃO DE DEPENDÊNCIA
  constructor(private http: HttpClient) { }

  // ===== UTILITÁRIOS
  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });
  }

  private getParams(filters: any): HttpParams {
    let params = new HttpParams();
    Object.keys(filters).forEach(key => {
      if (filters[key] !== undefined && filters[key] !== null && filters[key] !== '') {
        params = params.set(key, filters[key].toString());
      }
    });
    return params;
  }

  // ===== OPERAÇÕES CRUD

  /**
   * Retorna lista paginada de recursos
   * @param page Número da página (começa em 1)
   * @param limit Quantidade por página
   * @param filters Filtros adicionais (ex: { status: 'A', search: 'João' })
   */
  findAll(page: number = 1, limit: number = 10, filters: any = {}): Observable<SeuRecursoResponse> {
    const params = this.getParams({
      page,
      limit,
      ...filters
    });

    return this.http.get<SeuRecursoResponse>(this.API_URL, {
      headers: this.getHeaders(),
      params: params
    });
  }

  /**
   * Retorna um único recurso por ID
   * @param id ID do recurso
   */
  findOne(id: number): Observable<SeuRecurso> {
    return this.http.get<SeuRecurso>(`${this.API_URL}/${id}`, {
      headers: this.getHeaders()
    });
  }

  /**
   * Cria ou atualiza um recurso
   * @param data Dados do recurso
   */
  save(data: SeuRecurso): Observable<SeuRecurso> {
    if (data.id) {
      // PUT para atualização
      return this.http.put<SeuRecurso>(`${this.API_URL}/${data.id}`, data, {
        headers: this.getHeaders()
      });
    }
    // POST para criação
    return this.http.post<SeuRecurso>(this.API_URL, data, {
      headers: this.getHeaders()
    });
  }

  /**
   * Deleta um recurso
   * @param id ID do recurso
   */
  delete(id: number): Observable<any> {
    return this.http.delete(`${this.API_URL}/${id}`, {
      headers: this.getHeaders()
    });
  }

  /**
   * Operação customizada (exemplo)
   * @param id ID do recurso
   * @param action Ação a executar
   */
  executeAction(id: number, action: string): Observable<any> {
    return this.http.post(`${this.API_URL}/${id}/${action}`, {}, {
      headers: this.getHeaders()
    });
  }
}
```

### 2.2 Modelo/Interface

**Arquivo:** `frontend/src/app/services/models/seu-novo.model.ts`

```typescript
/**
 * Modelo principal do recurso
 */
export interface SeuRecurso {
  id?: number;
  nome: string;
  email?: string;
  status: 'A' | 'I';  // Ativo | Inativo
  dataCriacao?: Date;
  dataAtualizacao?: Date;
  // Adicione mais campos conforme necessário
}

/**
 * Response da API com paginação
 */
export interface SeuRecursoResponse {
  items: SeuRecurso[];
  total: number;
  page: number;
  limit: number;
  totalPages: number;
}

/**
 * Filtros para listagem
 */
export interface SeuRecursoFilter {
  page?: number;
  limit?: number;
  search?: string;
  status?: 'A' | 'I';
  ordenarPor?: 'nome' | 'dataCriacao';
  ordem?: 'asc' | 'desc';
}
```

---

## 3. Padrão de Componente

### 3.1 Componente Standalone Completo

**Arquivo:** `frontend/src/app/pages/meu-novo-modulo/meu-novo-modulo.ts`

```typescript
import { Component, OnInit, ViewChild, OnDestroy, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import {
  PoModule,
  PoPageAction,
  PoTableColumn,
  PoTableAction,
  PoSelectOption,
  PoModalComponent,
  PoNotificationService,
  PoPageComponent
} from '@po-ui/ng-components';

import { SeuNovoServico } from '../../../services/seu-novo-servico';
import { SeuRecurso, SeuRecursoFilter } from '../../../services/models/seu-novo.model';

@Component({
  selector: 'app-meu-novo-modulo',
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule, ReactiveFormsModule],
  templateUrl: './meu-novo-modulo.html',
  styleUrls: ['./meu-novo-modulo.css']
})
export class MeuNovoModuloComponent implements OnInit, OnDestroy {

  // ===== DEPENDENCY INJECTION
  private service = inject(SeuNovoServico);
  private notification = inject(PoNotificationService);
  private router = inject(Router);
  private fb = inject(FormBuilder);

  // ===== UNSUBSCRIBE
  private destroy$ = new Subject<void>();

  // ===== STATE - Dados principais
  items: SeuRecurso[] = [];
  selectedItem: SeuRecurso = this.getEmptyItem();
  isLoading = false;
  isLoadingModal = false;

  // ===== FILTROS
  filters: SeuRecursoFilter = {
    page: 1,
    limit: 10,
    search: '',
    status: undefined,
    ordenarPor: 'dataCriacao',
    ordem: 'desc'
  };

  // ===== FORM
  form: FormGroup = this.fb.group({
    id: [null],
    nome: ['', [Validators.required, Validators.minLength(3)]],
    email: ['', [Validators.email]],
    status: ['A', Validators.required]
  });

  // ===== UI CONFIG - Selects
  statusOptions: PoSelectOption[] = [
    { label: 'Ativo', value: 'A' },
    { label: 'Inativo', value: 'I' }
  ];

  ordenarPorOptions: PoSelectOption[] = [
    { label: 'Data de Criação', value: 'dataCriacao' },
    { label: 'Nome', value: 'nome' },
    { label: 'Email', value: 'email' }
  ];

  ordemOptions: PoSelectOption[] = [
    { label: 'Descendente', value: 'desc' },
    { label: 'Ascendente', value: 'asc' }
  ];

  // ===== UI CONFIG - Tabela
  readonly columns: PoTableColumn[] = [
    { property: 'id', label: 'ID', width: '80px', type: 'number' },
    { property: 'nome', label: 'Nome', width: '300px' },
    { property: 'email', label: 'Email', width: '250px' },
    {
      property: 'status',
      label: 'Status',
      width: '100px',
      type: 'label',
      labels: [
        { value: 'A', color: 'color-10', label: 'Ativo' },
        { value: 'I', color: 'color-07', label: 'Inativo' }
      ]
    },
    { property: 'dataCriacao', label: 'Criado em', width: '150px', type: 'date' }
  ];

  readonly tableActions: PoTableAction[] = [
    { label: 'Editar', action: (item: any) => this.edit(item), icon: 'po-icon-edit' },
    { label: 'Deletar', action: (item: any) => this.delete(item), icon: 'po-icon-delete' }
  ];

  // ===== UI CONFIG - Página
  readonly pageActions: PoPageAction[] = [
    { label: 'Novo', action: this.new.bind(this), icon: 'po-icon-plus' },
    { label: 'Atualizar', action: this.reload.bind(this), icon: 'po-icon-refresh' }
  ];

  // ===== REFERÊNCIAS DO TEMPLATE
  @ViewChild('modalEdicao') modalEdicao!: PoModalComponent;
  @ViewChild('paginaPrincipal') paginaPrincipal!: PoPageComponent;

  ngOnInit(): void {
    this.loadData();
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  // ===== MÉTODOS PRINCIPAIS

  /**
   * Carrega dados da API com filtros
   */
  loadData(): void {
    this.isLoading = true;

    this.service.findAll(this.filters.page, this.filters.limit, {
      search: this.filters.search,
      status: this.filters.status,
      ordenarPor: this.filters.ordenarPor,
      ordem: this.filters.ordem
    })
    .pipe(takeUntil(this.destroy$))
    .subscribe({
      next: (response) => {
        this.items = response.items;
        this.isLoading = false;
      },
      error: (err) => {
        console.error('Erro ao carregar dados:', err);
        this.notification.error('Falha ao carregar dados');
        this.isLoading = false;
      }
    });
  }

  /**
   * Abre modal para criar novo item
   */
  new(): void {
    this.selectedItem = this.getEmptyItem();
    this.form.reset({ status: 'A' });
    this.modalEdicao.open();
  }

  /**
   * Abre modal para editar item
   */
  edit(item: SeuRecurso): void {
    this.isLoadingModal = true;

    this.service.findOne(item.id!)
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: (data) => {
          this.selectedItem = data;
          this.form.patchValue(data);
          this.isLoadingModal = false;
          this.modalEdicao.open();
        },
        error: (err) => {
          console.error('Erro ao carregar item:', err);
          this.notification.error('Falha ao carregar dados do item');
          this.isLoadingModal = false;
        }
      });
  }

  /**
   * Salva (cria ou atualiza) item
   */
  save(): void {
    if (this.form.invalid) {
      this.notification.warning('Preencha todos os campos obrigatórios');
      return;
    }

    this.isLoadingModal = true;
    const data = this.form.value;

    this.service.save(data)
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: () => {
          this.notification.success(
            data.id ? 'Atualizado com sucesso' : 'Criado com sucesso'
          );
          this.modalEdicao.close();
          this.isLoadingModal = false;
          this.loadData();
        },
        error: (err) => {
          console.error('Erro ao salvar:', err);
          this.notification.error('Falha ao salvar dados');
          this.isLoadingModal = false;
        }
      });
  }

  /**
   * Deleta item após confirmação
   */
  delete(item: SeuRecurso): void {
    if (!confirm(`Tem certeza que deseja deletar "${item.nome}"?`)) {
      return;
    }

    this.isLoading = true;

    this.service.delete(item.id!)
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: () => {
          this.notification.success('Deletado com sucesso');
          this.loadData();
        },
        error: (err) => {
          console.error('Erro ao deletar:', err);
          this.notification.error('Falha ao deletar item');
          this.isLoading = false;
        }
      });
  }

  /**
   * Recarga dados
   */
  reload(): void {
    this.filters.page = 1;
    this.loadData();
  }

  /**
   * Executa quando filtros mudam
   */
  onFilterChange(): void {
    this.filters.page = 1; // Reset para primeira página
    this.loadData();
  }

  // ===== UTILITÁRIOS

  private getEmptyItem(): SeuRecurso {
    return {
      nome: '',
      email: '',
      status: 'A'
    };
  }

  // ===== GETTERS para o template
  get isFormValid(): boolean {
    return this.form.valid;
  }

  get submitButtonLabel(): string {
    return this.form.get('id')?.value ? 'Atualizar' : 'Criar';
  }
}
```

---

## 4. Guia: Criar Nova Tela CRUD

### 4.1 Passo a Passo

**Tempo estimado:** 30-45 minutos

#### Passo 1: Criar Modelo
```bash
# 1. Criar arquivo
touch frontend/src/app/services/models/seu-novo.model.ts

# 2. Adicionar interfaces (copiar template da seção 2.2)
```

#### Passo 2: Criar Serviço
```bash
# 1. Criar arquivo
touch frontend/src/app/services/seu-novo-servico.ts

# 2. Copiar template da seção 2.1 e ajustar API_URL
```

#### Passo 3: Criar Componente
```bash
# 1. Criar pasta
mkdir frontend/src/app/pages/seu-novo-modulo

# 2. Criar arquivos
touch frontend/src/app/pages/seu-novo-modulo/seu-novo-modulo.ts
touch frontend/src/app/pages/seu-novo-modulo/seu-novo-modulo.html
touch frontend/src/app/pages/seu-novo-modulo/seu-novo-modulo.css
```

#### Passo 4: Copiar código
- Componente TS: Usar template da seção 3.1
- Template HTML: Ver seção 5 (padrão de template)
- CSS: Ver seção 5.2

#### Passo 5: Registrar Rota
```typescript
// frontend/src/app/app.routes.ts
export const routes: Routes = [
  // ... outras rotas
  {
    path: 'seu-novo-modulo',
    loadComponent: () => import('./pages/seu-novo-modulo/seu-novo-modulo')
      .then(m => m.SeuNovoModuloComponent),
    canActivate: [authGuard]
  }
];
```

#### Passo 6: Testar no navegador
```bash
ng serve
# Acesse: http://localhost:4200/seu-novo-modulo
```

---

## 5. Padrão de Template

### 5.1 Template HTML Completo

**Arquivo:** `frontend/src/app/pages/seu-novo-modulo/seu-novo-modulo.html`

```html
<po-page
  p-title="Gestão de Recursos"
  [p-actions]="pageActions"
  [p-loading]="isLoading">

  <!-- SEÇÃO 1: FILTROS -->
  <po-widget p-title="Filtros" p-help="Use os filtros para refinar a busca">
    <div class="po-row">
      <!-- Busca por texto -->
      <po-input
        class="po-md-4"
        [(ngModel)]="filters.search"
        (p-change)="onFilterChange()"
        p-label="Buscar por nome"
        p-placeholder="Digite para filtrar...">
      </po-input>

      <!-- Status -->
      <po-select
        class="po-md-2"
        [(ngModel)]="filters.status"
        (p-change)="onFilterChange()"
        [p-options]="statusOptions"
        p-label="Status"
        p-placeholder="Todos">
      </po-select>

      <!-- Ordenação -->
      <po-select
        class="po-md-3"
        [(ngModel)]="filters.ordenarPor"
        (p-change)="onFilterChange()"
        [p-options]="ordenarPorOptions"
        p-label="Ordenar por">
      </po-select>

      <!-- Ordem -->
      <po-select
        class="po-md-3"
        [(ngModel)]="filters.ordem"
        (p-change)="onFilterChange()"
        [p-options]="ordemOptions"
        p-label="Ordem">
      </po-select>
    </div>
  </po-widget>

  <!-- SEÇÃO 2: TABELA -->
  <po-table
    [p-columns]="columns"
    [p-items]="items"
    [p-actions]="tableActions"
    [p-loading]="isLoading"
    p-striped="true"
    p-kind="secondary">
  </po-table>

  <!-- SEÇÃO 3: MODAL DE EDIÇÃO -->
  <po-modal
    #modalEdicao
    [p-title]="form.get('id')?.value ? 'Editar Recurso' : 'Novo Recurso'"
    p-size="lg"
    p-primary-action="Salvar"
    p-primary-label="Salvar"
    [p-primary-loading]="isLoadingModal"
    (p-primary-action)="save()">

    <form [formGroup]="form">
      <div class="po-row">
        <!-- Nome -->
        <po-input
          class="po-md-12"
          formControlName="nome"
          p-label="Nome *"
          p-placeholder="Digite o nome"
          p-required="true">
        </po-input>

        <!-- Email -->
        <po-input
          class="po-md-6"
          formControlName="email"
          p-label="Email"
          p-placeholder="Digite o email"
          p-kind="email">
        </po-input>

        <!-- Status -->
        <po-select
          class="po-md-6"
          formControlName="status"
          [p-options]="statusOptions"
          p-label="Status"
          p-required="true">
        </po-select>
      </div>
    </form>

  </po-modal>

</po-page>
```

### 5.2 CSS Padrão

**Arquivo:** `frontend/src/app/pages/seu-novo-modulo/seu-novo-modulo.css`

```css
/* Espaçamento geral */
:host {
  display: block;
  padding: 0;
}

/* Widgets */
::ng-deep .po-widget {
  margin-bottom: 24px;
}

/* Tabela responsiva */
::ng-deep .po-table {
  border-radius: 4px;
}

/* Modal */
::ng-deep .po-modal {
  z-index: 1000;
}

/* Alerta/Notificação */
::ng-deep .po-notification {
  z-index: 1001;
}

/* Cores customizadas (opcional) */
::ng-deep .po-button-primary {
  background-color: #0C8040;
}

::ng-deep .po-button-primary:hover {
  background-color: #0a6430;
}
```

---

## 6. Diretivas & Pipes

### 6.1 Diretiva de Permissão (Implementar)

**Arquivo:** `frontend/src/app/directives/has-permission.directive.ts`

```typescript
import { Directive, Input, TemplateRef, ViewContainerRef, OnInit, inject } from '@angular/core';
import { AuthService } from '../services/auth';

/**
 * Diretiva: Mostra/oculta elemento baseado em permissão
 * Uso: *appHasPermission="'admin.deletar'"
 */
@Directive({
  selector: '[appHasPermission]',
  standalone: true
})
export class HasPermissionDirective implements OnInit {
  @Input() appHasPermission!: string;

  private templateRef = inject(TemplateRef<any>);
  private viewContainer = inject(ViewContainerRef);
  private authService = inject(AuthService);

  ngOnInit() {
    const user = this.authService.getUser();
    const permissoes = user?.permissoes || [];

    if (permissoes.includes(this.appHasPermission)) {
      this.viewContainer.createEmbeddedView(this.templateRef);
    } else {
      this.viewContainer.clear();
    }
  }
}
```

**Uso no template:**
```html
<button *appHasPermission="'admin.deletar'" class="po-button">Deletar</button>
```

### 6.2 Pipe Customizado (Exemplo)

**Arquivo:** `frontend/src/app/pipes/safe-html.pipe.ts`

```typescript
import { Pipe, PipeTransform } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';

@Pipe({
  name: 'safeHtml',
  standalone: true
})
export class SafeHtmlPipe implements PipeTransform {
  constructor(private sanitizer: DomSanitizer) {}

  transform(html: string) {
    return this.sanitizer.bypassSecurityTrustHtml(html);
  }
}
```

**Uso:**
```html
<div [innerHTML]="htmlContent | safeHtml"></div>
```

---

## 7. Tratamento de Erros

### 7.1 Error Interceptor (Implementar)

**Arquivo:** `frontend/src/app/interceptors/error.interceptor.ts`

```typescript
import { HttpInterceptorFn, HttpErrorResponse } from '@angular/common/http';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { catchError, throwError } from 'rxjs';
import { PoNotificationService } from '@po-ui/ng-components';

export const errorInterceptor: HttpInterceptorFn = (req, next) => {
  const notification = inject(PoNotificationService);
  const router = inject(Router);

  return next(req).pipe(
    catchError((error: any) => {
      let errorMessage = 'Erro desconhecido';

      if (error instanceof HttpErrorResponse) {
        switch (error.status) {
          case 0:
            errorMessage = 'Erro de conexão com o servidor';
            break;
          case 400:
            errorMessage = error.error?.message || 'Requisição inválida';
            break;
          case 401:
            errorMessage = 'Sessão expirada. Faça login novamente';
            localStorage.removeItem('token');
            router.navigate(['/login']);
            break;
          case 403:
            errorMessage = 'Você não tem permissão para esta ação';
            break;
          case 404:
            errorMessage = 'Recurso não encontrado';
            break;
          case 500:
            errorMessage = 'Erro no servidor. Tente novamente mais tarde';
            break;
          default:
            errorMessage = `Erro HTTP ${error.status}`;
        }
      }

      console.error('Erro capturado:', { status: error.status, message: errorMessage, error });
      notification.error(errorMessage);

      return throwError(() => error);
    })
  );
};
```

**Registrar em app.config.ts:**
```typescript
export const appConfig: ApplicationConfig = {
  providers: [
    provideHttpClient(withInterceptors([authInterceptor, errorInterceptor])),
    // ...
  ]
};
```

### 7.2 Tratamento de Erro Local

```typescript
// No componente
this.service.delete(id).subscribe({
  next: () => {
    this.notification.success('Deletado com sucesso');
  },
  error: (err) => {
    // Erro específico do domínio
    if (err.status === 409) {
      this.notification.error('Não pode deletar: recurso está em uso');
    } else {
      this.notification.error('Erro ao deletar');
    }
    console.error('Delete error:', err);
  }
});
```

---

## 8. Troubleshooting

### 8.1 Problemas Comuns & Soluções

| Problema | Causa | Solução |
|----------|-------|---------|
| **"any is not assignable to never"** | Tipagem inconsistente | Use interfaces em vez de `any` |
| **"Cannot find module"** | Import path errado | Verifique path relativo: `../../../services/` |
| **Componente não renderiza** | Não registrado em standalone | Adicione em `imports: [...]` |
| **Modal não abre** | ViewChild não inicializado | Use `{ static: true }` ou ngAfterViewInit |
| **Interceptor não funciona** | Não registrado em providers | Adicione em `appConfig` |
| **401 Unauthorized** | Token expirado/inválido | Verifique localStorage ou login novamente |
| **Tabela vazia** | Service retorna erro silencioso | Verifique network tab do navegador (F12) |
| **Form inválido** | Validators não configurados | Use `[Validators.required, Validators.minLength(3)]` |
| **Filtro não funciona** | Query params mal formatados | Use `HttpParams` para construir corretamente |
| **Performance lenta** | Muitos renders | Use `OnPush` strategy: `changeDetection: ChangeDetectionStrategy.OnPush` |

### 8.2 Debug Checklist

```typescript
// 1. Verifique se o service está injetado
console.log('Service:', this.service);

// 2. Verifique se o request está sendo feito
// F12 > Network tab > Procure a requisição

// 3. Verifique se o token está em localStorage
console.log('Token:', localStorage.getItem('token'));

// 4. Verifique a resposta da API
this.service.findAll().subscribe(
  (res) => console.log('Response:', res),
  (err) => console.error('Error:', err)
);

// 5. Verifique se o componente foi registrado
// Veja em app.routes.ts se existe a rota

// 6. Verifique se o modelo está correto
console.log('Items:', this.items);
```

---

## 9. Snippets Prontos

### 9.1 Snippet: Listar com Paginação

```typescript
// Componente
currentPage = 1;
pageSize = 10;
totalRecords = 0;

loadPage(page: number): void {
  this.currentPage = page;
  this.service.findAll(page, this.pageSize)
    .subscribe(res => {
      this.items = res.items;
      this.totalRecords = res.total;
    });
}

// Template
<po-paginator
  [p-page]="currentPage"
  [p-page-size]="pageSize"
  [p-total]="totalRecords"
  (p-page-change)="loadPage($event)">
</po-paginator>
```

### 9.2 Snippet: Upload de Arquivo

```typescript
// Componente
uploadFile(event: any): void {
  const file = event.files[0];
  const formData = new FormData();
  formData.append('file', file);

  this.http.post(`${this.API_URL}/upload`, formData, {
    headers: new HttpHeaders({ 'Authorization': `Bearer ${token}` })
  }).subscribe(
    (res) => this.notification.success('Arquivo enviado'),
    (err) => this.notification.error('Erro ao enviar arquivo')
  );
}

// Template
<po-upload
  p-label="Selecione um arquivo"
  (p-selected)="uploadFile($event)">
</po-upload>
```

### 9.3 Snippet: Export para Excel

```typescript
// Componente
exportToExcel(): void {
  const data = this.items.map(item => ({
    'ID': item.id,
    'Nome': item.nome,
    'Email': item.email,
    'Status': item.status === 'A' ? 'Ativo' : 'Inativo'
  }));

  // Usar biblioteca: npm install xlsx
  import('xlsx').then(XLSX => {
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Dados');
    XLSX.writeFile(wb, 'dados.xlsx');
  });
}
```

### 9.4 Snippet: Validação Customizada

```typescript
// Validator customizado
import { AbstractControl, ValidationErrors } from '@angular/forms';

export function emailDomain(domain: string) {
  return (control: AbstractControl): ValidationErrors | null => {
    const email = control.value;
    if (!email) return null;

    if (email.endsWith(`@${domain}`)) {
      return null;
    }
    return { invalidDomain: { value: control.value } };
  };
}

// Uso no form
this.form = this.fb.group({
  email: ['', [Validators.required, emailDomain('empresa.com')]]
});
```

### 9.5 Snippet: Busca em Tempo Real (Debounce)

```typescript
import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';
import { Subject } from 'rxjs';

// Componente
searchSubject$ = new Subject<string>();

constructor() {
  this.searchSubject$
    .pipe(
      debounceTime(300),           // Espera 300ms
      distinctUntilChanged(),       // Só se mudou
      switchMap(term => this.service.search(term))
    )
    .subscribe(results => this.items = results);
}

onSearch(term: string): void {
  this.searchSubject$.next(term);
}

// Template
<po-input
  [(ngModel)]="searchTerm"
  (p-change)="onSearch($event)"
  p-placeholder="Digite para buscar...">
</po-input>
```

---

## 10. Checklist de Validação

### 10.1 Antes de Commitar

- [ ] **Tipagem Forte**
  - [ ] Sem `any` em serviços
  - [ ] Interfaces definidas em `models/`
  - [ ] Response da API tipado

- [ ] **Componente**
  - [ ] `OnDestroy` implementado
  - [ ] `takeUntil(this.destroy$)` em todos os subscribes
  - [ ] Validação de form completa
  - [ ] Loading states definidos

- [ ] **Template**
  - [ ] Sem erros de console
  - [ ] Responsivo (testado em mobile)
  - [ ] Acessibilidade: labels nos inputs
  - [ ] Mensagens de erro amigáveis

- [ ] **Serviço**
  - [ ] CRUD completo (findAll, findOne, save, delete)
  - [ ] Headers com Bearer Token
  - [ ] Tratamento de erro básico

- [ ] **Rota**
  - [ ] Registrada em `app.routes.ts`
  - [ ] Com `canActivate: [authGuard]`
  - [ ] Lazy loaded (loadComponent)

- [ ] **Performance**
  - [ ] Sem memory leaks (unsubscribe)
  - [ ] Paginated se > 100 registros
  - [ ] Sem loops infinitos

- [ ] **Segurança**
  - [ ] Token em localStorage (não em session)
  - [ ] Sanitização de HTML (usar `[innerHTML]` com cuidado)
  - [ ] Validação no frontend & backend

### 10.2 Antes de Deploy

- [ ] Build sem erros: `ng build --configuration production`
- [ ] Nenhum `console.log()` em produção
- [ ] Environment correto (prod.ts)
- [ ] Testes rodando: `ng test`
- [ ] Lint sem warnings: `ng lint`
- [ ] Todos os interceptadores registrados
- [ ] Funcionalidade testada manualmente em todos os fluxos

---

## 🎯 Resumo de Passos Rápidos

### Para Adicionar Nova Tela em 5 Minutos

1. **Copie modelo + serviço** (seções 2.1, 2.2)
2. **Copie componente** (seção 3.1)
3. **Copie template** (seção 5.1)
4. **Registre rota** (app.routes.ts)
5. **Teste no navegador**

### Ajustes Necessários em Cada Novo Componente

```typescript
// 1. Mude API_URL
private readonly API_URL = `${environment.apiUrl}/seu-novo-endpoint`;

// 2. Mude o nome do serviço
export class SeuNovoServico { ... }

// 3. Mude o nome do componente
export class SeuNovoModuloComponent { ... }

// 4. Mude o selector
selector: 'app-seu-novo-modulo',

// 5. Registre a rota
{
  path: 'seu-novo-modulo',
  loadComponent: () => import('./pages/seu-novo-modulo/seu-novo-modulo')
    .then(m => m.SeuNovoModuloComponent),
  canActivate: [authGuard]
}
```

---

## 📞 Contato & Suporte

**Se encontrar erro:**
1. Verifique o Troubleshooting (seção 8)
2. Verifique o console do navegador (F12)
3. Verifique a aba Network (F12)
4. Verifique se seguiu o padrão exato

**Padrão Seguido:** Angular 21 + PO-UI 21 + RxJS 7 + TypeScript 5.9

---

**Gerado:** Maio 2026  
**Status:** ✅ Offline Completo — Pronto para implementação com humano ou IA
