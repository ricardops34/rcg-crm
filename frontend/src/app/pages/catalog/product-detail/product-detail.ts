import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoDynamicModule,
  PoDynamicViewField,
  PoModule,
  PoNotificationService,
  PoPageAction,
  PoDialogService
} from "@po-ui/ng-components";
import { finalize } from "rxjs";
import { ProductService } from "../../../services/product";

@Component({
  selector: "app-product-detail",
  standalone: true,
  imports: [CommonModule, PoModule, PoDynamicModule],
  templateUrl: "./product-detail.html"
})
export class ProductDetailComponent implements OnInit {
  private productService = inject(ProductService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);

  product: any = {};
  isLoading = false;
  action: 'view' | 'delete' = 'view';
  title = "Detalhes do Produto";

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Produtos", link: "/produtos" },
      { label: "Detalhes" }
    ]
  };

  pageActions: Array<PoPageAction> = [];

  readonly fields: Array<PoDynamicViewField> = [
    { property: "codErp", label: "Cód. ERP", divider: "Dados do Item", gridColumns: 2, gridSmColumns: 12 },
    { property: "descricao", label: "Descrição", gridColumns: 6, gridSmColumns: 12 },
    { property: "marca", label: "Marca", gridColumns: 4, gridSmColumns: 12 },

    { property: "categoriaDescricao", label: "Categoria", gridColumns: 3, gridSmColumns: 12 },
    { property: "um", label: "U.M.", gridColumns: 3, gridSmColumns: 12 },
    { property: "ncm", label: "NCM", gridColumns: 3, gridSmColumns: 12 },
    { property: "statusFormatado", label: "Status", gridColumns: 3, gridSmColumns: 12 },

    { property: "informacoes_tecnicas", label: "Ficha Técnica", divider: "Informações Técnicas", gridColumns: 12, gridSmColumns: 12 }
  ];

  ngOnInit(): void {
    const id = Number(this.activatedRoute.snapshot.params["id"]);
    this.action = this.activatedRoute.snapshot.queryParams["action"] || 'view';

    this.setupActions();

    if (id) {
      this.loadProduct(id);
    } else {
      this.router.navigate(["/produtos"]);
    }
  }

  setupActions() {
    if (this.action === 'delete') {
      this.title = "Excluir Produto";
      this.pageActions = [
        { label: "Confirmar", action: this.confirmDelete.bind(this), type: "danger", icon: "an an-trash" },
        { label: "Cancelar", action: this.back.bind(this) }
      ];
    } else {
      this.title = "Visualizar Produto";
      this.pageActions = [
        { label: "Editar", action: this.edit.bind(this), icon: "an an-pencil-simple" },
        { label: "Voltar", action: this.back.bind(this) }
      ];
    }
  }

  loadProduct(id: number) {
    this.isLoading = true;

    this.productService.findOne(id).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        if (!res) {
          this.poNotification.error("Produto não encontrado.");
          this.router.navigate(["/produtos"]);
          return;
        }

        this.product = {
          ...res,
          categoriaDescricao: (res as any).categoria?.descricao || (res as any).categoriaId,
          statusFormatado: res.status === 'A' ? 'Ativo' : 'Inativo'
        };
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados do produto.");
        this.router.navigate(["/produtos"]);
      }
    });
  }

  confirmDelete() {
    this.poDialog.confirm({
      title: "Confirmação de Exclusão",
      message: `Confirma a exclusão do produto ${this.product.descricao}?`,
      confirm: () => {
        this.isLoading = true;
        this.productService.delete(this.product.id).pipe(
          finalize(() => this.isLoading = false)
        ).subscribe({
          next: () => {
            this.poNotification.success("Produto excluído com sucesso!");
            this.router.navigate(["/produtos"]);
          },
          error: () => {
            this.poNotification.error("Erro ao excluir produto.");
          }
        });
      }
    });
  }

  edit() {
    this.router.navigate(["/produtos/edit", this.product.id]);
  }

  back() {
    this.router.navigate(["/produtos"]);
  }
}
