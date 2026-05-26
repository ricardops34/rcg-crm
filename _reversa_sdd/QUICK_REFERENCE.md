# 📋 Quick Reference Guide — rcgCRM

**Data:** Maio 2026  
**Propósito:** Referência rápida para implementação. Copie & adapte.

---

## 🚀 Início Rápido: Nova Feature CRUD em 30 min

```bash
# 1. FRONTEND: Criar pasta e arquivos
mkdir frontend/src/app/pages/meu-recurso
touch frontend/src/app/pages/meu-recurso/meu-recurso.ts
touch frontend/src/app/pages/meu-recurso/meu-recurso.html
touch frontend/src/app/pages/meu-recurso/meu-recurso.css
touch frontend/src/app/services/meu-recurso.ts
touch frontend/src/app/services/models/meu-recurso.model.ts

# 2. BACKEND: Criar pasta e arquivos
mkdir backend/src/modules/meu-recurso
touch backend/src/modules/meu-recurso/meu-recurso.{entity,dto,service,controller,module}.ts
```

---

## 📝 Templates Minificados (Copie & Cole)

### Frontend Service (5 min)
```typescript
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({ providedIn: 'root' })
export class MeuRecursoService {
  private API_URL = `${environment.apiUrl}/meu-recurso`;

  constructor(private http: HttpClient) {}

  private getHeaders() {
    const token = localStorage.getItem('token');
    return new HttpHeaders({ 'Authorization': `Bearer ${token}` });
  }

  findAll(page: number = 1, limit: number = 10): Observable<any> {
    const params = new HttpParams().set('page', page).set('limit', limit);
    return this.http.get<any>(this.API_URL, { headers: this.getHeaders(), params });
  }

  findOne(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  save(data: any): Observable<any> {
    return data.id
      ? this.http.put<any>(`${this.API_URL}/${data.id}`, data, { headers: this.getHeaders() })
      : this.http.post<any>(this.API_URL, data, { headers: this.getHeaders() });
  }

  delete(id: number): Observable<any> {
    return this.http.delete(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
```

### Frontend Component (10 min)
```typescript
import { Component, OnInit, OnDestroy, ViewChild, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { PoModule, PoPageAction, PoTableColumn, PoTableAction, PoModalComponent, PoNotificationService } from '@po-ui/ng-components';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { MeuRecursoService } from '../../../services/meu-recurso';

@Component({
  selector: 'app-meu-recurso',
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: './meu-recurso.html'
})
export class MeuRecursoComponent implements OnInit, OnDestroy {
  private service = inject(MeuRecursoService);
  private notification = inject(PoNotificationService);
  private destroy$ = new Subject<void>();

  items: any[] = [];
  selectedItem: any = {};
  isLoading = false;

  readonly columns: PoTableColumn[] = [
    { property: 'id', label: 'ID', width: '80px' },
    { property: 'nome', label: 'Nome', width: '300px' }
  ];

  readonly tableActions: PoTableAction[] = [
    { label: 'Editar', action: (item: any) => this.edit(item) },
    { label: 'Deletar', action: (item: any) => this.delete(item) }
  ];

  readonly pageActions: PoPageAction[] = [
    { label: 'Novo', action: this.new.bind(this) },
    { label: 'Atualizar', action: this.reload.bind(this) }
  ];

  @ViewChild('modal') modal!: PoModalComponent;

  ngOnInit() { this.loadData(); }
  ngOnDestroy() { this.destroy$.next(); this.destroy$.complete(); }

  loadData() {
    this.isLoading = true;
    this.service.findAll().pipe(takeUntil(this.destroy$)).subscribe({
      next: (res) => { this.items = res.items; this.isLoading = false; },
      error: () => { this.notification.error('Erro ao carregar'); this.isLoading = false; }
    });
  }

  new() { this.selectedItem = {}; this.modal.open(); }

  edit(item: any) {
    this.service.findOne(item.id).pipe(takeUntil(this.destroy$)).subscribe(
      data => { this.selectedItem = data; this.modal.open(); }
    );
  }

  save() {
    this.service.save(this.selectedItem).pipe(takeUntil(this.destroy$)).subscribe({
      next: () => { this.notification.success('Salvo!'); this.modal.close(); this.loadData(); },
      error: () => this.notification.error('Erro ao salvar')
    });
  }

  delete(item: any) {
    if (!confirm('Confirmar?')) return;
    this.service.delete(item.id).pipe(takeUntil(this.destroy$)).subscribe({
      next: () => { this.notification.success('Deletado!'); this.loadData(); },
      error: () => this.notification.error('Erro ao deletar')
    });
  }

  reload() { this.loadData(); }
}
```

