import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoTableColumn, PoTableAction } from "@po-ui/ng-components";
import { VendedorService } from "../../../services/vendedor";

@Component({
  selector: "app-vendedor-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./vendedor-list.html"
})
export class VendedorListComponent implements OnInit {

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly columns: Array<PoTableColumn> = [
    { property: "codErp", label: "Cód. ERP", width: "10%" },
    { property: "nome", label: "Nome" },
    { property: "email", label: "E-mail" },
    { property: "status", label: "Status", type: "label", width: "10%", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "celular", label: "Celular", width: "15%" }
  ];

  readonly actions: Array<PoTableAction> = [
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" }
  ];

  constructor(private vendedorService: VendedorService, private router: Router) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData() {
    this.isLoading = true;
    this.vendedorService.findAll().subscribe({
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
    this.router.navigate(["/vendedores/edit", item.id]);
  }

  create() {
    this.router.navigate(["/vendedores/new"]);
  }
}
