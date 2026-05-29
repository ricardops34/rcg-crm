import { Component, OnInit, inject, ViewChild } from "@angular/core";
import { CommonModule } from "@angular/common";
import { forkJoin } from "rxjs";
import { Router } from "@angular/router";
import {
  PoModule,
  PoNotificationService,
  PoTableColumn,
  PoTableAction,
  PoSelectOption,
  PoBreadcrumb,
  PoModalComponent,
  PoDialogService,
  PoComboOption,
  PoComboFilterMode
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ProgramService } from "../../../services/program";
import { ModuleService } from "../../../services/module";
import { RoutesLookupService } from "../../../services/routes-lookup.service";
import { RoutesRegistryService } from "../../../services/routes-registry.service";

interface ProgramEdit {
  id: number;
  name: string;
  controller: string;
  icon: string;
  order: number;
  systemModuleId: number | null;
}

interface ModuleGroup {
  id: number;
  name: string;
  icon: string;
  programs: any[];
}

@Component({
  selector: "app-menu-editor",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-default
      p-title="Manutenção de Menus"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="pageActions">

      <div class="po-row" style="margin-bottom: 16px;">
        <po-select
          class="po-md-4"
          name="filterModule"
          p-label="Filtrar por Módulo"
          [p-options]="moduleFilterOptions"
          [ngModel]="selectedModuleId"
          (p-change)="onModuleFilter($event)"
          p-clean>
        </po-select>
        <po-input
          class="po-md-8"
          name="filterText"
          p-label="Pesquisar por nome ou controller"
          [ngModel]="filterText"
          (p-change-model)="onTextFilter($event)"
          p-clean>
        </po-input>
      </div>

      @if (isLoading) {
        <po-loading-overlay></po-loading-overlay>
      }

      <po-accordion>
        @for (mod of filteredModules; track mod.id) {
          <po-accordion-item [p-label]="mod.name + ' (' + mod.programs.length + ' rotinas)'">
            <po-table
              [p-columns]="columns"
              [p-items]="mod.programs"
              [p-actions]="tableActions"
              p-container="shadow"
              [p-striped]="true"
              [p-sort]="true">
            </po-table>
          </po-accordion-item>
        }
        @if (orphanPrograms.length > 0) {
          <po-accordion-item
            p-label="— Sem módulo ({{ orphanPrograms.length }} rotinas sem menu) —">
            <po-table
              [p-columns]="columns"
              [p-items]="orphanPrograms"
              [p-actions]="orphanTableActions"
              p-container="shadow"
              [p-striped]="true">
            </po-table>
          </po-accordion-item>
        }
      </po-accordion>

      @if (filteredModules.length === 0 && !isLoading) {
        <po-info
          p-label="Nenhuma rotina encontrada"
          p-value="Tente alterar os filtros de busca ou verifique se há rotinas cadastradas.">
        </po-info>
      }

    </po-page-default>

    <!-- Modal de Edição -->
    <po-modal
      #editModal
      p-title="Editar Posição no Menu"
      [p-primary-action]="primaryAction"
      [p-secondary-action]="secondaryAction">

      @if (editingProgram) {
        <form #editForm="ngForm">
          <po-divider p-label="Informações da Rotina"></po-divider>
          <div class="po-row">
            <po-input
              class="po-md-6"
              name="editName"
              [(ngModel)]="editingProgram.name"
              p-label="Nome exibido no Menu"
              p-required
              p-clean>
            </po-input>
            <po-lookup
              class="po-md-6"
              name="editController"
              [p-filter-service]="routesLookupService"
              p-label="Rotina do Sistema (Controller)"
              p-field-label="label"
              p-field-value="value"
              [ngModel]="editingProgram.controller"
              (p-change)="onRouteSelected($event)"
              [p-required]="true"
              p-clean>
            </po-lookup>
          </div>

          <po-divider p-label="Posição no Menu"></po-divider>
          <div class="po-row">
            <po-select
              class="po-md-6"
              name="editModule"
              [ngModel]="editingProgram.systemModuleId"
              (ngModelChange)="editingProgram.systemModuleId = $event"
              p-label="Módulo"
              [p-options]="moduleOptions">
            </po-select>
            <po-number
              class="po-md-2"
              name="editOrder"
              [(ngModel)]="editingProgram.order"
              p-label="Ordem"
              [p-min]="1">
            </po-number>
            <po-combo
              class="po-md-4"
              name="editIcon"
              [ngModel]="editingProgram.icon"
              (p-change)="editingProgram.icon = $event"
              p-label="Ícone (PO-UI / Animalia)"
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
          </div>

          @if (!editingProgram.systemModuleId) {
            <div class="po-row">
              <div class="po-md-12">
                <po-info
                  p-label="Atenção"
                  p-value="Rotinas sem módulo não aparecem no menu do sistema.">
                </po-info>
              </div>
            </div>
          }
        </form>
      }
    </po-modal>
  `
})
export class MenuEditorComponent implements OnInit {
  @ViewChild("editModal") editModal!: PoModalComponent;

  readonly filterModeContains = PoComboFilterMode.contains;

  private programService = inject(ProgramService);
  private moduleService = inject(ModuleService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);
  public routesLookupService = inject(RoutesLookupService);
  private routesRegistry = inject(RoutesRegistryService);

  allModules: ModuleGroup[] = [];
  filteredModules: ModuleGroup[] = [];
  orphanPrograms: any[] = [];
  allPrograms: any[] = [];

  moduleOptions: Array<PoSelectOption> = [];
  moduleFilterOptions: Array<PoSelectOption> = [];
  selectedModuleId: string = "";
  filterText: string = "";

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

  isLoading = false;
  isSaving = false;
  editingProgram: ProgramEdit | null = null;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Administração", link: "/admin/modules" },
      { label: "Manutenção de Menus" }
    ]
  };

  readonly pageActions = [
    {
      label: "Gerenciar Rotinas",
      action: () => this.router.navigate(["/admin/programs"]),
      icon: "an an-list"
    },
    {
      label: "Gerenciar Módulos",
      action: () => this.router.navigate(["/admin/modules"]),
      icon: "an an-gear-six"
    }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "order", label: "Ordem", width: "80px", type: "number" },
    { property: "name", label: "Nome no Menu" },
    { property: "controller", label: "Controller", width: "200px" },
    { property: "icon", label: "Ícone", width: "150px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.openEdit(row), icon: "an an-pencil-simple" },
    {
      label: "Retirar do menu",
      action: (row: any) => this.detachFromMenu(row),
      icon: "an an-minus",
      type: "danger"
    }
  ];

  readonly orphanTableActions: Array<PoTableAction> = [
    { label: "Adicionar ao menu", action: (row: any) => this.attachToMenu(row), icon: "an an-plus" },
    { label: "Editar", action: (row: any) => this.openEdit(row), icon: "an an-pencil-simple" }
  ];

  primaryAction = {
    label: "Salvar",
    action: () => this.saveEdit(),
    loading: false
  };

  secondaryAction = {
    label: "Cancelar",
    action: () => this.editModal.close()
  };

  ngOnInit() {
    this.loadData();
  }

  loadData() {
    this.isLoading = true;
    
    forkJoin({
      modules: this.moduleService.findAll(),
      programs: this.programService.findAll()
    }).subscribe({
      next: ({ modules, programs }) => {
        const mods: any[] = modules ?? [];
        const progs: any[] = programs ?? [];

        this.allPrograms = progs;

        this.moduleOptions = [
          { label: "— Sem módulo (não aparece no menu) —", value: null as any },
          ...mods.map(m => ({ label: m.name, value: m.id }))
        ];

        this.moduleFilterOptions = [
          { label: "Todos os Módulos", value: "" },
          ...mods.map(m => ({ label: m.name, value: String(m.id) }))
        ];

        this.allModules = mods.map(m => ({
          id: m.id,
          name: m.name,
          icon: m.icon || "an an-dots-three",
          programs: progs
            .filter(p => p.systemModuleId === m.id)
            .sort((a, b) => (a.order ?? 0) - (b.order ?? 0))
        }));

        this.orphanPrograms = progs
          .filter(p => !p.systemModuleId)
          .sort((a, b) => a.name.localeCompare(b.name));

        this.applyFilter();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar dados do menu.");
      }
    });
  }

  onModuleFilter(value: string) {
    this.selectedModuleId = value;
    this.applyFilter();
  }

  onTextFilter(value: string) {
    this.filterText = value;
    this.applyFilter();
  }

  applyFilter() {
    let mods = [...this.allModules];

    if (this.selectedModuleId) {
      mods = mods.filter(m => String(m.id) === this.selectedModuleId);
    }

    if (this.filterText?.trim()) {
      const q = this.filterText.toLowerCase();
      mods = mods.map(m => ({
        ...m,
        programs: m.programs.filter(p =>
          p.name?.toLowerCase().includes(q) ||
          p.controller?.toLowerCase().includes(q)
        )
      })).filter(m => m.programs.length > 0);
    }

    this.filteredModules = mods;
  }

  openEdit(program: any) {
    this.editingProgram = {
      id: program.id,
      name: program.name ?? "",
      controller: program.controller ?? "",
      icon: program.icon ?? "",
      order: program.order ?? 1,
      systemModuleId: program.systemModuleId ?? null
    };
    this.editModal.open();
  }

  attachToMenu(program: any) {
    this.editingProgram = {
      id: program.id,
      name: program.name ?? "",
      controller: program.controller ?? "",
      icon: program.icon ?? "",
      order: program.order ?? 1,
      systemModuleId: program.systemModuleId ?? null
    };
    this.editModal.open();
  }

  detachFromMenu(program: any) {
    this.poDialog.confirm({
      title: "Retirar rotina do menu",
      message: `Deseja retirar a rotina <strong>${program.name}</strong> do menu? Ela continuará cadastrada, mas ficará sem módulo e não aparecerá na navegação.`,
      confirm: () => {
        this.programService.save({
          id: program.id,
          name: program.name,
          controller: program.controller,
          icon: program.icon ?? "",
          order: program.order ?? 1,
          systemModuleId: null
        }).subscribe({
          next: () => {
            this.poNotification.success("Rotina retirada do menu com sucesso!");
            this.loadData();
          },
          error: () => {
            this.poNotification.error("Erro ao retirar rotina do menu.");
          }
        });
      }
    });
  }

  saveEdit() {
    if (!this.editingProgram) return;
    if (!this.editingProgram.name || !this.editingProgram.controller) {
      this.poNotification.warning("Nome e controller são obrigatórios.");
      return;
    }

    this.primaryAction.loading = true;
    this.programService.save(this.editingProgram).subscribe({
      next: () => {
        this.primaryAction.loading = false;
        this.poNotification.success("Rotina atualizada com sucesso!");
        this.editModal.close();
        this.loadData();
      },
      error: () => {
        this.primaryAction.loading = false;
        this.poNotification.error("Erro ao salvar rotina.");
      }
    });
  }

  onRouteSelected(value: string) {
    if (this.editingProgram) {
      this.editingProgram.controller = value;
      if (value) {
        const route = this.routesRegistry.getRouteByController(value);
        if (route) {
          this.editingProgram.name = route.label;
          this.editingProgram.icon = route.icon;
        }
      }
    }
  }
}
