import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { 
  PoModule, 
  PoPageAction, 
  PoTableColumn, 
  PoTableAction, 
  PoPageFilter, 
  PoNotificationService,
  PoBreadcrumb 
} from "@po-ui/ng-components";
import { ModuleService } from "../../../services/module";

@Component({
  selector: "app-module-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Módulos do Sistema"
      p-subtitle="Gestão de agrupadores e ícones do menu lateral"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="modules"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>
      
    </po-page-list>
  `
})
export class ModuleListComponent implements OnInit {
  private moduleService = inject(ModuleService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  modules: Array<any> = [];
  isLoading: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Segurança", link: "/admin/users" },
      { label: "Módulos" }
    ]
  };

  readonly filter: PoPageFilter = {
    action: this.loadModules.bind(this),
    placeholder: "Pesquisar módulo"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Módulo", action: () => this.router.navigate(["/admin/modules/new"]), icon: "po-icon-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/modules/edit/${row.id}`]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteModule.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "order", label: "Ordem", width: "80px" },
    { property: "icon", label: "Ícone", type: "icon", width: "80px" },
    { property: "name", label: "Nome do Módulo" }
  ];

  ngOnInit() {
    this.loadModules();
  }

  loadModules(filter?: string) {
    this.isLoading = true;
    this.moduleService.findAll().subscribe({
      next: (res) => {
        this.modules = res;
        if (filter) {
          this.modules = this.modules.filter(m => 
            m.name?.toLowerCase().includes(filter.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  deleteModule(module: any) {
    if (confirm(`Deseja realmente excluir o módulo ${module.name}?`)) {
      this.isLoading = true;
      this.moduleService.delete(module.id).subscribe({
        next: () => {
          this.poNotification.success("Módulo excluído com sucesso!");
          this.loadModules();
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir módulo.");
        }
      });
    }
  }
}
