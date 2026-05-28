import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoTableColumn,
  PoTableAction,
  PoPageAction,
  PoNotificationService,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { TabelaPrecoService } from "../../../services/tabela-preco";

@Component({
  selector: "app-tabela-preco-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="Tabelas de Preços"
      p-subtitle="Gestão de listas de preços e vigências"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions">

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
export class TabelaPrecoListComponent implements OnInit {
  private tabelaService = inject(TabelaPrecoService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private paginaAtual = 1;
  private readonly itensPorPagina = 20;

  items: Array<any> = [];
  isLoading: boolean = true;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Comercial", link: "/clientes" },
      { label: "Tabelas de Preços" }
    ]
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Nova Tabela", action: () => this.router.navigate(["/tabelas-precos/new"]), icon: "po-icon-plus" },
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Visualizar", action: (row: any) => this.router.navigate(["/tabelas-precos/detail", row.id], { queryParams: { action: 'view' } }), icon: "po-icon-eye" },
    { label: "Editar", action: (row: any) => this.router.navigate(["/tabelas-precos/edit", row.id]), icon: "po-icon-edit" },
    { label: "Excluir", action: (row: any) => this.router.navigate(["/tabelas-precos/detail", row.id], { queryParams: { action: 'delete' } }), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "codErp", label: "Cód. ERP", width: "100px" },
    { property: "descricao", label: "Descrição" },
    { property: "dt_inicio", label: "Início", type: "date" },
    { property: "dt_fim", label: "Término", type: "date" },
    { property: "status", label: "Status", type: "label", labels: [
      { value: "A", color: "color-10", label: "Ativa" },
      { value: "I", color: "color-07", label: "Inativa" }
    ]}
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(append: boolean = false) {
    if (append) {
      this.loadingShowMore = true;
    } else {
      this.paginaAtual = 1;
      this.items = [];
      this.isLoading = true;
    }

    this.tabelaService.findAll(this.paginaAtual, this.itensPorPagina).subscribe({
      next: (res) => {
        this.items = append ? [...this.items, ...(res.items || [])] : (res.items || []);
        this.hasNext = Boolean((res as any)?.hasNext ?? (res?.total > this.paginaAtual * this.itensPorPagina));
        this.isLoading = false;
        this.loadingShowMore = false;
      },
      error: () => {
        this.isLoading = false;
        this.loadingShowMore = false;
        this.poNotification.error("Erro ao carregar tabelas de preços.");
      }
    });
  }

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.paginaAtual += 1;
    this.loadData(true);
  }

  remove(row: any) {
    if (!confirm(`Excluir a tabela de preço "${row.descricao}"?`)) {
      return;
    }

    this.isLoading = true;
    this.tabelaService.delete(row.id).subscribe({
      next: () => {
        this.poNotification.success("Tabela de preço excluída com sucesso!");
        this.loadData();
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao excluir tabela de preço.");
      }
    });
  }
}
