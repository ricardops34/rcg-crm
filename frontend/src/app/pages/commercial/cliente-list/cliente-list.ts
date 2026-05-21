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

  items: Array<any> = [];
  isLoading: boolean = true;

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
    placeholder: "Filtrar por razão, CPF ou CNPJ"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "codErp", label: "Cód. ERP", width: "100px" },
    { property: "razao", label: "Razão Social" },
    { property: "cnpjCpf", label: "CNPJ/CPF", width: "180px" },
    { property: "status", label: "Status", type: "label", width: "120px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "vendedor.nome", label: "Vendedor" },
    { property: "uf", label: "UF", width: "80px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Posição 360", action: (item: any) => this.router.navigate(["/clientes/360", item.id]), icon: "po-icon-eye" },
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteCliente.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filterText?: string) {
    this.isLoading = true;
    this.clienteService.findAll(1, 200).subscribe({ 
      next: (res) => {
        this.items = res.items;
        if (filterText) {
          this.items = this.items.filter(item => 
            item.razao?.toLowerCase().includes(filterText.toLowerCase()) ||
            item.cnpjCpf?.toLowerCase().includes(filterText.toLowerCase()) ||
            item.codErp?.toLowerCase().includes(filterText.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
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
          this.poNotification.success("Cliente excluído com sucesso!");
          this.loadData();
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir cliente.");
        }
      });
    }
  }
}
