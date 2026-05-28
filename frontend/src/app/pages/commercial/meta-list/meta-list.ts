import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoModalComponent,
  PoModule,
  PoNotificationService,
  PoPageAction,
  PoPageFilter,
  PoSelectOption,
  PoTableAction,
  PoTableColumn,
  PoTableColumnSort,
  PoTableColumnSortType
} from "@po-ui/ng-components";
import { MetaVendedorService } from "../../../services/meta-vendedor";
import { VendedorService } from "../../../services/vendedor";

type MetaFilters = {
  ano?: string;
  mes?: string;
  vendedorId?: number;
};

@Component({
  selector: "app-meta-list",
  standalone: true,
  imports: [CommonModule, FormsModule, PoModule],
  template: `
    <po-page-list
      p-title="Objetivos e Metas"
      p-subtitle="Gestao de metas mensais de vendas"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="pageActions"
      [p-filter]="filter">

      <div class="meta-filters-panel" *ngIf="hasAppliedFilters()">
        <div class="meta-filters-title">Apresentando resultados filtrados por:</div>
        <div class="meta-filters-tags">
          <button class="meta-filter-clear" type="button" (click)="clearAdvancedFilters()">Remover todos</button>
          <button
            class="meta-filter-tag"
            type="button"
            *ngFor="let appliedFilter of appliedFilters"
            (click)="removeFilter(appliedFilter.key)">
            {{ appliedFilter.label }}
            <span class="meta-filter-tag-close">x</span>
          </button>
        </div>
      </div>

      <po-table
        [p-columns]="columns"
        [p-items]="items"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        [p-loading-show-more]="loadingShowMore"
        [p-show-more-disabled]="!hasNext"
        (p-show-more)="showMore()"
        (p-sort-by)="onSort($event)"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>

    </po-page-list>

    <po-modal
      #advancedFilterModal
      p-title="Busca avancada"
      [p-primary-action]="{ label: 'Aplicar filtros', action: applyAdvancedFilters.bind(this) }"
      [p-secondary-action]="{ label: 'Limpar', action: resetDraftFilters.bind(this) }">
      <div class="po-row">
        <po-input
          class="po-sm-12 po-md-4"
          name="ano"
          [(ngModel)]="draftFilters.ano"
          p-label="Ano"
          p-placeholder="Ex: 2025">
        </po-input>

        <po-select
          class="po-sm-12 po-md-4"
          name="mes"
          [ngModel]="draftFilters.mes"
          (p-change)="draftFilters.mes = $event"
          p-label="Mes"
          [p-options]="monthOptions">
        </po-select>

        <po-select
          class="po-sm-12 po-md-4"
          name="vendedorId"
          [ngModel]="draftFilters.vendedorId"
          (p-change)="draftFilters.vendedorId = $event"
          p-label="Vendedor"
          [p-options]="sellerOptions">
        </po-select>
      </div>
    </po-modal>
  `,
  styleUrl: "./meta-list.css"
})
export class MetaListComponent implements OnInit {
  @ViewChild("advancedFilterModal", { static: true }) advancedFilterModal!: PoModalComponent;

  private readonly metaService = inject(MetaVendedorService);
  private readonly vendedorService = inject(VendedorService);
  private readonly router = inject(Router);
  private readonly poNotification = inject(PoNotificationService);
  private paginaAtual = 1;
  private readonly itensPorPagina = 20;
  private filtroAtual = "";
  private sortOrder?: string;

  items: Array<any> = [];
  isLoading = true;
  loadingShowMore = false;
  hasNext = false;
  advancedFilters: MetaFilters = {};
  draftFilters: MetaFilters = {};
  sellerOptions: Array<PoSelectOption> = [];

