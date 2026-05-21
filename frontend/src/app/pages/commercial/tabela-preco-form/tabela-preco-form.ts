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
        // Se houver itens na tabela, carregaríamos aqui
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar tabela.");
      }
    });
  }

  save() {
    // Nota: O backend atual do TabelaPrecoService não tem o método 'save'.
    // Vou precisar adicionar ou apenas exibir as informações por enquanto.
    this.poNotification.warning("A alteração de tabelas de preços deve ser feita via sincronização com o ERP.");
  }

  cancel() {
    this.router.navigate(["/tabelas-precos"]);
  }
}
