# 📝 Exemplo Prático: Implementar novo CRUD (Completo)

**Duração:** 30 minutos de implementação + 10 min de teste

---

## 📋 Objetivo

Implementar um novo recurso completo: **Tabela de Preço (TabelaPreco)**
- Listar com paginação e filtros
- Criar novo
- Editar existente
- Deletar

---

## 🎨 PASSO 1: Frontend — Modelo (2 min)

**Arquivo:** `frontend/src/app/services/models/tabela-preco.model.ts`

```typescript
export interface TabelaPreco {
  id?: number;
  nome: string;
  descricao?: string;
  margemLucro: number;  // Percentual
  vigenciaInicial: Date;
  vigenciaFinal?: Date;
  status: 'A' | 'I';    // Ativo | Inativo
  dataCriacao?: Date;
}

export interface TabelaPrecoPaginada {
  items: TabelaPreco[];
  total: number;
  page: number;
  limit: number;
  totalPages: number;
}

export interface TabelaPrecofiltro {
  page?: number;
  limit?: number;
  search?: string;
  status?: 'A' | 'I';
}
```

---

## 🎨 PASSO 2: Frontend — Serviço (3 min)

**Arquivo:** `frontend/src/app/services/tabela-preco.ts`

```typescript
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { TabelaPreco, TabelaPrecoPaginada, TabelaPrecofiltro } from './models/tabela-preco.model';

@Injectable({ providedIn: 'root' })
export class TabelaPrecService {
  private readonly API_URL = `${environment.apiUrl}/tabela-preco`;

  constructor(private http: HttpClient) {}

  private getHeaders() {
    const token = localStorage.getItem('token');
    return new HttpHeaders({ 'Authorization': `Bearer ${token}` });
  }

  findAll(filter: TabelaPrecofiltro = {}): Observable<TabelaPrecoPaginada> {
    let params = new HttpParams();
    if (filter.page) params = params.set('page', filter.page.toString());
    if (filter.limit) params = params.set('limit', filter.limit.toString());
    if (filter.search) params = params.set('search', filter.search);
    if (filter.status) params = params.set('status', filter.status);

    return this.http.get<TabelaPrecoPaginada>(this.API_URL, {
      headers: this.getHeaders(),
      params
    });
  }

  findOne(id: number): Observable<TabelaPreco> {
    return this.http.get<TabelaPreco>(`${this.API_URL}/${id}`, {
      headers: this.getHeaders()
    });
  }

  save(data: TabelaPreco): Observable<TabelaPreco> {
    if (data.id) {
      return this.http.put<TabelaPreco>(`${this.API_URL}/${data.id}`, data, {
        headers: this.getHeaders()
      });
    }
    return this.http.post<TabelaPreco>(this.API_URL, data, {
      headers: this.getHeaders()
    });
  }

  delete(id: number): Observable<any> {
    return this.http.delete(`${this.API_URL}/${id}`, {
      headers: this.getHeaders()
    });
  }
}
```

---

## 🎨 PASSO 3: Frontend — Componente (8 min)

**Arquivo:** `frontend/src/app/pages/catalog/tabela-preco/tabela-preco.ts`

