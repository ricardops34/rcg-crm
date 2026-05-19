import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoTableColumn, PoTableAction, PoPageAction, PoPageFilter, PoNotificationService } from "@po-ui/ng-components";
import { ClienteService } from "../../../services/cliente";

@Component({
  selector: "app-cliente-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./cliente-list.html"
})
export class ClienteListComponent implements OnInit {

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly pageActions: Array<PoPageAction> = [
    { label: "Novo Cliente", action: this.create.bind(this), icon: "po-icon-user-add" }
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

  readonly actions: Array<PoTableAction> = [
    { label: "Posição 360", action: (item: any) => this.router.navigate(["/clientes/360", item.id]), icon: "po-icon-eye" },
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteCliente.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  constructor(
    private clienteService: ClienteService, 
    private router: Router,
    private poNotification: PoNotificationService
  ) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filter?: string) {
    this.isLoading = true;
    this.clienteService.findAll(1, 200).subscribe({ 
      next: (res) => {
        this.items = res.items;
        if (filter) {
          this.items = this.items.filter(item => 
            item.razao?.toLowerCase().includes(filter.toLowerCase()) ||
            item.cnpjCpf?.toLowerCase().includes(filter.toLowerCase()) ||
            item.codErp?.toLowerCase().includes(filter.toLowerCase())
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
