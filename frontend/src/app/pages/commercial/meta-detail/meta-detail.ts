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
import { MetaService } from "../../../services/meta";

@Component({
  selector: "app-meta-detail",
  standalone: true,
  imports: [CommonModule, PoModule, PoDynamicModule],
  templateUrl: "./meta-detail.html"
})
export class MetaDetailComponent implements OnInit {
  private metaService = inject(MetaService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private poDialog = inject(PoDialogService);

  meta: any = {};
  isLoading = false;
  action: 'view' | 'delete' = 'view';
  title = "Detalhes da Meta";

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Metas", link: "/metas" },
      { label: "Detalhes" }
    ]
  };

  pageActions: Array<PoPageAction> = [];

  readonly fields: Array<PoDynamicViewField> = [
    { property: "ano", label: "Ano", divider: "Período e Responsável", gridColumns: 2, gridSmColumns: 12 },
    { property: "mes", label: "Mês", gridColumns: 3, gridSmColumns: 12 },
    { property: "vendedorNome", label: "Vendedor", gridColumns: 5, gridSmColumns: 12 },
    { property: "tipo", label: "Tipo", gridColumns: 2, gridSmColumns: 12, value: (val) => val === 'M' ? 'Mensal' : (val === 'S' ? 'Semanal' : val) },
    { property: "valor", label: "Valor do Objetivo (R$)", divider: "Metas de Valor e Volume", type: "currency", gridColumns: 4, gridSmColumns: 12 },
    { property: "numeroCliente", label: "Meta de Positivação", gridColumns: 4, gridSmColumns: 12 },
    { property: "novoCliente", label: "Meta de Novos Clientes", gridColumns: 4, gridSmColumns: 12 }
  ];

  ngOnInit(): void {
    const id = Number(this.activatedRoute.snapshot.params["id"]);
    this.action = this.activatedRoute.snapshot.queryParams["action"] || 'view';

    this.setupActions();

    if (id) {
      this.loadMeta(id);
    } else {
      this.router.navigate(["/metas"]);
    }
  }

  setupActions() {
    if (this.action === 'delete') {
      this.title = "Excluir Meta";
      this.pageActions = [
        { label: "Confirmar", action: this.confirmDelete.bind(this), type: "danger", icon: "po-icon-delete" },
        { label: "Cancelar", action: this.back.bind(this) }
      ];
    } else {
      this.title = "Visualizar Meta";
      this.pageActions = [
        { label: "Editar", action: this.edit.bind(this), icon: "po-icon-edit" },
        { label: "Voltar", action: this.back.bind(this) }
      ];
    }
  }

  loadMeta(id: number) {
    this.isLoading = true;

    this.metaService.findOne(id).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        if (!res) {
          this.poNotification.error("Meta não encontrada.");
          this.router.navigate(["/metas"]);
          return;
        }

        this.meta = {
          ...res,
          vendedorNome: res.vendedor?.nome || res.vendedorId
        };
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados da meta.");
        this.router.navigate(["/metas"]);
      }
    });
  }

  confirmDelete() {
    this.poDialog.confirm({
      title: "Confirmação de Exclusão",
      message: `Confirma a exclusão da meta do vendedor ${this.meta.vendedorNome}?`,
      confirm: () => {
        this.isLoading = true;
        this.metaService.delete(this.meta.id).pipe(
          finalize(() => this.isLoading = false)
        ).subscribe({
          next: () => {
            this.poNotification.success("Meta excluída com sucesso!");
            this.router.navigate(["/metas"]);
          },
          error: () => {
            this.poNotification.error("Erro ao excluir meta.");
          }
        });
      }
    });
  }

  edit() {
    this.router.navigate(["/metas/edit", this.meta.id]);
  }

  back() {
    this.router.navigate(["/metas"]);
  }
}
