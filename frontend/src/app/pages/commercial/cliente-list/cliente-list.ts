import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoTableColumn,
  PoTableAction,
  PoPageAction,
  PoPageFilter,
  PoNotificationService,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { ClienteService } from "../../../services/cliente";

@Component({
  selector: "app-cliente-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./cliente-list.html"
})
export class ClienteListComponent implements OnInit {
  private clienteService = inject(ClienteService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private paginaAtual = 1;
  private readonly itensPorPagina = 20;
  private filtroAtual = "";

  items: Array<any> = [];
  isLoading: boolean = true;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Clientes" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Novo Cliente", action: this.create.bind(this), icon: "po-icon-user-add" },
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "Filtrar por razÃ£o, CPF ou CNPJ"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "codErp", label: "CÃ³d. ERP", width: "100px" },
    { property: "razao", label: "RazÃ£o Social" },
    { property: "cnpjCpf", label: "CNPJ/CPF", width: "180px" },
    { property: "status", label: "Status", type: "label", width: "120px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "vendedor.nome", label: "Vendedor" },
    { property: "uf", label: "UF", width: "80px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "PosiÃ§Ã£o 360", action: (item: any) => this.router.navigate(["/clientes/360", item.id]), icon: "po-icon-eye" },
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteCliente.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filterText: string = "", append: boolean = false) {
    this.filtroAtual = filterText;

    if (append) {
      this.loadingShowMore = true;
    } else {
      this.paginaAtual = 1;
      this.items = [];
      this.isLoading = true;
    }

    this.clienteService.findAll(this.paginaAtual, this.itensPorPagina).subscribe({
      next: (res) => {
        const itemsFiltrados = this.aplicarFiltroLocal(res.items || [], filterText);

        this.items = append ? [...this.items, ...itemsFiltrados] : itemsFiltrados;
        this.hasNext = Boolean((res as any)?.hasNext);
        this.isLoading = false;
        this.loadingShowMore = false;
      },
      error: () => {
        this.isLoading = false;
        this.loadingShowMore = false;
        this.poNotification.error("Erro ao carregar lista de clientes.");
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

  edit(item: any) {
    this.router.navigate(["/clientes/edit", item.id]);
  }

  create() {
    this.router.navigate(["/clientes/new"]);
  }

  deleteCliente(item: any) {
    if (confirm(`Deseja realmente excluir o cliente ${item.razao}?`)) {
      this.isLoading = true;
      this.clienteService.delete(item.id).subscribe({
        next: () => {
          this.poNotification.success("Cliente excluÃ­do com sucesso!");
          this.loadData(this.filtroAtual);
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir cliente.");
        }
      });
    }
  }

  private aplicarFiltroLocal(items: Array<any>, filterText: string): Array<any> {
    if (!filterText) {
      return items;
    }

    const filtroNormalizado = filterText.toLowerCase();

    return items.filter(item =>
      item.razao?.toLowerCase().includes(filtroNormalizado) ||
      item.cnpjCpf?.toLowerCase().includes(filtroNormalizado) ||
      item.codErp?.toLowerCase().includes(filtroNormalizado)
    );
  }
}
