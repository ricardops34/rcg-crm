import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService,
  PoBreadcrumb,
  PoComboOption,
  PoComboFilterMode 
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
      (p-cancel)="cancel()"
      [p-disable-submit]="isLoading">

      <form #moduleForm="ngForm">
        <po-divider p-label="Configuração do Módulo"></po-divider>
        <div class="po-row">
          <po-input class="po-md-6" name="name" [(ngModel)]="module.name" p-label="Nome do Módulo" p-required p-clean></po-input>
          <po-combo
            class="po-md-3"
            name="icon"
            [ngModel]="module.icon"
            (p-change)="module.icon = $event"
            p-label="Ícone (PO-UI)"
            [p-options]="iconOptions"
            [p-filter-mode]="filterModeContains"
            p-clean>
            <ng-template p-combo-option-template let-option>
              <div style="display: flex; align-items: center; gap: 12px; padding: 6px 0;">
                <span [class]="option.value" style="font-size: 20px; width: 24px; text-align: center; color: var(--color-primary, #0056b3);"></span>
                <span style="font-size: 14px;">{{ option.label }}</span>
              </div>
            </ng-template>
          </po-combo>
          <po-number class="po-md-3" name="order" [(ngModel)]="module.order" p-label="Ordem de Exibição" p-required></po-number>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class ModuleFormComponent implements OnInit {
  readonly filterModeContains = PoComboFilterMode.contains;

  private moduleService = inject(ModuleService);
  private router = inject(Router);
  private activatedRoute = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  module: any = { order: 0 };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Novo Módulo";

  readonly iconOptions: Array<PoComboOption> = [
    { label: 'Casa / Home (an-house)', value: 'an an-house' },
    { label: 'Usuário / User (an-user)', value: 'an an-user' },
    { label: 'Adicionar Usuário (an-user-plus)', value: 'an an-user-plus' },
    { label: 'Engrenagem / Configurações (an-gear-six)', value: 'an an-gear-six' },
    { label: 'Gráfico / Estatísticas (an-chart-line)', value: 'an an-chart-line' },
    { label: 'Área de Gráfico (an-chart-area)', value: 'an an-chart-area' },
    { label: 'Lista / MCV (an-list)', value: 'an an-list' },
    { label: 'Alvo / Objetivos (an-target)', value: 'an an-target' },
    { label: 'Cadeado / Permissões (an-lock)', value: 'an an-lock' },
    { label: 'Menu Lateral (an-sidebar-simple)', value: 'an an-sidebar-simple' },
    { label: 'Sinal de Mais / Novo (an-plus)', value: 'an an-plus' },
    { label: 'Lixeira / Excluir (an-trash)', value: 'an an-trash' },
    { label: 'Prédio / Unidades (an-buildings)', value: 'an an-buildings' },
    { label: 'Camadas / Módulos (an-layers)', value: 'an an-layers' },
    { label: 'Grid / Aplicativos (an-grid-four)', value: 'an an-grid-four' },
    { label: 'Calendário (an-calendar)', value: 'an an-calendar' },
    { label: 'Telefone (an-phone)', value: 'an an-phone' },
    { label: 'Envelope / E-mail (an-envelope)', value: 'an an-envelope' },
    { label: 'Mesa / Dashboard Vendedor (an-desktop)', value: 'an an-desktop' },
    { label: 'Estrela / Destaque (an-star)', value: 'an an-star' }
  ];

  readonly breadcrumb: PoBreadcrumb = {
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
