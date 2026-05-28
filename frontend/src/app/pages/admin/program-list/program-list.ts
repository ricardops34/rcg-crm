import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoPageAction,
  PoTableColumn,
  PoTableAction,
  PoBreadcrumb,
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
          p-label="Filtrar por Modulo"
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
  private readonly programService = inject(ProgramService);
  private readonly moduleService = inject(ModuleService);
  private readonly router = inject(Router);
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;

  programs: Array<any> = [];
  filteredPrograms: Array<any> = [];
  moduleFilterOptions: Array<PoSelectOption> = [{ label: "Todos os Modulos", value: "" }];
  selectedModule = "";
  filterText = "";
  isLoading = false;
  loadingShowMore = false;
  hasNext = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Administracao", link: "/admin/users" },
      { label: "Rotinas" }
    ]
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Nova Rotina", action: () => this.router.navigate(["/admin/programs/new"]), icon: "po-icon-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/programs/edit/${row.id}`]), icon: "po-icon-edit" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "70px", type: "number" },
    { property: "name", label: "Nome da Rotina" },
    { property: "controller", label: "Controller" },
    { property: "moduleName", label: "Modulo", width: "160px" },
    { property: "icon", label: "Icone", width: "120px" }
  ];

  ngOnInit() {
    this.loadModules();
    this.loadPrograms();
  }

  loadModules() {
    this.moduleService.findAll().subscribe({
      next: (modules: any[]) => {
        const opts = modules.map((module) => ({ label: module.name, value: String(module.id) }));
        this.moduleFilterOptions = [{ label: "Todos os Modulos", value: "" }, ...opts];
      }
    });
  }

  loadPrograms() {
    this.isLoading = true;
    this.paginaAtual = 1;
    this.programService.findAll().subscribe({
      next: (res: any[]) => {
        this.programs = res.map((program) => ({
          ...program,
          moduleName: program.systemModule?.name ?? "-- sem modulo --"
        }));
        this.applyFilter();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
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
      result = result.filter((program) => String(program.systemModuleId) === this.selectedModule);
    }

    if (this.filterText?.trim()) {
      const search = this.filterText.toLowerCase();
      result = result.filter((program) =>
        program.name?.toLowerCase().includes(search) ||
        program.controller?.toLowerCase().includes(search)
      );
    }

    const limit = this.paginaAtual * this.itensPorPagina;
    this.filteredPrograms = result.slice(0, limit);
    this.hasNext = result.length > limit;
  }
}
