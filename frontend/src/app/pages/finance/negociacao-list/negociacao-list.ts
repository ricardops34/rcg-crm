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
      p-title="Cobrança e Inadimplência"
      p-subtitle="Monitoramento de débitos e negociações"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="pageActions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="items"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
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

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Financeiro", link: "/clientes" },
      { label: "Cobrança" }
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
    { property: "cliente_nome", label: "Razão Social" },
    { property: "cod_erp", label: "Cód. ERP", width: "100px" },
    { property: "qtd_titulos", label: "Títulos", type: "number", width: "100px" },
    { property: "total_vencido", label: "Valor Vencido", type: "currency", format: "BRL" },
    { property: "maior_atraso", label: "Maior Atraso (Dias)", type: "number", width: "150px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Nova Negociação", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id], { queryParams: { tab: 'cobranca' } }), icon: "po-icon-finance" },
    { label: "Visão 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "po-icon-eye" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filter?: string) {
    this.isLoading = true;
    this.negociacaoService.getDelinquentClients().subscribe({
      next: (res) => {
        this.items = res;
        if (filter) {
          this.items = this.items.filter(item => 
            item.cliente_nome?.toLowerCase().includes(filter.toLowerCase()) ||
            item.cod_erp?.includes(filter)
          );
        }
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
}