### Frontend Template (5 min)
```html
<po-page [p-title]="'Meu Recurso'" [p-actions]="pageActions" [p-loading]="isLoading">
  <po-table [p-columns]="columns" [p-items]="items" [p-actions]="tableActions" [p-loading]="isLoading"></po-table>
  
  <po-modal #modal p-title="Editar" p-primary-action="Salvar" (p-primary-action)="save()">
    <po-input [(ngModel)]="selectedItem.nome" p-label="Nome" p-required="true"></po-input>
  </po-modal>
</po-page>
```

### Backend Entity (3 min)
```typescript
import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from 'typeorm';

@Entity('meu_recurso')
export class MeuRecursoEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ type: 'varchar', length: 255 })
  nome: string;

  @Column({ type: 'enum', enum: ['A', 'I'], default: 'A' })
  status: 'A' | 'I';

  @CreateDateColumn()
  dataCriacao: Date;
}
```

### Backend Service (5 min)
```typescript
import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { MeuRecursoEntity } from './meu-recurso.entity';

@Injectable()
export class MeuRecursoService {
  constructor(@InjectRepository(MeuRecursoEntity) private repo: Repository<MeuRecursoEntity>) {}

  async findAll(page: number = 1, limit: number = 10) {
    const skip = (page - 1) * limit;
    const [items, total] = await this.repo.findAndCount({ skip, take: limit });
    return { items, total, page, limit, totalPages: Math.ceil(total / limit) };
  }

  async findOne(id: number) {
    const item = await this.repo.findOne({ where: { id } });
    if (!item) throw new NotFoundException('Não encontrado');
    return item;
  }

  async create(dto: any) {
    return this.repo.save({ ...dto, dataCriacao: new Date() });
  }

  async update(id: number, dto: any) {
    await this.findOne(id);
    await this.repo.update(id, dto);
    return this.findOne(id);
  }

  async delete(id: number) {
    await this.findOne(id);
    await this.repo.delete(id);
  }
}
```

### Backend Controller (5 min)
```typescript
import { Controller, Get, Post, Put, Delete, Param, Body, Query, UseGuards } from '@nestjs/common';
import { JwtAuthGuard } from '../../common/guards/jwt-auth.guard';
import { MeuRecursoService } from './meu-recurso.service';

@Controller('meu-recurso')
@UseGuards(JwtAuthGuard)
export class MeuRecursoController {
  constructor(private service: MeuRecursoService) {}

  @Get()
  async findAll(@Query('page') page: number = 1, @Query('limit') limit: number = 10) {
    return this.service.findAll(page, limit);
  }

  @Get(':id')
  async findOne(@Param('id') id: string) {
    return this.service.findOne(Number(id));
  }

  @Post()
  async create(@Body() dto: any) {
    return this.service.create(dto);
  }

  @Put(':id')
  async update(@Param('id') id: string, @Body() dto: any) {
    return this.service.update(Number(id), dto);
  }

  @Delete(':id')
  async delete(@Param('id') id: string) {
    await this.service.delete(Number(id));
    return { success: true };
  }
}
```

### Backend Module (2 min)
```typescript
import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { MeuRecursoEntity } from './meu-recurso.entity';
import { MeuRecursoService } from './meu-recurso.service';
import { MeuRecursoController } from './meu-recurso.controller';

@Module({
  imports: [TypeOrmModule.forFeature([MeuRecursoEntity])],
  providers: [MeuRecursoService],
  controllers: [MeuRecursoController]
})
export class MeuRecursoModule {}
```

---

## 🔧 Configurações Essenciais

