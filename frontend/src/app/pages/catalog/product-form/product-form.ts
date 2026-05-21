import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService, 
  PoSelectOption,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ProductService } from "../../../services/product";

@Component({
  selector: "app-product-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit 
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()">

      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <form #formProduct="ngForm">
        
        <po-divider p-label="Dados do Item"></po-divider>
        <div class="po-row">
          <po-input class="po-md-2" name="codErp" [(ngModel)]="product.codErp" p-label="Cód. ERP" p-required p-clean></po-input>
          <po-input class="po-md-7" name="descricao" [(ngModel)]="product.descricao" p-label="Descrição do Produto" p-required p-clean></po-input>
          <po-input class="po-md-3" name="marca" [(ngModel)]="product.marca" p-label="Marca" p-clean></po-input>
        </div>

        <div class="po-row">
          <po-select 
            class="po-md-4" 
            name="categoriaId" 
            [ngModel]="product.categoriaId" 
            (p-change)="product.categoriaId = $event"
            p-label="Categoria" 
            [p-options]="categoriaOptions" 
            p-required>
          </po-select>
          <po-input class="po-md-2" name="um" [(ngModel)]="product.um" p-label="U.M." p-placeholder="PC, CX, KG" p-required></po-input>
          <po-input class="po-md-3" name="ncm" [(ngModel)]="product.ncm" p-label="NCM" p-mask="9999.99.99"></po-input>
          <po-select 
            class="po-md-3" 
            name="status" 
            [ngModel]="product.status" 
            (p-change)="product.status = $event"
            p-label="Status" 
            [p-options]="[{label:'Ativo', value:'A'}, {label:'Inativo', value:'I'}]">
          </po-select>
        </div>

        <po-divider p-label="Informações Técnicas"></po-divider>
        <div class="po-row">
          <po-textarea class="po-md-12" name="informacoes_tecnicas" [(ngModel)]="product.informacoesTecnicas" p-label="Ficha Técnica" [p-rows]="5"></po-textarea>
        </div>

      </form>
    </po-page-edit>
  `
})
export class ProductFormComponent implements OnInit {
  private productService = inject(ProductService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  product: any = { status: "A" };
  isLoading: boolean = false;
  title: string = "Novo Produto";

  categoriaOptions: Array<PoSelectOption> = [];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Produtos", link: "/produtos" },
      { label: "Cadastro" }
    ]
  };

  ngOnInit(): void {
    this.loadCategorias();
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Produto";
      this.loadProduct(id);
    }
  }

  loadCategorias() {
    this.productService.getCategorias().subscribe(res => {
      this.categoriaOptions = res.map((c: any) => ({ label: c.descricao, value: c.id }));
    });
  }

  loadProduct(id: number) {
    this.isLoading = true;
    this.productService.findOne(id).subscribe({
      next: (res) => {
        this.product = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar produto.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.productService.save(this.product).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Produto salvo com sucesso!");
        this.router.navigate(["/produtos"]);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar produto.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/produtos"]);
  }
}
