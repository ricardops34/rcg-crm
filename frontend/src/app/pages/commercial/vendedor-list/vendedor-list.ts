import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoTableColumn, PoTableAction, PoPageAction, PoPageFilter } from "@po-ui/ng-components";
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

  readonly pageActions: Array<PoPageAction> = [
    { label: "Novo Vendedor", action: this.create.bind(this), icon: "po-icon-user-add" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "Filtrar por nome ou e-mail"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "codErp", label: "Cód. ERP", width: "100px" },
    { property: "nome", label: "Nome" },
    { property: "email", label: "E-mail" },
    { property: "status", label: "Status", type: "label", width: "120px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "celular", label: "Celular", width: "150px" }
  ];

  readonly actions: Array<PoTableAction> = [
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" }
  ];

  constructor(private vendedorService: VendedorService, private router: Router) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filter?: string) {
    this.isLoading = true;
    this.vendedorService.findAll().subscribe({
      next: (res) => {
        this.items = res.items;
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
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
  }


  edit(item: any) {
    this.router.navigate(["/vendedores/edit", item.id]);
  }

  create() {
    this.router.navigate(["/vendedores/new"]);
  }
}
