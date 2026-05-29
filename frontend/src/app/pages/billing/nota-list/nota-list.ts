import { Component, OnInit, inject, ViewChild } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoTableColumn,
  PoTableAction,
  PoPageAction,
  PoPageFilter,
  PoNotificationService,
  PoBreadcrumb,
  PoTableComponent
} from "@po-ui/ng-components";
import { BillingService } from "../../../services/billing";

@Component({
  selector: "app-nota-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="Notas Fiscais de SaÃ­da"
      p-subtitle="Consulta de faturamento e documentos fiscais"
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
        [p-sort]="true"
        [p-selectable]="true">
      </po-table>

    </po-page-list>
  `
})
export class NotaListComponent implements OnInit {
  private billingService = inject(BillingService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private filtroAtual = "";
  private allItems: Array<any> = [];

  @ViewChild(PoTableComponent, { static: false }) poTable!: PoTableComponent;

  items: Array<any> = [];
  isLoading: boolean = true;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Faturamento" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Gerar Etiquetas", action: this.printSelectedLabels.bind(this), icon: "an an-printer" },
    { label: "Consultar Comodatos", action: () => this.router.navigate(["/faturamento/comodatos"]), icon: "an an-package" },
    { label: "Atualizar", action: () => this.loadData(), icon: "an an-arrows-clockwise" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "NÃºmero da nota"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "notaFiscal", label: "NÃºmero", width: "120px" },
    { property: "serieFiscal", label: "SÃ©rie", width: "80px" },
    { property: "dtEmissao", label: "EmissÃ£o", type: "date", width: "120px" },
    { property: "cliente.razao", label: "Cliente" },
    { property: "vlr_liquido", label: "Vlr LÃ­quido", type: "currency", format: "BRL", width: "150px" },
    { property: "chaveNfe", label: "Chave de Acesso", width: "250px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Visualizar DANFE", action: (row: any) => this.billingService.viewDanfe(row.id), icon: "an an-file-text" },
    { label: "Download XML", action: (row: any) => this.billingService.downloadXml(row.id), icon: "an an-download-simple" },
    { label: "Detalhes da Nota", action: (row: any) => this.router.navigate(["/faturamento/notas", row.id]), icon: "an an-eye" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(nota: string = "") {
    this.filtroAtual = nota;
    this.paginaAtual = 1;
    this.isLoading = true;

    this.billingService.findAll({ nota }).subscribe({
      next: (res) => {
        this.allItems = res.items || [];
        this.atualizarPaginaVisivel();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar notas fiscais.");
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

  printSelectedLabels() {
    const selectedItems = this.poTable.getSelectedRows();
    if (selectedItems.length === 0) {
      this.poNotification.warning("Selecione ao menos uma nota para gerar etiquetas.");
      return;
    }
    this.poNotification.information(`Gerando ${selectedItems.length} etiquetas de despacho...`);
    selectedItems.forEach(item => {
      console.log(`Etiqueta gerada para Nota: ${item.notaFiscal}`);
    });
  }

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.items = this.allItems.slice(0, limite);
    this.hasNext = this.allItems.length > limite;
  }
}
