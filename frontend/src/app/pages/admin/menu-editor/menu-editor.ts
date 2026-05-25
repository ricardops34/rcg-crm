import { Component, OnInit, inject, ViewChild } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoNotificationService,
  PoTableColumn,
  PoTableAction,
  PoSelectOption,
  PoBreadcrumb,
  PoModalComponent,
  PoDialogService
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ProgramService } from "../../../services/program";
import { ModuleService } from "../../../services/module";

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
          <po-accordion-item [p-label]="mod.name + ' (' + mod.programs.length + ' rotinas)'" [p-icon]="mod.icon || 'po-icon-more'">
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
            p-label="— Sem módulo ({{ orphanPrograms.length }} rotinas sem menu) —"
            p-icon="po-icon-warning">
            <po-table
              [p-columns]="columns"
              [p-items]="orphanPrograms"
              [p-actions]="tableActions"
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
            <po-input
              class="po-md-6"
              name="editController"
              [(ngModel)]="editingProgram.controller"
              p-label="Controller"
              p-required
              p-clean>
            </po-input>
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
            <po-input
              class="po-md-4"
              name="editIcon"
              [(ngModel)]="editingProgram.icon"
              p-label="Ícone (ex: an-chart-bar)"
              p-icon="po-icon-star">
            </po-input>
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

  private programService = inject(ProgramService);
  private moduleService = inject(ModuleService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);

  allModules: ModuleGroup[] = [];
  filteredModules: ModuleGroup[] = [];
  orphanPrograms: any[] = [];
  allPrograms: any[] = [];

  moduleOptions: Array<PoSelectOption> = [];
  moduleFilterOptions: Array<PoSelectOption> = [];
  selectedModuleId: string = "";
  filterText: string = "";

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
      icon: "po-icon-list"
    },
    {
      label: "Gerenciar Módulos",
      action: () => this.router.navigate(["/admin/modules"]),
      icon: "po-icon-settings"
    }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "order", label: "Ordem", width: "80px", type: "number" },
    { property: "name", label: "Nome no Menu" },
    { property: "controller", label: "Controller", width: "200px" },
    { property: "icon", label: "Ícone", width: "150px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.openEdit(row), icon: "po-icon-edit" }
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
    Promise.all([
      this.moduleService.findAll().toPromise(),
      this.programService.findAll().toPromise()
    ]).then(([modules, programs]) => {
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
        icon: m.icon || "po-icon-more",
        programs: progs
          .filter(p => p.systemModuleId === m.id)
          .sort((a, b) => (a.order ?? 0) - (b.order ?? 0))
      }));

      this.orphanPrograms = progs
        .filter(p => !p.systemModuleId)
        .sort((a, b) => a.name.localeCompare(b.name));

      this.applyFilter();
      this.isLoading = false;
    }).catch(() => {
      this.isLoading = false;
      this.poNotification.error("Erro ao carregar dados do menu.");
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
}
