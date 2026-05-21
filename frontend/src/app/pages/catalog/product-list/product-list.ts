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
import { ProductService } from "../../../services/product";

@Component({
  selector: "app-product-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Catálogo de Produtos"
      p-subtitle="Gestão de itens e categorias"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="pageActions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="items"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>
      
    </po-page-list>
  `
})
export class ProductListComponent implements OnInit {
  private productService = inject(ProductService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Produtos" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Novo Produto", action: () => this.router.navigate(["/produtos/new"]), icon: "po-icon-plus" },
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "Filtrar por nome ou código"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "codErp", label: "Cód. ERP", width: "120px" },
    { property: "descricao", label: "Descrição" },
    { property: "categoria.descricao", label: "Categoria" },
    { property: "um", label: "Unidade", width: "80px" },
    { property: "status", label: "Status", type: "label", width: "100px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "I", color: "color-07", label: "Inativo" }
    ]},
    { property: "marca", label: "Marca" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate(["/produtos/edit", row.id]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteProduct.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filterText?: string) {
    this.isLoading = true;
    this.productService.findAll().subscribe({
      next: (res) => {
        this.items = res;
        if (filterText) {
          this.items = this.items.filter(item => 
            item.descricao?.toLowerCase().includes(filterText.toLowerCase()) ||
            item.codErp?.includes(filterText)
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar catálogo.");
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
  }

  deleteProduct(product: any) {
    if (confirm(`Deseja realmente excluir o produto ${product.descricao}?`)) {
      this.isLoading = true;
      this.productService.delete(product.id).subscribe({
        next: () => {
          this.poNotification.success("Produto removido com sucesso!");
          this.loadData();
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir produto.");
        }
      });
    }
  }
}
