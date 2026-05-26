import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoPageAction,
  PoTableColumn,
  PoTableAction,
  PoNotificationService,
  PoBreadcrumb,
  PoDialogService,
  PoSelectOption
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ProgramService } from "../../../services/program";
import { ModuleService } from "../../../services/module";

@Component({
  selector: "app-program-list",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-list
      p-title="Rotinas do Sistema"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions">

      <div class="po-row" style="margin-bottom: 16px;">
        <po-select
          class="po-md-4"
          name="filterModule"
          p-label="Filtrar por MÃ³dulo"
          [p-options]="moduleFilterOptions"
          [ngModel]="selectedModule"
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

      <po-table
        [p-columns]="columns"
        [p-items]="filteredPrograms"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        [p-loading-show-more]="loadingShowMore"
        [p-show-more-disabled]="!hasNext"
        (p-show-more)="showMore()"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>

    </po-page-list>
  `
})
export class ProgramListComponent implements OnInit {
  private programService = inject(ProgramService);
  private moduleService = inject(ModuleService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;

  programs: Array<any> = [];
  filteredPrograms: Array<any> = [];
  moduleFilterOptions: Array<PoSelectOption> = [{ label: "Todos os MÃ³dulos", value: "" }];
  selectedModule: string = "";
  filterText: string = "";
  isLoading = false;
  loadingShowMore = false;
  hasNext = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "AdministraÃ§Ã£o", link: "/admin/users" },
      { label: "Rotinas" }
    ]
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Nova Rotina", action: () => this.router.navigate(["/admin/programs/new"]), icon: "po-icon-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/programs/edit/${row.id}`]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.confirmDelete.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "70px", type: "number" },
    { property: "name", label: "Nome da Rotina" },
    { property: "controller", label: "Controller" },
    { property: "moduleName", label: "MÃ³dulo", width: "160px" },
    { property: "icon", label: "Ãcone", width: "120px" }
  ];

  ngOnInit() {
    this.loadModules();
    this.loadPrograms();
  }

  loadModules() {
    this.moduleService.findAll().subscribe({
      next: (modules: any[]) => {
        const opts = modules.map(m => ({ label: m.name, value: String(m.id) }));
        this.moduleFilterOptions = [{ label: "Todos os MÃ³dulos", value: "" }, ...opts];
      }
    });
  }

  loadPrograms() {
    this.isLoading = true;
    this.paginaAtual = 1;
    this.programService.findAll().subscribe({
      next: (res: any[]) => {
        this.programs = res.map(p => ({
          ...p,
          moduleName: p.systemModule?.name ?? "â€” sem mÃ³dulo â€”"
        }));
        this.applyFilter();
        this.isLoading = false;
      },
      error: () => { this.isLoading = false; }
    });
  }

  onModuleFilter(value: string) {
    this.selectedModule = value;
    this.paginaAtual = 1;
    this.applyFilter();
  }

  onTextFilter(value: string) {
    this.filterText = value;
    this.paginaAtual = 1;
    this.applyFilter();
  }

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.loadingShowMore = true;
    this.paginaAtual += 1;
    this.applyFilter();
    this.loadingShowMore = false;
  }

  applyFilter() {
    let result = [...this.programs];
    if (this.selectedModule) {
      result = result.filter(p => String(p.systemModuleId) === this.selectedModule);
    }
    if (this.filterText?.trim()) {
      const q = this.filterText.toLowerCase();
      result = result.filter(p =>
        p.name?.toLowerCase().includes(q) ||
        p.controller?.toLowerCase().includes(q)
      );
    }

    const limite = this.paginaAtual * this.itensPorPagina;
    this.filteredPrograms = result.slice(0, limite);
    this.hasNext = result.length > limite;
  }

  confirmDelete(program: any) {
    this.poDialog.confirm({
      title: "Excluir Rotina",
      message: `Deseja realmente excluir a rotina <strong>${program.name}</strong>?<br>Esta aÃ§Ã£o nÃ£o pode ser desfeita.`,
      confirm: () => this.deleteProgram(program),
      cancel: () => {}
    });
  }

  deleteProgram(program: any) {
    this.isLoading = true;
    this.programService.delete(program.id).subscribe({
      next: () => {
        this.poNotification.success("Rotina excluÃ­da com sucesso!");
        this.loadPrograms();
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao excluir rotina.");
      }
    });
  }
}
