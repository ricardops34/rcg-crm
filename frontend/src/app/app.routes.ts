import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login';
import { ClienteListComponent } from './pages/commercial/cliente-list/cliente-list';
import { ClienteFormComponent } from './pages/commercial/cliente-form/cliente-form';
import { DashboardComponent } from './pages/analytics/dashboard/dashboard';
import { MvcListComponent } from './pages/commercial/mvc-list/mvc-list';
import { VendedorListComponent } from './pages/commercial/vendedor-list/vendedor-list';
import { VendedorFormComponent } from './pages/commercial/vendedor-form/vendedor-form';
import { MetaListComponent } from './pages/commercial/meta-list/meta-list';
import { MetaFormComponent } from './pages/commercial/meta-form/meta-form';
import { authGuard } from './guards/auth-guard';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { 
    path: '', 
    canActivate: [authGuard], 
    children: [
      { path: 'dashboard', component: DashboardComponent },
      { path: 'clientes', component: ClienteListComponent },
      { path: 'clientes/new', component: ClienteFormComponent },
      { path: 'clientes/edit/:id', component: ClienteFormComponent },
      { path: 'vendedores', component: VendedorListComponent },
      { path: 'vendedores/new', component: VendedorFormComponent },
      { path: 'vendedores/edit/:id', component: VendedorFormComponent },
      { path: 'metas', component: MetaListComponent },
      { path: 'metas/new', component: MetaFormComponent },
      { path: 'metas/edit/:id', component: MetaFormComponent },
      { path: 'mvc', component: MvcListComponent },
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' }
    ] 
  },
  { path: '**', redirectTo: '' }
];