### Registrar Rota (Frontend)
```typescript
// app.routes.ts
{
  path: 'meu-recurso',
  loadComponent: () => import('./pages/meu-recurso/meu-recurso').then(m => m.MeuRecursoComponent),
  canActivate: [authGuard]
}
```

### Registrar Módulo (Backend)
```typescript
// app.module.ts
import { MeuRecursoModule } from './modules/meu-recurso/meu-recurso.module';

@Module({
  imports: [MeuRecursoModule, /* outros */]
})
export class AppModule {}
```

---

## 🐛 Erros Comuns & Fix Rápido

| Erro | Fix |
|------|-----|
| `Cannot find module '@po-ui/ng-components'` | `npm install @po-ui/ng-components` |
| `TypeError: Cannot read property 'subscribe'` | Verifique se serviço retorna Observable |
| `401 Unauthorized` | Adicione Bearer token ao header |
| `Module not found` | Registre em app.module.ts (backend) |
| `@ViewChild undefined` | Use `{ static: true }` ou aguarde ngAfterViewInit |
| `Entity not saved` | Faça `return await repository.save(entity)` |
| `CORS error` | Adicione `app.enableCors()` em main.ts |
| `Query timeout` | Adicione índice `@Index` na entity |
| `Form invalid` | Adicione Validators: `Validators.required` |

---

## ✅ Checklist de Deploy

```bash
# Frontend
ng lint                          # Sem warnings
ng build --prod                  # Build sem erros
npm test                         # Testes passam

# Backend
npm run build                    # Compila sem erro
npm test                         # Testes passam
npm run typeorm migration:run    # Migrations aplicadas

# Integração
curl -H "Authorization: Bearer $TOKEN" http://localhost:3000/meu-recurso
# Deve retornar: { "items": [...], "total": X, ... }
```

---

## 📱 Keyboard Shortcuts

| Atalho | Ação |
|--------|------|
| `ng serve` | Inicia dev frontend |
| `npm start` | Inicia dev backend |
| `F12` | Abre DevTools (debug) |
| `Ctrl+Shift+I` | Inspeciona elemento |
| `Ctrl+K Ctrl+C` | Comenta linha (VS Code) |

---

## 🔐 Variáveis de Ambiente

### Frontend (.env)
```env
ANGULAR_APP_API_URL=http://localhost:3000
```

### Backend (.env)
```env
DB_HOST=localhost
DB_PORT=5432
DB_USERNAME=postgres
DB_PASSWORD=postgres
DB_DATABASE=rcgcrm
JWT_SECRET=sua-chave-secreta
JWT_EXPIRATION=24h
NODE_ENV=development
```

---

## 📞 Comandos Úteis

```bash
# Docker (Database)
docker-compose up -d              # Inicia DB
docker-compose down               # Para DB
docker logs db_container          # Ver logs

# Backend
npm run typeorm migration:create -- -n CreateTable
npm run typeorm migration:run
npm test -- --coverage

# Frontend
ng generate component pages/novo
ng generate service services/novo
ng lint --fix                     # Fix automático

# Git
git add .
git commit -m "feat: novo recurso"
git push origin develop
```

---

## 🎯 Performance Tips

1. **Paginação:** Sempre limite a 10-50 itens por página
2. **Índices:** Adicione `@Index` em campos de filtro (email, status, etc)
3. **Lazy Loading:** Use `loadComponent` em rotas
4. **Unsubscribe:** Sempre use `takeUntil(this.destroy$)`
5. **OnPush:** Use `ChangeDetectionStrategy.OnPush` para componentes large
6. **Debounce:** Adicione `debounceTime(300)` em buscas
7. **Cache:** Use `shareReplay()` para queries que repetem

---

## 📚 Links Rápidos

- [Angular Docs](https://angular.io)
- [NestJS Docs](https://docs.nestjs.com)
- [TypeORM Docs](https://typeorm.io)
- [PO-UI Components](https://po-ui.io/ng/components)
- [RxJS Operators](https://rxjs.dev/guide/operators)

---

**Bookmark este arquivo! 📌**

---

**Gerado:** Maio 2026  
**Tamanho:** ~8KB  
**Status:** ✅ Pronto para usar offline
