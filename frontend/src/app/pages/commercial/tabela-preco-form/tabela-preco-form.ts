import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService, 
  PoBreadcrumb,
  PoTableColumn
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { TabelaPrecoService } from "../../../services/tabela-preco";

@Component({
  selector: "app-tabela-preco-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./tabela-preco-form.html"
})
export class TabelaPrecoFormComponent implements OnInit {
  private tabelaService = inject(TabelaPrecoService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  tabela: any = {
    status: "A",
    utiliza: "S"
  };
  
  items: Array<any> = [];
  isLoading: boolean = false;
  title: string = "Nova Tabela de Preço";

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Tabelas de Preços", link: "/tabelas-precos" },
      { label: "Manutenção" }
    ]
  };

  readonly itemColumns: Array<PoTableColumn> = [
    { property: "produto_nome", label: "Produto" },
    { property: "preco", label: "Preço Unitário", type: "currency", format: "BRL" },
    { property: "status", label: "Status", type: "label", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "I", color: "color-07", label: "Inativo" }
    ]}
  ];

  ngOnInit(): void {
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Tabela";
      this.loadTabela(id);
    }
  }

  loadTabela(id: number) {
    this.isLoading = true;
    this.tabelaService.findOne(id).subscribe({
      next: (res) => {
        this.tabela = res;
        this.loadItems(id);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar tabela.");
      }
    });
  }

  loadItems(id: number) {
    this.tabelaService.findItems(id).subscribe({
      next: (res) => {
        this.items = res;
        this.isLoading = false;
      },
      error: () => {
        this.items = [];
        this.isLoading = false;
        this.poNotification.warning("Itens da tabela não puderam ser carregados.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.tabelaService.save(this.tabela).subscribe({
      next: (res) => {
        this.isLoading = false;
        this.poNotification.success("Tabela de preço salva com sucesso!");

        if (this.tabela.id) {
          this.router.navigate(["/tabelas-precos"]);
          return;
        }

        this.router.navigate(["/tabelas-precos/edit", res.id]);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar tabela.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/tabelas-precos"]);
  }
}
