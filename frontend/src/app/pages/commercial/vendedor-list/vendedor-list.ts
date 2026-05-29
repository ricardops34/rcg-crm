import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoDialogService,
  PoModalComponent,
  PoModule,
  PoPageFilter,
  PoPageAction,
  PoNotificationService,
  PoSelectOption,
  PoTableAction,
  PoTableColumn,
  PoTableColumnSort,
  PoTableColumnSortType
} from "@po-ui/ng-components";
import { VendedorService } from "../../../services/vendedor";

type VendedorAdvancedFilters = {
  status?: string;
  supervisor?: string;
  dashboard?: string;
};

@Component({
  selector: "app-vendedor-list",
  standalone: true,
  imports: [CommonModule, FormsModule, PoModule],
  templateUrl: "./vendedor-list.html",
  styleUrl: ./vendedor-list.css"
})
export class VendedorListComponent implements OnInit {
  @ViewChild("advancedFilterModal", { static: true }) advancedFilterModal!: PoModalComponent;

  private vendedorService = inject(VendedorService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);
  private paginaAtual = 1;
  private readonly itensPorPagina = 20;
  private filtroAtual = "";
  private sortOrder?: string;

  items: Array<any> = [];
  isLoading: boolean = true;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;
  total: number = 0;
  advancedFilters: VendedorAdvancedFilters = {};
  draftFilters: VendedorAdvancedFilters = {};

  readonly statusOptions: Array<PoSelectOption> = [
    { label: "Todos", value: undefined as unknown as string },
    { label: "Ativo", value: "A" },
    { label: "Bloqueado", value: "B" }
  ];

