import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoTableColumn,
  PoTableAction,
  PoPageAction,
  PoPageFilter,
  PoNotificationService,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { NegociacaoService } from "../../../services/negociacao";

@Component({
  selector: "app-negociacao-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="CobranÃ§a e InadimplÃªncia"
      p-subtitle="Monitoramento de dÃ©bitos e negociaÃ§Ãµes"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="pageActions"
      [p-filter]="filter">

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
export class NegociacaoListComponent implements OnInit {
  private negociacaoService = inject(NegociacaoService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private filtroAtual = "";
  private allItems: Array<any> = [];

  items: Array<any> = [];
  isLoading: boolean = true;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Financeiro", link: "/clientes" },
      { label: "CobranÃ§a" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "Pesquisar cliente"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "cliente_nome", label: "RazÃ£o Social" },
    { property: "cod_erp", label: "CÃ³d. ERP", width: "100px" },
    { property: "qtd_titulos", label: "TÃ­tulos", type: "number", width: "100px" },
    { property: "total_vencido", label: "Valor Vencido", type: "currency", format: "BRL" },
    { property: "maior_atraso", label: "Maior Atraso (Dias)", type: "number", width: "150px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Nova NegociaÃ§Ã£o", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id], { queryParams: { tab: "cobranca" } }), icon: "po-icon-finance" },
    { label: "VisÃ£o 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "po-icon-eye" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filter: string = "") {
    this.filtroAtual = filter;
    this.paginaAtual = 1;
    this.isLoading = true;
    this.negociacaoService.getDelinquentClients().subscribe({
      next: (res) => {
        this.allItems = this.aplicarFiltroLocal(res || [], filter);
        this.atualizarPaginaVisivel();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar inadimplentes.");
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
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

  private aplicarFiltroLocal(items: Array<any>, filter: string): Array<any> {
    if (!filter) {
      return items;
    }

    const filtroNormalizado = filter.toLowerCase();
    return items.filter(item =>
      item.cliente_nome?.toLowerCase().includes(filtroNormalizado) ||
      item.cod_erp?.includes(filter)
    );
  }
}
