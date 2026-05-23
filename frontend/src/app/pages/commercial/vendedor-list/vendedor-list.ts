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
import { VendedorService } from "../../../services/vendedor";

@Component({
  selector: "app-vendedor-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./vendedor-list.html"
})
export class VendedorListComponent implements OnInit {
  private vendedorService = inject(VendedorService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  items: Array<any> = [];
  isLoading: boolean = true;
  total: number = 0;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendedores" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Novo Vendedor", action: this.create.bind(this), icon: "po-icon-user-add" },
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
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
    { label: "Editar", action: (row: any) => this.router.navigate(["/vendedores/edit", row.id]), icon: "po-icon-edit" },
    { label: "Excluir", action: (row: any) => this.remove(row), icon: "po-icon-delete" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filter?: string) {
    this.isLoading = true;
    this.vendedorService.findAll(1, 100).subscribe({
      next: (res) => {
        this.items = res.items.map((item: any) => ({
          ...item,
          filialRazao: item.filial ? item.filial.razao : "Não vinculada"
        }));
        this.total = res.total;
        if (filter) {
          this.items = this.items.filter(item => 
            item.nome?.toLowerCase().includes(filter.toLowerCase()) ||
            item.email?.toLowerCase().includes(filter.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar lista de vendedores.");
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
  }

  create() {
    this.router.navigate(["/vendedores/new"]);
  }

  remove(row: any) {
    if (!confirm(`Excluir o vendedor "${row.nome}"?`)) {
      return;
    }

    this.isLoading = true;
    this.vendedorService.delete(row.id).subscribe({
      next: () => {
        this.poNotification.success("Vendedor excluído com sucesso!");
        this.loadData();
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao excluir vendedor.");
      }
    });
  }
}
