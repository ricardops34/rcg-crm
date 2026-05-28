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
        [p-loading-show-more]="loadingShowMore"
        [p-show-more-disabled]="!hasNext"
        (p-show-more)="showMore()"
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
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private filtroAtual = "";
  private allItems: Array<any> = [];

  items: Array<any> = [];
  isLoading: boolean = true;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

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
    { label: "Visualizar", action: (row: any) => this.router.navigate(["/produtos/detail", row.id], { queryParams: { action: 'view' } }), icon: "po-icon-eye" },
    { label: "Editar", action: (row: any) => this.router.navigate(["/produtos/edit", row.id]), icon: "po-icon-edit" },
    { label: "Excluir", action: (row: any) => this.router.navigate(["/produtos/detail", row.id], { queryParams: { action: 'delete' } }), icon: "po-icon-delete", type: "danger" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filterText: string = "") {
    this.filtroAtual = filterText;
    this.paginaAtual = 1;
    this.isLoading = true;

    this.productService.findAll().subscribe({
      next: (res) => {
        this.allItems = this.aplicarFiltroLocal(res || [], filterText);
        this.atualizarPaginaVisivel();
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

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.loadingShowMore = true;
    this.paginaAtual += 1;
    this.atualizarPaginaVisivel();
    this.loadingShowMore = false;
  }

  deleteProduct(product: any) {
    if (confirm(`Deseja realmente excluir o produto ${product.descricao}?`)) {
      this.isLoading = true;
      this.productService.delete(product.id).subscribe({
        next: () => {
          this.poNotification.success("Produto removido com sucesso!");
          this.loadData(this.filtroAtual);
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir produto.");
        }
      });
    }
  }

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.items = this.allItems.slice(0, limite);
    this.hasNext = this.allItems.length > limite;
  }

  private aplicarFiltroLocal(items: Array<any>, filterText: string): Array<any> {
    if (!filterText) {
      return items;
    }

    const filtroNormalizado = filterText.toLowerCase();
    return items.filter(item =>
      item.descricao?.toLowerCase().includes(filtroNormalizado) ||
      item.codErp?.includes(filterText)
    );
  }
}