```typescript
import { Component, OnInit, OnDestroy, ViewChild, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PoModule, PoPageAction, PoTableColumn, PoTableAction, PoSelectOption, PoModalComponent, PoNotificationService } from '@po-ui/ng-components';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { TabelaPrecService } from '../../../services/tabela-preco';
import { TabelaPreco, TabelaPrecofiltro } from '../../../services/models/tabela-preco.model';

@Component({
  selector: 'app-tabela-preco',
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule, ReactiveFormsModule],
  templateUrl: './tabela-preco.html'
})
export class TabelaPrecComponent implements OnInit, OnDestroy {
  private service = inject(TabelaPrecService);
  private notification = inject(PoNotificationService);
  private fb = inject(FormBuilder);
  private destroy$ = new Subject<void>();

  // State
  items: TabelaPreco[] = [];
  isLoading = false;
  isLoadingModal = false;

  // Filters
  filters: TabelaPrecofiltro = { page: 1, limit: 10, search: '', status: undefined };

  // Form
  form: FormGroup = this.fb.group({
    id: [null],
    nome: ['', [Validators.required, Validators.minLength(3)]],
    descricao: [''],
    margemLucro: [0, [Validators.required, Validators.min(0), Validators.max(100)]],
    vigenciaInicial: ['', Validators.required],
    vigenciaFinal: [''],
    status: ['A', Validators.required]
  });

  // Options
  statusOptions: PoSelectOption[] = [
    { label: 'Ativo', value: 'A' },
    { label: 'Inativo', value: 'I' }
  ];

  // Table Config
  columns: PoTableColumn[] = [
    { property: 'id', label: 'ID', width: '80px' },
    { property: 'nome', label: 'Nome', width: '250px' },
    { property: 'margemLucro', label: 'Margem (%)', width: '120px', type: 'number' },
    { property: 'vigenciaInicial', label: 'Válida de', width: '120px', type: 'date' },
    { property: 'status', label: 'Status', width: '100px', type: 'label', labels: [
      { value: 'A', color: 'color-10', label: 'Ativo' },
      { value: 'I', color: 'color-07', label: 'Inativo' }
    ]}
  ];

  tableActions: PoTableAction[] = [
    { label: 'Editar', action: (item: any) => this.edit(item) },
    { label: 'Deletar', action: (item: any) => this.delete(item) }
  ];

  pageActions: PoPageAction[] = [
    { label: 'Nova', action: this.new.bind(this) },
    { label: 'Atualizar', action: this.reload.bind(this) }
  ];

  @ViewChild('modal') modal!: PoModalComponent;

  ngOnInit() { this.loadData(); }
  ngOnDestroy() { this.destroy$.next(); this.destroy$.complete(); }

  loadData() {
    this.isLoading = true;
    this.service.findAll(this.filters)
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: (res) => {
          this.items = res.items;
          this.isLoading = false;
        },
        error: (err) => {
          this.notification.error('Erro ao carregar tabelas de preço');
          this.isLoading = false;
        }
      });
  }

  new() {
    this.form.reset({ status: 'A' });
    this.modal.open();
  }

  edit(item: TabelaPreco) {
    this.isLoadingModal = true;
    this.service.findOne(item.id!)
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: (data) => {
          this.form.patchValue(data);
          this.isLoadingModal = false;
          this.modal.open();
        },
        error: () => {
          this.notification.error('Erro ao carregar tabela');
          this.isLoadingModal = false;
        }
      });
  }

  save() {
    if (this.form.invalid) {
      this.notification.warning('Preencha todos os campos obrigatórios');
      return;
    }

    this.isLoadingModal = true;
    this.service.save(this.form.value)
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: () => {
          this.notification.success(this.form.get('id')?.value ? 'Atualizada' : 'Criada');
          this.modal.close();
          this.loadData();
        },
        error: () => {
          this.notification.error('Erro ao salvar');
          this.isLoadingModal = false;
        }
      });
  }

  delete(item: TabelaPreco) {
    if (!confirm(`Deletar "${item.nome}"?`)) return;

    this.service.delete(item.id!)
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: () => {
          this.notification.success('Deletada');
          this.loadData();
        },
        error: () => this.notification.error('Erro ao deletar')
      });
  }

  reload() {
    this.filters.page = 1;
    this.loadData();
  }

  onFilterChange() {
    this.filters.page = 1;
    this.loadData();
  }

  get isValid() { return this.form.valid; }
}
```

---

## 🎨 PASSO 4: Frontend — Template (5 min)

**Arquivo:** `frontend/src/app/pages/catalog/tabela-preco/tabela-preco.html`

