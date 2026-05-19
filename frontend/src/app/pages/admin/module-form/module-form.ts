import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService 
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ModuleService } from "../../../services/module";

@Component({
  selector: "app-module-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit 
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()">
      
      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <form #moduleForm="ngForm">
        <po-divider p-label="Configuração do Módulo"></po-divider>
        <div class="po-row">
          <po-input class="po-md-6" name="name" [(ngModel)]="module.name" p-label="Nome do Módulo" p-required p-clean></po-input>
          <po-input class="po-md-3" name="icon" [(ngModel)]="module.icon" p-label="Ícone (PO-UI)" p-icon="po-icon-star" p-help="Ex: po-icon-finance"></po-input>
          <po-number class="po-md-3" name="order" [(ngModel)]="module.order" p-label="Ordem de Exibição" p-required></po-number>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class ModuleFormComponent implements OnInit {
  private moduleService = inject(ModuleService);
  private router = inject(Router);
  private activatedRoute = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  module: any = { order: 0 };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Novo Módulo";

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Módulos", link: "/admin/modules" },
      { label: "Manutenção" }
    ]
  };

  ngOnInit() {
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Módulo";
      this.loadModule(id);
    }
  }

  loadModule(id: number) {
    this.isLoading = true;
    this.moduleService.findOne(id).subscribe({
      next: (res) => {
        this.module = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar módulo.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.moduleService.save(this.module).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Módulo salvo com sucesso!");
        this.router.navigate(["/admin/modules"]);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar módulo.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/modules"]);
  }
}
