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
import { BillingService } from "../../../services/billing";

@Component({
  selector: "app-comodato-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="Ativos em Comodato"
      p-subtitle="GestÃ£o de equipamentos sob custÃ³dia de clientes"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="pageActions">

      <po-table
        [p-columns]="columns"
        [p-items]="items"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        [p-loading-show-more]="loadingShowMore"
        [p-show-more-disabled]="!hasNext"
        (p-show-more)="showMore()"
        p-container="shadow"
        [p-striped]="true">
      </po-table>

    </po-page-list>
  `
})
export class ComodatoListComponent implements OnInit {
  private billingService = inject(BillingService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private allItems: Array<any> = [];

  items: Array<any> = [];
  isLoading: boolean = true;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Faturamento", link: "/faturamento/notas" },
      { label: "Comodatos" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "dtEmissao", label: "Data", type: "date", width: "120px" },
    { property: "notaFiscal", label: "Nota", width: "100px" },
    { property: "cliente.razao", label: "Cliente" },
    { property: "vendedor1.nome", label: "Vendedor" },
    { property: "vlrComodato", label: "Vlr Total Ativos", type: "currency", format: "BRL", width: "150px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Visualizar Nota", action: (row: any) => this.router.navigate(["/faturamento/notas", row.id]), icon: "po-icon-eye" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData() {
    this.paginaAtual = 1;
    this.isLoading = true;
    this.billingService.getComodatos().subscribe({
      next: (res) => {
        this.allItems = res || [];
        this.atualizarPaginaVisivel();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar ativos em comodato.");
      }
    });
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

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.items = this.allItems.slice(0, limite);
    this.hasNext = this.allItems.length > limite;
  }
}
