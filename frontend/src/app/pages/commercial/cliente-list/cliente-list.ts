import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoTableColumn, PoTableAction } from "@po-ui/ng-components";
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

  readonly columns: Array<PoTableColumn> = [
    { property: "codErp", label: "Cód. ERP", width: "10%" },
    { property: "razao", label: "Razão Social" },
    { property: "cnpjCpf", label: "CNPJ/CPF", width: "15%" },
    { property: "status", label: "Status", type: "label", width: "10%", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "vendedor.nome", label: "Vendedor" },
    { property: "uf", label: "UF", width: "5%" }
  ];

  readonly actions: Array<PoTableAction> = [
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" }
  ];

  constructor(private clienteService: ClienteService, private router: Router) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData() {
    this.isLoading = true;
    this.clienteService.findAll().subscribe({
      next: (res) => {
        this.items = res.items;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  edit(item: any) {
    this.router.navigate(["/clientes/edit", item.id]);
  }

  create() {
    this.router.navigate(["/clientes/new"]);
  }

}
