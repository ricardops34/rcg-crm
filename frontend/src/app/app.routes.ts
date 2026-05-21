import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login';
import { ClienteListComponent } from './pages/commercial/cliente-list/cliente-list';
import { ClienteFormComponent } from './pages/commercial/cliente-form/cliente-form';
import { Cliente360Component } from './pages/commercial/cliente-360/cliente-360';
import { DashboardComponent } from './pages/analytics/dashboard/dashboard';
import { MvcListComponent } from './pages/commercial/mvc-list/mvc-list';
import { VendedorListComponent } from './pages/commercial/vendedor-list/vendedor-list';
import { VendedorFormComponent } from './pages/commercial/vendedor-form/vendedor-form';
import { MetaListComponent } from './pages/commercial/meta-list/meta-list';
import { MetaFormComponent } from './pages/commercial/meta-form/meta-form';
import { UserListComponent } from './pages/admin/user-list/user-list';
import { UserFormComponent } from './pages/admin/user-form/user-form';
import { TermsFormComponent } from './pages/admin/terms-form/terms-form';
import { ProfileComponent } from './pages/admin/profile/profile';
import { GroupListComponent } from './pages/admin/group-list/group-list';
import { GroupFormComponent } from './pages/admin/group-form/group-form';
import { ProgramListComponent } from './pages/admin/program-list/program-list';
import { ProgramFormComponent } from './pages/admin/program-form/program-form';
import { UnitListComponent } from './pages/admin/unit-list/unit-list';
import { UnitFormComponent } from './pages/admin/unit-form/unit-form';
import { ModuleListComponent } from './pages/admin/module-list/module-list';
import { ModuleFormComponent } from './pages/admin/module-form/module-form';
import { authGuard } from './guards/auth-guard';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { 
    path: '', 
    canActivate: [authGuard], 
    children: [
      { path: 'dashboard', component: DashboardComponent, data: { controller: 'DashboardVendedor' } },
      { path: 'clientes', component: ClienteListComponent, data: { controller: 'ClienteList' } },
      { path: 'clientes/360/:id', component: Cliente360Component, data: { controller: 'PosisaoClienteFormView' } },
      { path: 'clientes/new', component: ClienteFormComponent, data: { controller: 'ClienteList' } },
      { path: 'clientes/edit/:id', component: ClienteFormComponent, data: { controller: 'ClienteList' } },
      { path: 'vendedores', component: VendedorListComponent, data: { controller: 'VendedorList' } },
      { path: 'vendedores/new', component: VendedorFormComponent, data: { controller: 'VendedorList' } },
      { path: 'vendedores/edit/:id', component: VendedorFormComponent, data: { controller: 'VendedorList' } },
      { path: 'metas', component: MetaListComponent, data: { controller: 'MetaList' } },
      { path: 'metas/new', component: MetaFormComponent, data: { controller: 'MetaList' } },
      { path: 'metas/edit/:id', component: MetaFormComponent, data: { controller: 'MetaList' } },
      { path: 'mvc', component: MvcListComponent, data: { controller: 'MvcList' } },
      { path: 'admin/users', component: UserListComponent, data: { controller: 'SystemUserList' } },
      { path: 'admin/users/new', component: UserFormComponent, data: { controller: 'SystemUserList' } },
      { path: 'admin/users/edit/:id', component: UserFormComponent, data: { controller: 'SystemUserList' } },
      { path: 'admin/users/terms', component: TermsFormComponent, data: { controller: 'SystemUserList' } },
      { path: 'admin/groups', component: GroupListComponent, data: { controller: 'SystemGroupList' } },
      { path: 'admin/groups/new', component: GroupFormComponent, data: { controller: 'SystemGroupList' } },
      { path: 'admin/groups/edit/:id', component: GroupFormComponent, data: { controller: 'SystemGroupList' } },
      { path: 'admin/units', component: UnitListComponent, data: { controller: 'SystemUnitList' } },
      { path: 'admin/units/new', component: UnitFormComponent, data: { controller: 'SystemUnitList' } },
      { path: 'admin/units/edit/:id', component: UnitFormComponent, data: { controller: 'SystemUnitList' } },
      { path: 'admin/modules', component: ModuleListComponent, data: { controller: 'SystemModuleList' } },
      { path: 'admin/modules/new', component: ModuleFormComponent, data: { controller: 'SystemModuleList' } },
      { path: 'admin/modules/edit/:id', component: ModuleFormComponent, data: { controller: 'SystemModuleList' } },
      { path: 'admin/programs', component: ProgramListComponent, data: { controller: 'SystemProgramList' } },
      { path: 'admin/programs/new', component: ProgramFormComponent, data: { controller: 'SystemProgramList' } },
      { path: 'admin/programs/edit/:id', component: ProgramFormComponent, data: { controller: 'SystemProgramList' } },
      { path: 'profile', component: ProfileComponent },
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' }
    ] 
  },
  { path: '**', redirectTo: '' }
];
