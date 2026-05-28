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
  PoDialogService,
  PoTableColumn
} from "@po-ui/ng-components";
import { finalize } from "rxjs";
import { TabelaPrecoService } from "../../../services/tabela-preco";

@Component({
  selector: "app-tabela-preco-detail",
  standalone: true,
  imports: [CommonModule, PoModule, PoDynamicModule],
  templateUrl: "./tabela-preco-detail.html"
})
export class TabelaPrecoDetailComponent implements OnInit {
  private tabelaService = inject(TabelaPrecoService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);

  tabela: any = { items: [] };
  isLoading = false;
  action: 'view' | 'delete' = 'view';
  title = "Detalhes da Tabela de Preço";

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Tabelas de Preço", link: "/tabelas-precos" },
      { label: "Detalhes" }
    ]
  };

  pageActions: Array<PoPageAction> = [];

  readonly fields: Array<PoDynamicViewField> = [
    { property: "codErp", label: "Cód. ERP", divider: "Definição da Tabela", gridColumns: 2, gridSmColumns: 12 },
    { property: "descricao", label: "Descrição da Tabela", gridColumns: 6, gridSmColumns: 12 },
    { property: "statusFormatado", label: "Status", gridColumns: 2, gridSmColumns: 12 },
    { property: "utilizaFormatado", label: "Em Uso", gridColumns: 2, gridSmColumns: 12 },
    { property: "dt_inicio", label: "Data Início", type: "date", gridColumns: 3, gridSmColumns: 12 },
    { property: "dt_fim", label: "Data Fim", type: "date", gridColumns: 3, gridSmColumns: 12 }
  ];

  readonly itemColumns: Array<PoTableColumn> = [
    { property: "produto.nome", label: "Produto" },
    { property: "preco", label: "Preço", type: "currency", format: "BRL" },
    { property: "descontoMaximo", label: "Desc. Máx (%)", type: "number" }
  ];

  ngOnInit(): void {
    const id = Number(this.activatedRoute.snapshot.params["id"]);
    this.action = this.activatedRoute.snapshot.queryParams["action"] || 'view';

    this.setupActions();

    if (id) {
      this.loadTabela(id);
    } else {
      this.router.navigate(["/tabelas-precos"]);
    }
  }

  setupActions() {
    if (this.action === 'delete') {
      this.title = "Excluir Tabela de Preço";
      this.pageActions = [
        { label: "Confirmar", action: this.confirmDelete.bind(this), type: "danger", icon: "po-icon-delete" },
        { label: "Cancelar", action: this.back.bind(this) }
      ];
    } else {
      this.title = "Visualizar Tabela de Preço";
      this.pageActions = [
        { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" },
        { label: "Voltar", action: this.back.bind(this) }
      ];
    }
  }

  loadTabela(id: number) {
    this.isLoading = true;

    this.tabelaService.findOne(id).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        if (!res) {
          this.poNotification.error("Tabela de Preço não encontrada.");
          this.router.navigate(["/tabelas-precos"]);
          return;
        }

        this.tabela = {
          ...res,
          statusFormatado: res.status === 'A' ? 'Ativa' : 'Inativa',
          utilizaFormatado: res.utiliza === 'S' ? 'Sim' : 'Não'
        };
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados da tabela.");
        this.router.navigate(["/tabelas-precos"]);
      }
    });
  }

  confirmDelete() {
    this.poDialog.confirm({
      title: "Confirmação de Exclusão",
      message: `Confirma a exclusão da tabela de preço ${this.tabela.descricao}?`,
      confirm: () => {
        this.isLoading = true;
        this.tabelaService.delete(this.tabela.id).pipe(
          finalize(() => this.isLoading = false)
        ).subscribe({
          next: () => {
            this.poNotification.success("Tabela de Preço excluída com sucesso!");
            this.router.navigate(["/tabelas-precos"]);
          },
          error: () => {
            this.poNotification.error("Erro ao excluir tabela de preço.");
          }
        });
      }
    });
  }

  edit() {
    this.router.navigate(["/tabelas-precos/edit", this.tabela.id]);
  }

  back() {
    this.router.navigate(["/tabelas-precos"]);
  }
}
