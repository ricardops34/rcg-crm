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
import { ClienteService } from "../../../services/cliente";

@Component({
  selector: "app-cliente-detail",
  standalone: true,
  imports: [CommonModule, PoModule, PoDynamicModule],
  templateUrl: "./cliente-detail.html"
})
export class ClienteDetailComponent implements OnInit {
  private clienteService = inject(ClienteService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);

  cliente: any = {};
  isLoading = false;
  action: 'view' | 'delete' = 'view';
  title = "Detalhes do Cliente";

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Clientes", link: "/clientes" },
      { label: "Detalhes" }
    ]
  };

  pageActions: Array<PoPageAction> = [];

  readonly fields: Array<PoDynamicViewField> = [
    { property: "codErp", label: "Cód. ERP", divider: "Identificação", gridColumns: 2, gridSmColumns: 12 },
    { property: "razao", label: "Razão Social", gridColumns: 6, gridSmColumns: 12 },
    { property: "fantasia", label: "Nome Fantasia", gridColumns: 4, gridSmColumns: 12 },
    { property: "cnpjCpf", label: "CNPJ / CPF", gridColumns: 4, gridSmColumns: 12 },
    { property: "ie", label: "Inscrição Estadual", gridColumns: 3, gridSmColumns: 12 },
    { property: "tipoFormatado", label: "Tipo", gridColumns: 2, gridSmColumns: 12 },
    { property: "statusFormatado", label: "Status", gridColumns: 3, gridSmColumns: 12 },
    { property: "cep", label: "CEP", divider: "Localização", gridColumns: 2, gridSmColumns: 12 },
    { property: "endereco", label: "Endereço", gridColumns: 6, gridSmColumns: 12 },
    { property: "bairro", label: "Bairro", gridColumns: 4, gridSmColumns: 12 },
    { property: "uf", label: "UF", gridColumns: 2, gridSmColumns: 12 },
    { property: "municipioNome", label: "Cidade", gridColumns: 4, gridSmColumns: 12 },
    { property: "complemento", label: "Complemento", gridColumns: 6, gridSmColumns: 12 },
    { property: "telefone1", label: "Telefone 1", divider: "Contato e Comercial", gridColumns: 3, gridSmColumns: 12 },
    { property: "celular", label: "Celular", gridColumns: 3, gridSmColumns: 12 },
    { property: "email", label: "E-mail", gridColumns: 6, gridSmColumns: 12 },
    { property: "vendedorNome", label: "Vendedor Responsável", gridColumns: 4, gridSmColumns: 12 },
    { property: "limite", label: "Limite de Crédito", type: "currency", gridColumns: 4, gridSmColumns: 12 },
    { property: "vencimentoLimite", label: "Vencimento Limite", type: "date", gridColumns: 4, gridSmColumns: 12 },
    { property: "obs", label: "Observações Internas", divider: "Observações", gridColumns: 12, gridSmColumns: 12 }
  ];

  ngOnInit(): void {
    const id = Number(this.activatedRoute.snapshot.params["id"]);
    this.action = this.activatedRoute.snapshot.queryParams["action"] || 'view';

    this.setupActions();

    if (id) {
      this.loadCliente(id);
    } else {
      this.router.navigate(["/clientes"]);
    }
  }

  setupActions() {
    if (this.action === 'delete') {
      this.title = "Excluir Cliente";
      this.pageActions = [
        { label: "Confirmar", action: this.confirmDelete.bind(this), type: "danger", icon: "an an-trash" },
        { label: "Cancelar", action: this.back.bind(this) }
      ];
    } else {
      this.title = "Visualizar Cliente";
      this.pageActions = [
        { label: "Editar", action: this.edit.bind(this), icon: "an an-pencil-simple" },
        { label: "Voltar", action: this.back.bind(this) }
      ];
    }
  }

  loadCliente(id: number) {
    this.isLoading = true;

    this.clienteService.findOne(id).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        if (!res) {
          this.poNotification.error("Cliente não encontrado.");
          this.router.navigate(["/clientes"]);
          return;
        }

        // Custom mapping to display relations nicely
        this.cliente = {
          ...res,
          vendedorNome: (res as any).vendedor?.nome || (res as any).vendedorId,
          municipioNome: (res as any).municipio?.name || (res as any).municipioId,
          tipoFormatado: res.tipo === 'J' ? 'Jurídica' : 'Física',
          statusFormatado: res.status === 'A' ? 'Ativo' : 'Bloqueado'
        };
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados do cliente.");
        this.router.navigate(["/clientes"]);
      }
    });
  }

  confirmDelete() {
    this.poDialog.confirm({
      title: "Confirmação de Exclusão",
      message: `Confirma a exclusão do registro de ${this.cliente.razao}?`,
      confirm: () => {
        this.isLoading = true;
        this.clienteService.delete(this.cliente.id).pipe(
          finalize(() => this.isLoading = false)
        ).subscribe({
          next: () => {
            this.poNotification.success("Cliente excluído com sucesso!");
            this.router.navigate(["/clientes"]);
          },
          error: () => {
            this.poNotification.error("Erro ao excluir cliente.");
          }
        });
      }
    });
  }

  edit() {
    this.router.navigate(["/clientes/edit", this.cliente.id]);
  }

  back() {
    this.router.navigate(["/clientes"]);
  }
}