  readonly yesNoOptions: Array<PoSelectOption> = [
    { label: "Todos", value: undefined as unknown as string },
    { label: "Sim", value: "S" },
    { label: "Nao", value: "N" }
  ];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendedores" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Novo Vendedor", action: this.create.bind(this), icon: "an an-user-plus" },
    { label: "Atualizar", action: () => this.loadData(), icon: "an an-arrows-clockwise" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    advancedAction: this.openAdvancedFilters.bind(this),
    placeholder: "Filtrar por nome ou e-mail"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "codErp", label: "Cód. ERP", width: "100px" },
    { property: "nome", label: "Nome Completo" },
    { property: "email", label: "E-mail" },
    { property: "filialRazao", label: "Filial Principal" },
    { property: "status", label: "Status", type: "label", width: "120px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "celular", label: "Celular", width: "150px" },
    { property: "supervisor", label: "Supervisor?", type: "label", width: "120px", labels: [
      { value: "S", color: "color-11", label: "Sim" },
      { value: "N", color: "color-08", label: "Não" }
    ]}
  ];

  readonly actions: Array<PoTableAction> = [
    {
      label: "Visualizar",
      icon: "an an-eye",
      action: (row: any) => this.router.navigate(["/vendedores/detail", row.id], { queryParams: { action: 'view' } })
    },
    {
      label: "Editar",
      icon: "an an-pencil-simple",
      action: (row: any) => this.router.navigate(["/vendedores/edit", row.id])
    },
    {
      label: "Criar Usuário",
      icon: "an an-user-plus",
      visible: (row: any) => !row.systemUsersId,
      disabled: (row: any) => !row.email,
      action: (row: any) => this.confirmCreateUser(row)
    },
    {
      label: "Enviar Senha",
      icon: "an an-envelope-simple",
      visible: (row: any) => !!row.systemUsersId,
      disabled: (row: any) => !row.email,
      action: (row: any) => this.confirmSendPassword(row)
    }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  get appliedFilters(): Array<{ key: keyof VendedorAdvancedFilters; label: string }> {
    const filters: Array<{ key: keyof VendedorAdvancedFilters; label: string }> = [];

    if (this.advancedFilters.status) {
      filters.push({ key: "status", label: `Status: ${this.getOptionLabel(this.statusOptions, this.advancedFilters.status)}` });
    }

    if (this.advancedFilters.supervisor) {
      filters.push({ key: "supervisor", label: `Supervisor: ${this.getOptionLabel(this.yesNoOptions, this.advancedFilters.supervisor)}` });
    }

    if (this.advancedFilters.dashboard) {
      filters.push({ key: "dashboard", label: `Dashboard: ${this.getOptionLabel(this.yesNoOptions, this.advancedFilters.dashboard)}` });
    }

    return filters;
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

    this.vendedorService.findAll(this.paginaAtual, this.itensPorPagina, {
      ...this.advancedFilters,
      order: this.sortOrder
    }).subscribe({
      next: (res) => {
        const novosItems = (res.items || []).map((item: any) => ({
          ...item,
          filialRazao: item.filial ? item.filial.razao : "Não vinculada"
        }));
        const itemsFiltrados = this.aplicarFiltroLocal(novosItems, filter);

        this.items = append ? [...this.items, ...itemsFiltrados] : itemsFiltrados;
        this.total = res.total;
        this.hasNext = Boolean((res as any)?.hasNext);
        this.isLoading = false;
        this.loadingShowMore = false;
      },
      error: () => {
        this.isLoading = false;
        this.loadingShowMore = false;
        this.poNotification.error("Erro ao carregar lista de vendedores.");
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
  }

  onSort(sort: PoTableColumnSort) {
    const property = sort.column?.property;
    if (!property) {
      return;
    }

    this.items = [];
    this.isLoading = true;
    this.sortOrder = sort.type === PoTableColumnSortType.Descending ? `-${property}` : property;
    this.loadData(this.filtroAtual);
  }

  openAdvancedFilters() {
    this.draftFilters = { ...this.advancedFilters };
    this.advancedFilterModal.open();
  }

  applyAdvancedFilters() {
    this.advancedFilters = {
      status: this.draftFilters.status || undefined,
      supervisor: this.draftFilters.supervisor || undefined,
      dashboard: this.draftFilters.dashboard || undefined
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

  removeAdvancedFilter(key: keyof VendedorAdvancedFilters) {
    this.advancedFilters = {
      ...this.advancedFilters,
      [key]: undefined
    };
    this.draftFilters = { ...this.advancedFilters };
    this.loadData(this.filtroAtual);
  }

  hasAppliedFilters(): boolean {
    return Boolean(this.advancedFilters.status || this.advancedFilters.supervisor || this.advancedFilters.dashboard);
  }

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.paginaAtual += 1;
    this.loadData(this.filtroAtual, true);
  }

  create() {
    this.router.navigate(["/vendedores/new"]);
  }

  confirmCreateUser(row: any) {
    const semEmail = !row.email;
    if (semEmail) {
      this.poNotification.warning('Este vendedor não possui e-mail cadastrado. Cadastre um e-mail antes de criar o usuário.');
      return;
    }
    this.poDialog.confirm({
      title: 'Criar Usuário de Acesso',
      message: `Deseja criar um usuário de sistema para o vendedor <strong>${row.nome}</strong>?
                <br><br>
                Um login será gerado automaticamente a partir do e-mail <strong>${row.email}</strong>
                e uma senha temporária será enviada para este endereço.
                <br><br>
                O vendedor deverá trocar a senha no primeiro acesso.`,
      confirm: () => this.createUser(row),
    });
  }

  createUser(row: any) {
    this.isLoading = true;
    this.vendedorService.createUser(row.id).subscribe({
      next: (res) => {
        this.isLoading = false;
        this.poNotification.success(res.message);
        this.loadData(this.filtroAtual);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error(err?.error?.message || 'Erro ao criar usuário.');
      }
    });
  }

  confirmSendPassword(row: any) {
    this.poDialog.confirm({
      title: 'Enviar Senha Temporária',
      message: `Deseja gerar e enviar uma senha temporária para <strong>${row.nome}</strong> (${row.email})?
                <br><br>O vendedor receberá um e-mail com as instruções de acesso e será obrigado a criar uma nova senha no primeiro login.`,
      confirm: () => this.sendPassword(row),
    });
  }

  sendPassword(row: any) {
    this.isLoading = true;
    this.vendedorService.sendPassword(row.id).subscribe({
      next: (res) => {
        this.isLoading = false;
        this.poNotification.success(res.message);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error(err?.error?.message || 'Erro ao enviar senha.');
      }
    });
  }

  private aplicarFiltroLocal(items: Array<any>, filter: string): Array<any> {
    if (!filter) {
      return items;
    }

    const filtroNormalizado = filter.toLowerCase();

    return items.filter(item =>
      item.nome?.toLowerCase().includes(filtroNormalizado) ||
      item.email?.toLowerCase().includes(filtroNormalizado)
    );
  }

  private getOptionLabel(options: Array<PoSelectOption>, value?: string): string {
    return options.find((option) => option.value === value)?.label || String(value || "");
  }
}
