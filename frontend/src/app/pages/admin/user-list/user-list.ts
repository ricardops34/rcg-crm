import { Component, OnInit, ViewChild } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoModalComponent,
  PoModule,
  PoNotificationService,
  PoPageAction,
  PoSelectOption,
  PoPageFilter,
  PoTableAction,
  PoTableColumn,
  PoTableColumnSort,
  PoTableColumnSortType
} from "@po-ui/ng-components";
import { UserService } from "../../../services/user";
import { UnitService } from "../../../services/unit";

type UserAdvancedFilters = {
  systemUnitId?: number;
  active?: string;
  acceptedTermPolicy?: string;
};

@Component({
  selector: "app-user-list",
  standalone: true,
  imports: [CommonModule, FormsModule, PoModule],
  template: `
    <po-page-list
      p-title="Usuarios do Sistema"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">

      @if (hasAppliedFilters()) {
        <div class="advanced-filters-panel">
          <div class="advanced-filters-title">Apresentando resultados filtrados por:</div>
          <div class="advanced-filters-tags">
            <button class="advanced-filter-clear" type="button" (click)="clearAdvancedFilters()">Remover todos</button>
            @for (appliedFilter of appliedFilters; track appliedFilter.key) {
              <button class="advanced-filter-tag" type="button" (click)="removeAdvancedFilter(appliedFilter.key)">
                {{ appliedFilter.label }}
                <span class="advanced-filter-tag-close">x</span>
              </button>
            }
          </div>
        </div>
      }

      <po-table
        [p-columns]="columns"
        [p-items]="users"
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
        <po-select
          class="po-sm-12 po-md-4"
          name="systemUnitId"
          [ngModel]="draftFilters.systemUnitId"
          (p-change)="draftFilters.systemUnitId = $event"
          p-label="Unidade"
          [p-options]="unitOptions">
        </po-select>

        <po-select
          class="po-sm-12 po-md-4"
          name="active"
          [ngModel]="draftFilters.active"
          (p-change)="draftFilters.active = $event"
          p-label="Ativo"
          [p-options]="yesNoOptions">
        </po-select>

        <po-select
          class="po-sm-12 po-md-4"
          name="acceptedTermPolicy"
          [ngModel]="draftFilters.acceptedTermPolicy"
          (p-change)="draftFilters.acceptedTermPolicy = $event"
          p-label="LGPD"
          [p-options]="yesNoOptions">
        </po-select>
      </div>
    </po-modal>
  `,
  styleUrl: "./user-list.css"
})
export class UserListComponent implements OnInit {
  @ViewChild("advancedFilterModal", { static: true }) advancedFilterModal!: PoModalComponent;

  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private filtroAtual = "";
  private allUsers: Array<any> = [];
  private sortState?: { property: string; descending: boolean };

  users: Array<any> = [];
  isLoading = false;
  loadingShowMore = false;
  hasNext = false;
  advancedFilters: UserAdvancedFilters = {};
  draftFilters: UserAdvancedFilters = {};
  unitOptions: Array<PoSelectOption> = [];

