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
import { VendedorService } from "../../../services/vendedor";

@Component({
  selector: "app-vendedor-detail",
  standalone: true,
  imports: [CommonModule, PoModule, PoDynamicModule],
  templateUrl: "./vendedor-detail.html"
})
export class VendedorDetailComponent implements OnInit {
  private vendedorService = inject(VendedorService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);

  vendedor: any = {};
  isLoading = false;
  action: 'view' | 'delete' = 'view';
  title = "Detalhes do Vendedor";

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendedores", link: "/vendedores" },
      { label: "Detalhes" }
    ]
  };

  pageActions: Array<PoPageAction> = [];

  readonly fields: Array<PoDynamicViewField> = [
    { property: "codErp", label: "Cód. ERP", divider: "Informações Básicas", gridColumns: 2, gridSmColumns: 12 },
    { property: "nome", label: "Nome Completo", gridColumns: 6, gridSmColumns: 12 },
    { property: "nomeReduzido", label: "Nome de Guerra", gridColumns: 4, gridSmColumns: 12 },
    { property: "email", label: "E-mail", gridColumns: 4, gridSmColumns: 12 },
    { property: "ddd", label: "DDD", gridColumns: 2, gridSmColumns: 12 },
    { property: "telefone", label: "Telefone", gridColumns: 3, gridSmColumns: 12 },
    { property: "celular", label: "Celular", gridColumns: 3, gridSmColumns: 12 },

    { property: "systemUnitNome", label: "Unidade Principal", divider: "Configuração e Hierarquia", gridColumns: 3, gridSmColumns: 12 },
    { property: "statusFormatado", label: "Status", gridColumns: 3, gridSmColumns: 12 },
    { property: "dtNascimento", label: "Data Nascimento", type: "date", gridColumns: 3, gridSmColumns: 12 },
    { property: "systemUsersId", label: "ID Usuário Relacionado", gridColumns: 3, gridSmColumns: 12 },

    { property: "supervisorFormatado", label: "É Supervisor?", gridColumns: 3, gridSmColumns: 12 },
    { property: "supervisorNome", label: "Supervisor Imediato", gridColumns: 3, gridSmColumns: 12 },
    { property: "dashboardFormatado", label: "Exibir Dashboard?", gridColumns: 3, gridSmColumns: 12 },
    { property: "desligadoFormatado", label: "Desligado?", gridColumns: 3, gridSmColumns: 12 }
  ];

  ngOnInit(): void {
    const id = Number(this.activatedRoute.snapshot.params["id"]);
    this.action = this.activatedRoute.snapshot.queryParams["action"] || 'view';

    this.setupActions();

    if (id) {
      this.loadVendedor(id);
    } else {
      this.router.navigate(["/vendedores"]);
    }
  }

  setupActions() {
    if (this.action === 'delete') {
      this.title = "Excluir Vendedor";
      this.pageActions = [
        { label: "Confirmar", action: this.confirmDelete.bind(this), type: "danger", icon: "an an-trash" },
        { label: "Cancelar", action: this.back.bind(this) }
      ];
    } else {
      this.title = "Visualizar Vendedor";
      this.pageActions = [
        { label: "Editar", action: this.edit.bind(this), icon: "an an-pencil-simple" },
        { label: "Voltar", action: this.back.bind(this) }
      ];
    }
  }

  loadVendedor(id: number) {
    this.isLoading = true;

    this.vendedorService.findOne(id).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        if (!res) {
          this.poNotification.error("Vendedor não encontrado.");
          this.router.navigate(["/vendedores"]);
          return;
        }

        this.vendedor = {
          ...res,
          systemUnitNome: (res as any).systemUnit?.name || (res as any).systemUnitId,
          supervisorNome: (res as any).supervisorParent?.nome || (res as any).supervisorId,
          statusFormatado: res.status === 'A' ? 'Ativo' : 'Inativo',
          supervisorFormatado: res.supervisor === 'S' ? 'Sim' : 'Não',
          dashboardFormatado: res.dashboard === 'S' ? 'Sim' : 'Não',
          desligadoFormatado: res.desligado === 'S' ? 'Sim' : 'Não'
        };
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados do vendedor.");
        this.router.navigate(["/vendedores"]);
      }
    });
  }

  confirmDelete() {
    this.poDialog.confirm({
      title: "Confirmação de Exclusão",
      message: `Confirma a exclusão do vendedor ${this.vendedor.nome}?`,
      confirm: () => {
        this.isLoading = true;
        this.vendedorService.delete(this.vendedor.id).pipe(
          finalize(() => this.isLoading = false)
        ).subscribe({
          next: () => {
            this.poNotification.success("Vendedor excluído com sucesso!");
            this.router.navigate(["/vendedores"]);
          },
          error: () => {
            this.poNotification.error("Erro ao excluir vendedor.");
          }
        });
      }
    });
  }

  edit() {
    this.router.navigate(["/vendedores/edit", this.vendedor.id]);
  }

  back() {
    this.router.navigate(["/vendedores"]);
  }
}
