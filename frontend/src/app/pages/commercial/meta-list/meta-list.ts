import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoTableColumn, PoTableAction, PoPageAction, PoPageFilter } from "@po-ui/ng-components";
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

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendas", link: "/metas" },
      { label: "Objetivos e Metas" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Nova Meta", action: this.create.bind(this), icon: "po-icon-plus" }
  ];

  readonly filter: PoPageFilter = {
    action: this.loadData.bind(this),
    placeholder: "Pesquisar por vendedor"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "vendedor.nome", label: "Vendedor" },
    { property: "mes", label: "Mês", width: "100px" },
    { property: "ano", label: "Ano", width: "100px" },
    { property: "valor", label: "Meta Financeira", type: "currency", format: "BRL" },
    { property: "numeroCliente", label: "Meta Positivação", type: "number" }
  ];

  readonly actions: Array<PoTableAction> = [
    { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" }
  ];

  constructor(private metaService: MetaVendedorService, private router: Router) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filter?: string) {
    this.isLoading = true;
    this.metaService.findAll().subscribe({
      next: (res) => {
        this.items = res.items;
        if (filter) {
          this.items = this.items.filter(item => 
            item.vendedor?.nome?.toLowerCase().includes(filter.toLowerCase())
          );
        }
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