  readonly yesNoOptions: Array<PoSelectOption> = [
    { label: "Todos", value: undefined as unknown as string },
    { label: "Sim", value: "Y" },
    { label: "Nao", value: "N" }
  ];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Seguranca", link: "/admin/users" },
      { label: "Usuarios" }
    ]
  };

  readonly filter: PoPageFilter = {
    action: this.loadUsers.bind(this),
    advancedAction: this.openAdvancedFilters.bind(this),
    placeholder: "Pesquisar por nome ou login"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Usuario", action: () => this.router.navigate(["/admin/users/new"]), icon: "an an-user-plus" },
    { label: "Configurar Termos/LGPD", action: () => this.router.navigate(["/admin/users/terms"]), icon: "an an-file-text" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/users/edit/${row.id}`]), icon: "an an-pencil-simple" },
    { label: "Excluir", action: this.deleteUser.bind(this), icon: "an an-trash", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "name", label: "Nome Completo" },
    { property: "login", label: "Login" },
    { property: "systemUnit.name", label: "Unidade" },
    { property: "grupos", label: "Perfis" },
    { property: "acceptedTermPolicy", label: "LGPD", type: "label", labels: [
      { value: "Y", color: "color-10", label: "Aceitou" },
      { value: "N", color: "color-07", label: "Pendente" }
    ]},
    { property: "acceptedTermPolicyAt", label: "Data Aceite", type: "date", format: "dd/MM/yyyy HH:mm" },
    { property: "active", label: "Ativo", type: "label", labels: [
      { value: "Y", color: "color-10", label: "Sim" },
      { value: "N", color: "color-07", label: "Nao" }
    ]}
  ];

  constructor(
    private userService: UserService,
    private unitService: UnitService,
    private router: Router,
    private poNotification: PoNotificationService
  ) {}

  ngOnInit() {
    this.loadUnits();
    this.loadUsers();
  }

  get appliedFilters(): Array<{ key: keyof UserAdvancedFilters; label: string }> {
    const filters: Array<{ key: keyof UserAdvancedFilters; label: string }> = [];

    if (this.advancedFilters.systemUnitId) {
      filters.push({
        key: "systemUnitId",
        label: `Unidade: ${this.unitOptions.find((option) => option.value === this.advancedFilters.systemUnitId)?.label || this.advancedFilters.systemUnitId}`
      });
    }

    if (this.advancedFilters.active) {
      filters.push({ key: "active", label: `Ativo: ${this.getOptionLabel(this.yesNoOptions, this.advancedFilters.active)}` });
    }

    if (this.advancedFilters.acceptedTermPolicy) {
      filters.push({ key: "acceptedTermPolicy", label: `LGPD: ${this.getOptionLabel(this.yesNoOptions, this.advancedFilters.acceptedTermPolicy)}` });
    }

    return filters;
  }

  loadUsers(filter: string = "") {
    this.filtroAtual = filter;
    this.paginaAtual = 1;
    this.isLoading = true;
    this.userService.findAll().subscribe({
      next: (res) => {
        const users = res.map((u: any) => ({
          ...u,
          grupos: u.userGroups?.map((ug: any) => ug.systemGroup?.name).join(", ")
        }));
        this.allUsers = this.aplicarOrdenacao(this.aplicarFiltros(users, filter));
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

  onSort(sort: PoTableColumnSort) {
    const property = sort.column?.property;
    if (!property) {
      return;
    }

    this.sortState = {
      property,
      descending: sort.type === PoTableColumnSortType.Descending
    };
    this.loadUsers(this.filtroAtual);
  }

  openAdvancedFilters() {
    this.draftFilters = { ...this.advancedFilters };
    this.advancedFilterModal.open();
  }

  applyAdvancedFilters() {
    this.advancedFilters = {
      systemUnitId: this.draftFilters.systemUnitId || undefined,
      active: this.draftFilters.active || undefined,
      acceptedTermPolicy: this.draftFilters.acceptedTermPolicy || undefined
    };
    this.advancedFilterModal.close();
    this.loadUsers(this.filtroAtual);
  }

  resetDraftFilters() {
    this.draftFilters = {};
  }

  clearAdvancedFilters() {
    this.advancedFilters = {};
    this.draftFilters = {};
    this.loadUsers(this.filtroAtual);
  }

  removeAdvancedFilter(key: keyof UserAdvancedFilters) {
    this.advancedFilters = {
      ...this.advancedFilters,
      [key]: undefined
    };
    this.draftFilters = { ...this.advancedFilters };
    this.loadUsers(this.filtroAtual);
  }

  hasAppliedFilters(): boolean {
    return Boolean(this.advancedFilters.systemUnitId || this.advancedFilters.active || this.advancedFilters.acceptedTermPolicy);
  }

  deleteUser(user: any) {
    if (confirm(`Deseja realmente excluir o usuario ${user.name}?`)) {
      this.isLoading = true;
      this.userService.delete(user.id).subscribe({
        next: () => {
          this.poNotification.success("Usuario excluido com sucesso!");
          this.loadUsers(this.filtroAtual);
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir usuario.");
        }
      });
    }
  }

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.users = this.allUsers.slice(0, limite);
    this.hasNext = this.allUsers.length > limite;
  }

  private aplicarFiltros(users: Array<any>, filter: string): Array<any> {
    const filtroNormalizado = filter.toLowerCase();

    return users.filter((u) => {
      const matchText =
        !filter ||
        u.name?.toLowerCase().includes(filtroNormalizado) ||
        u.login?.toLowerCase().includes(filtroNormalizado);

      const matchUnit = !this.advancedFilters.systemUnitId || u.systemUnit?.id === this.advancedFilters.systemUnitId;
      const matchActive = !this.advancedFilters.active || u.active === this.advancedFilters.active;
      const matchLgpd = !this.advancedFilters.acceptedTermPolicy || u.acceptedTermPolicy === this.advancedFilters.acceptedTermPolicy;

      return Boolean(matchText && matchUnit && matchActive && matchLgpd);
    });
  }

  private aplicarOrdenacao(users: Array<any>): Array<any> {
    if (!this.sortState) {
      return users;
    }

    const { property, descending } = this.sortState;
    const direction = descending ? -1 : 1;

    return [...users].sort((left, right) => {
      const leftValue = this.getNestedValue(left, property);
      const rightValue = this.getNestedValue(right, property);

      if (leftValue == null && rightValue == null) {
        return 0;
      }

      if (leftValue == null) {
        return 1;
      }

      if (rightValue == null) {
        return -1;
      }

      if (typeof leftValue === "number" && typeof rightValue === "number") {
        return (leftValue - rightValue) * direction;
      }

      return String(leftValue).localeCompare(String(rightValue), "pt-BR", {
        numeric: true,
        sensitivity: "base"
      }) * direction;
    });
  }

  private getNestedValue(item: any, property: string): any {
    return property.split(".").reduce((value, key) => value?.[key], item);
  }

  private loadUnits() {
    this.unitService.findAll().subscribe({
      next: (res) => {
        this.unitOptions = [
          { label: "Todas", value: undefined as unknown as number },
          ...(res || []).map((unit: any) => ({
            label: unit.name,
            value: unit.id
          }))
        ];
      },
      error: () => {
        this.unitOptions = [];
      }
    });
  }

  private getOptionLabel(options: Array<PoSelectOption>, value?: string): string {
    return options.find((option) => option.value === value)?.label || String(value || "");
  }
}
