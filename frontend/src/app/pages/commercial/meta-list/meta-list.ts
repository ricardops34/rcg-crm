import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoTableColumn, PoTableAction } from "@po-ui/ng-components";
import { MetaVendedorService } from "../../../services/meta-vendedor";

@Component({
  selector: "app-meta-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./meta-list.html"
})
export class MetaListComponent implements OnInit {

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly columns: Array<PoTableColumn> = [
    { property: "vendedor", label: "Vendedor",  },
    { property: "mes", label: "M�s", width: "100px" },
    { property: "ano", label: "Ano", width: "100px" },
    { property: "valor", label: "Meta Financeira", type: "currency", format: "BRL" },
    { property: "numero_cliente", label: "Meta Positiva��o", type: "number" }
  ];

  readonly actions: Array<PoTableAction> = [
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" }
  ];

  constructor(private metaService: MetaVendedorService, private router: Router) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData() {
    this.isLoading = true;
    this.metaService.findAll().subscribe({
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
    this.router.navigate(["/metas/edit", item.id]);
  }

  create() {
    this.router.navigate(["/metas/new"]);
  }
}