```html
<po-page p-title="Tabela de Preço" [p-actions]="pageActions" [p-loading]="isLoading">
  
  <!-- Filtros -->
  <po-widget p-title="Filtros">
    <div class="po-row">
      <po-input class="po-md-8" [(ngModel)]="filters.search" (p-change)="onFilterChange()" p-label="Buscar" p-placeholder="Nome da tabela..."></po-input>
      <po-select class="po-md-4" [(ngModel)]="filters.status" (p-change)="onFilterChange()" [p-options]="statusOptions" p-label="Status" p-placeholder="Todos"></po-select>
    </div>
  </po-widget>

  <!-- Tabela -->
  <po-table [p-columns]="columns" [p-items]="items" [p-actions]="tableActions" [p-loading]="isLoading"></po-table>

  <!-- Modal -->
  <po-modal #modal [p-title]="form.get('id')?.value ? 'Editar' : 'Novo'" p-size="lg" p-primary-action="Salvar" [p-primary-loading]="isLoadingModal" (p-primary-action)="save()">
    <form [formGroup]="form">
      <div class="po-row">
        <po-input class="po-md-12" formControlName="nome" p-label="Nome *" p-required="true"></po-input>
        <po-input class="po-md-12" formControlName="descricao" p-label="Descrição"></po-input>
        <po-number class="po-md-6" formControlName="margemLucro" p-label="Margem de Lucro (%) *" p-required="true"></po-number>
        <po-datepicker class="po-md-6" formControlName="vigenciaInicial" p-label="Válida de *" p-required="true"></po-datepicker>
        <po-datepicker class="po-md-6" formControlName="vigenciaFinal" p-label="Válida até"></po-datepicker>
        <po-select class="po-md-6" formControlName="status" [p-options]="statusOptions" p-label="Status" p-required="true"></po-select>
      </div>
    </form>
  </po-modal>

</po-page>
```

---

## 🔧 PASSO 5: Backend — Entity (3 min)

**Arquivo:** `backend/src/modules/tabela-preco/tabela-preco.entity.ts`

```typescript
import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn, Index } from 'typeorm';

@Entity('tabela_preco')
@Index(['status'])
export class TabelaPrecEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ type: 'varchar', length: 255, nullable: false })
  nome: string;

  @Column({ type: 'text', nullable: true })
  descricao: string;

  @Column({ type: 'decimal', precision: 5, scale: 2, nullable: false })
  margemLucro: number;

  @Column({ type: 'date', nullable: false })
  vigenciaInicial: Date;

  @Column({ type: 'date', nullable: true })
  vigenciaFinal: Date;

  @Column({ type: 'enum', enum: ['A', 'I'], default: 'A' })
  status: 'A' | 'I';

  @CreateDateColumn()
  dataCriacao: Date;

  @UpdateDateColumn()
  dataAtualizacao: Date;
}
```

---

## 🔧 PASSO 6: Backend — DTO (2 min)

**Arquivo:** `backend/src/modules/tabela-preco/tabela-preco.dto.ts`

```typescript
import { IsString, IsNumber, IsDate, IsOptional, IsEnum, Min, Max } from 'class-validator';

export class CreateTabelaPrecDto {
  @IsString()
  nome: string;

  @IsOptional()
  @IsString()
  descricao?: string;

  @IsNumber()
  @Min(0)
  @Max(100)
  margemLucro: number;

  @IsDate()
  vigenciaInicial: Date;

  @IsOptional()
  @IsDate()
  vigenciaFinal?: Date;

  @IsOptional()
  @IsEnum(['A', 'I'])
  status?: 'A' | 'I';
}

export class UpdateTabelaPrecDto extends CreateTabelaPrecDto {}

export class ListaTabelaPrecDto {
  page?: number = 1;
  limit?: number = 10;
  search?: string;
  status?: 'A' | 'I';
}
```

---

## 🔧 PASSO 7: Backend — Service (4 min)

**Arquivo:** `backend/src/modules/tabela-preco/tabela-preco.service.ts`

