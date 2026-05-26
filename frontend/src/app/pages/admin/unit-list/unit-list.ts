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
import { UnitService } from "../../../services/unit";

@Component({
  selector: "app-unit-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="Unidades do Sistema"
      p-subtitle="GestÃ£o de filiais e conexÃµes"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">

      <po-table
        [p-columns]="columns"
        [p-items]="units"
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
export class UnitListComponent implements OnInit {
  private unitService = inject(UnitService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private filtroAtual = "";
  private allUnits: Array<any> = [];

  units: Array<any> = [];
  isLoading: boolean = false;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "SeguranÃ§a", link: "/admin/users" },
      { label: "Unidades" }
    ]
  };

  readonly filter: PoPageFilter = {
    action: this.loadUnits.bind(this),
    placeholder: "Pesquisar unidade"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Nova Unidade", action: () => this.router.navigate(["/admin/units/new"]), icon: "po-icon-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/units/edit/${row.id}`]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteUnit.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "name", label: "Nome da Unidade" },
    { property: "connectionName", label: "ConexÃ£o (ERP)" }
  ];

  ngOnInit() {
    this.loadUnits();
  }

  loadUnits(filter: string = "") {
    this.filtroAtual = filter;
    this.paginaAtual = 1;
    this.isLoading = true;
    this.unitService.findAll().subscribe({
      next: (res) => {
        this.allUnits = this.aplicarFiltroLocal(res || [], filter);
        this.atualizarPaginaVisivel();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.loadingShowMore = true;
    this.paginaAtual += 1;
    this.atualizarPaginaVisivel();
    this.loadingShowMore = false;
  }

  deleteUnit(unit: any) {
    if (confirm(`Deseja realmente excluir a unidade ${unit.name}?`)) {
      this.isLoading = true;
      this.unitService.delete(unit.id).subscribe({
        next: () => {
          this.poNotification.success("Unidade excluÃ­da com sucesso!");
          this.loadUnits(this.filtroAtual);
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir unidade.");
        }
      });
    }
  }

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.units = this.allUnits.slice(0, limite);
    this.hasNext = this.allUnits.length > limite;
  }

  private aplicarFiltroLocal(units: Array<any>, filter: string): Array<any> {
    if (!filter) {
      return units;
    }

    const filtroNormalizado = filter.toLowerCase();
    return units.filter(u => u.name?.toLowerCase().includes(filtroNormalizado));
  }
}