  readonly monthOptions: Array<PoSelectOption> = [
    { label: "Todos", value: undefined as unknown as string },
    { label: "Janeiro", value: "01" },
    { label: "Fevereiro", value: "02" },
    { label: "Marco", value: "03" },
    { label: "Abril", value: "04" },
    { label: "Maio", value: "05" },
    { label: "Junho", value: "06" },
    { label: "Julho", value: "07" },
    { label: "Agosto", value: "08" },
    { label: "Setembro", value: "09" },
    { label: "Outubro", value: "10" },
    { label: "Novembro", value: "11" },
    { label: "Dezembro", value: "12" }
  ];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Metas de Venda" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Novo Objetivo", action: () => this.router.navigate(["/metas/new"]), icon: "po-icon-target" },
    { label: "Busca avancada", action: () => this.openAdvancedFilters(), icon: "po-icon-filter" },
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "Pesquisar por ano ou vendedor"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "ano", label: "Ano", width: "80px" },
    { property: "mes", label: "Mes", width: "80px" },
    { property: "vendedor.nome", label: "Vendedor" },
    { property: "valor", label: "Valor Meta", type: "currency", format: "BRL" },
    { property: "numeroCliente", label: "Positivacao", type: "number" },
    { property: "novoCliente", label: "Novos Clientes", type: "number" },
    {
      property: "tipo",
      label: "Tipo",
      type: "label",
      labels: [
        { value: "M", color: "color-10", label: "Mensal" },
        { value: "S", color: "color-08", label: "Semanal" }
      ]
    }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Visualizar", action: (row: any) => this.router.navigate(["/metas/detail", row.id], { queryParams: { action: "view" } }), icon: "po-icon-eye" },
    { label: "Editar", action: (row: any) => this.router.navigate(["/metas/edit", row.id]), icon: "po-icon-edit" },
    { label: "Excluir", action: (row: any) => this.router.navigate(["/metas/detail", row.id], { queryParams: { action: "delete" } }), icon: "po-icon-delete", type: "danger" }
  ];

  get appliedFilters(): Array<{ key: keyof MetaFilters; label: string }> {
    const filters: Array<{ key: keyof MetaFilters; label: string }> = [];

    if (this.advancedFilters.ano) {
      filters.push({ key: "ano", label: `Ano: ${this.advancedFilters.ano}` });
    }

    if (this.advancedFilters.mes) {
      filters.push({ key: "mes", label: `Mes: ${this.getMonthLabel(this.advancedFilters.mes)}` });
    }

    if (this.advancedFilters.vendedorId) {
      filters.push({
        key: "vendedorId",
        label: `Vendedor: ${this.sellerOptions.find((option) => option.value === this.advancedFilters.vendedorId)?.label || this.advancedFilters.vendedorId}`
      });
    }

    return filters;
  }

  ngOnInit(): void {
    this.loadSellerOptions();
    this.loadData();
  }

  loadData(filter: string = "", append: boolean = false) {
    this.filtroAtual = filter;

    if (append) {
      this.loadingShowMore = true;
    } else {
      this.paginaAtual = 1;
      this.items = [];
      this.isLoading = true;
    }

    this.metaService.findAll(this.paginaAtual, this.itensPorPagina, {
      ...this.advancedFilters,
      order: this.sortOrder
    }).subscribe({
      next: (res) => {
        const itemsFiltrados = this.aplicarFiltroLocal(res.items || [], filter);

        this.items = append ? [...this.items, ...itemsFiltrados] : itemsFiltrados;
        this.hasNext = Boolean(res?.total > this.paginaAtual * this.itensPorPagina);
        this.isLoading = false;
        this.loadingShowMore = false;
      },
      error: () => {
        this.isLoading = false;
        this.loadingShowMore = false;
        this.poNotification.error("Erro ao carregar metas.");
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
  }

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.paginaAtual += 1;
    this.loadData(this.filtroAtual, true);
  }

  onSort(sort: PoTableColumnSort) {
    const property = sort.column?.property;
    if (!property) {
      return;
    }

    this.sortOrder = sort.type === PoTableColumnSortType.Descending ? `-${property}` : property;
    this.loadData(this.filtroAtual);
  }

  openAdvancedFilters() {
    this.draftFilters = { ...this.advancedFilters };
    this.advancedFilterModal.open();
  }

  applyAdvancedFilters() {
    this.advancedFilters = {
      ano: this.draftFilters.ano?.trim() || undefined,
      mes: this.draftFilters.mes || undefined,
      vendedorId: this.draftFilters.vendedorId || undefined
    };

    this.advancedFilterModal.close();
    this.loadData(this.filtroAtual);
  }

  resetDraftFilters() {
    this.draftFilters = {};
  }

  clearAdvancedFilters() {
    this.advancedFilters = {};
    this.draftFilters = {};
    this.loadData(this.filtroAtual);
  }

  removeFilter(key: keyof MetaFilters) {
    this.advancedFilters = {
      ...this.advancedFilters,
      [key]: undefined
    };
    this.draftFilters = { ...this.advancedFilters };
    this.loadData(this.filtroAtual);
  }

  hasAppliedFilters(): boolean {
    return Boolean(this.advancedFilters.ano || this.advancedFilters.mes || this.advancedFilters.vendedorId);
  }

  private loadSellerOptions() {
    this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe({
      next: (res) => {
        this.sellerOptions = [
          { label: "Todos", value: undefined as unknown as number },
          ...(res.items || []).map((vendedor) => ({
            label: vendedor.nome,
            value: vendedor.id as number
          }))
        ];
      },
      error: () => {
        this.sellerOptions = [];
      }
    });
  }

  private aplicarFiltroLocal(items: Array<any>, filter: string): Array<any> {
    if (!filter) {
      return items;
    }

    const filtroNormalizado = filter.toLowerCase();

    return items.filter((item) =>
      item.ano?.toString().includes(filter) ||
      item.mes?.toString().includes(filter) ||
      item.vendedor?.nome?.toLowerCase().includes(filtroNormalizado)
    );
  }

  private getMonthLabel(monthValue: string): string {
    return this.monthOptions.find((option) => option.value === monthValue)?.label || monthValue;
  }
}