```typescript
import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, Like } from 'typeorm';
import { TabelaPrecEntity } from './tabela-preco.entity';
import { CreateTabelaPrecDto, UpdateTabelaPrecDto, ListaTabelaPrecDto } from './tabela-preco.dto';

@Injectable()
export class TabelaPrecService {
  constructor(
    @InjectRepository(TabelaPrecEntity)
    private repository: Repository<TabelaPrecEntity>
  ) {}

  async findAll(filter: ListaTabelaPrecDto) {
    const { page = 1, limit = 10, search, status } = filter;
    const skip = (page - 1) * limit;

    let query = this.repository.createQueryBuilder('tp');

    if (search) {
      query = query.where('tp.nome LIKE :search', { search: `%${search}%` });
    }
    if (status) {
      query = query.andWhere('tp.status = :status', { status });
    }

    const [items, total] = await query
      .orderBy('tp.dataCriacao', 'DESC')
      .skip(skip)
      .take(limit)
      .getManyAndCount();

    return { items, total, page, limit, totalPages: Math.ceil(total / limit) };
  }

  async findOne(id: number) {
    const item = await this.repository.findOne({ where: { id } });
    if (!item) throw new NotFoundException('Tabela não encontrada');
    return item;
  }

  async create(dto: CreateTabelaPrecDto) {
    return this.repository.save({
      ...dto,
      status: 'A',
      dataCriacao: new Date()
    });
  }

  async update(id: number, dto: UpdateTabelaPrecDto) {
    await this.findOne(id);
    await this.repository.update(id, { ...dto, dataAtualizacao: new Date() });
    return this.findOne(id);
  }

  async delete(id: number) {
    await this.findOne(id);
    await this.repository.delete(id);
  }
}
```

---

## 🔧 PASSO 8: Backend — Controller (3 min)

**Arquivo:** `backend/src/modules/tabela-preco/tabela-preco.controller.ts`

```typescript
import { Controller, Get, Post, Put, Delete, Param, Body, Query, UseGuards, Logger } from '@nestjs/common';
import { JwtAuthGuard } from '../../common/guards/jwt-auth.guard';
import { TabelaPrecService } from './tabela-preco.service';
import { CreateTabelaPrecDto, UpdateTabelaPrecDto, ListaTabelaPrecDto } from './tabela-preco.dto';

@Controller('tabela-preco')
@UseGuards(JwtAuthGuard)
export class TabelaPrecController {
  private readonly logger = new Logger(TabelaPrecController.name);

  constructor(private service: TabelaPrecService) {}

  @Get()
  async findAll(@Query() filter: ListaTabelaPrecDto) {
    this.logger.log('Listando tabelas de preço');
    const result = await this.service.findAll(filter);
    return { success: true, data: result };
  }

  @Get(':id')
  async findOne(@Param('id') id: string) {
    const item = await this.service.findOne(Number(id));
    return { success: true, data: item };
  }

  @Post()
  async create(@Body() dto: CreateTabelaPrecDto) {
    const item = await this.service.create(dto);
    return { success: true, message: 'Criada', data: item };
  }

  @Put(':id')
  async update(@Param('id') id: string, @Body() dto: UpdateTabelaPrecDto) {
    const item = await this.service.update(Number(id), dto);
    return { success: true, message: 'Atualizada', data: item };
  }

  @Delete(':id')
  async delete(@Param('id') id: string) {
    await this.service.delete(Number(id));
    return { success: true, message: 'Deletada' };
  }
}
```

---

## 🔧 PASSO 9: Backend — Module (2 min)

**Arquivo:** `backend/src/modules/tabela-preco/tabela-preco.module.ts`

```typescript
import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { TabelaPrecEntity } from './tabela-preco.entity';
import { TabelaPrecService } from './tabela-preco.service';
import { TabelaPrecController } from './tabela-preco.controller';

@Module({
  imports: [TypeOrmModule.forFeature([TabelaPrecEntity])],
  providers: [TabelaPrecService],
  controllers: [TabelaPrecController]
})
export class TabelaPrecModule {}
```

---

## 🔗 PASSO 10: Registrar Rota Frontend (1 min)

**Arquivo:** `frontend/src/app/app.routes.ts`

```typescript
{
  path: 'catalog/tabela-preco',
  loadComponent: () => import('./pages/catalog/tabela-preco/tabela-preco')
    .then(m => m.TabelaPrecComponent),
  canActivate: [authGuard]
}
```

---

## 🔗 PASSO 11: Registrar Módulo Backend (1 min)

**Arquivo:** `backend/src/app.module.ts`

```typescript
import { TabelaPrecModule } from './modules/tabela-preco/tabela-preco.module';

@Module({
  imports: [
    // ... outros módulos
    TabelaPrecModule
  ]
})
export class AppModule {}
```

---

## 🔗 PASSO 12: Criar Migration (1 min)

```bash
cd backend
npm run typeorm migration:create -- -n CreateTabelaPrecTable
```

**Arquivo gerado:** `backend/src/database/migrations/xxxxx-CreateTabelaPrecTable.ts`

```typescript
import { MigrationInterface, QueryRunner, Table } from 'typeorm';

export class CreateTabelaPrecTable implements MigrationInterface {
  public async up(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.createTable(
      new Table({
        name: 'tabela_preco',
        columns: [
          { name: 'id', type: 'int', isPrimary: true, isGenerated: true },
          { name: 'nome', type: 'varchar', length: '255' },
          { name: 'descricao', type: 'text', isNullable: true },
          { name: 'margemLucro', type: 'decimal', precision: 5, scale: 2 },
          { name: 'vigenciaInicial', type: 'date' },
          { name: 'vigenciaFinal', type: 'date', isNullable: true },
          { name: 'status', type: 'enum', enum: ['A', 'I'], default: "'A'" },
          { name: 'dataCriacao', type: 'timestamp', default: 'CURRENT_TIMESTAMP' },
          { name: 'dataAtualizacao', type: 'timestamp', isNullable: true }
        ]
      }),
      true
    );
  }

  public async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.dropTable('tabela_preco');
  }
}
```

**Executar:**
```bash
npm run typeorm migration:run
```

---

## 🧪 PASSO 13: Testar (10 min)

### Backend
```bash
cd backend
npm start

# Testar com curl
curl -X GET http://localhost:3000/tabela-preco \
  -H "Authorization: Bearer SEU_TOKEN"
```

### Frontend
```bash
cd frontend
ng serve

# Acesse no navegador:
# http://localhost:4200/catalog/tabela-preco
```

### Fluxo de Teste
1. [ ] Listar (vazio inicialmente)
2. [ ] Criar nova tabela
3. [ ] Editar tabela criada
4. [ ] Filtrar por status
5. [ ] Deletar tabela
6. [ ] Verificar sem erros no console (F12)

---

## ✅ Checklist Final

- [ ] Frontend compila sem erros
- [ ] Backend compila sem erros
- [ ] Banco de dados criado (migration executada)
- [ ] Rota registrada em app.routes.ts
- [ ] Módulo registrado em app.module.ts
- [ ] Funcionalidade CRUD testada manualmente
- [ ] Sem console.errors
- [ ] Filtros funcionando
- [ ] Paginação funcionando
- [ ] Permissões de acesso corretas (JWT)

---

## ⏱️ Resumo de Tempo

| Etapa | Tempo |
|-------|-------|
| Frontend Modelo | 2 min |
| Frontend Serviço | 3 min |
| Frontend Componente | 8 min |
| Frontend Template | 5 min |
| Backend Entity | 3 min |
| Backend DTO | 2 min |
| Backend Service | 4 min |
| Backend Controller | 3 min |
| Backend Module | 2 min |
| Registrar Rotas | 2 min |
| Migration & DB | 2 min |
| Teste | 10 min |
| **TOTAL** | **47 min** |

---

## 🎓 Aprendizado

Este exemplo mostrou:
- ✅ Estrutura completa Frontend + Backend
- ✅ Como conectar componente ao serviço
- ✅ Como criar endpoint NestJS
- ✅ Como fazer requisições com autenticação
- ✅ Como paginação e filtros funcionam
- ✅ Como testar funcionamento

**Use como template para próximas features!**

---

**Criado:** Maio 2026  
**Status:** ✅ Pronto para usar
