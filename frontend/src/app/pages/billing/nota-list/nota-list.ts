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
      p-title="Notas Fiscais de Saída"
      p-subtitle="Consulta de faturamento e documentos fiscais"
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

  @ViewChild(PoTableComponent, { static: false }) poTable!: PoTableComponent;

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Faturamento" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Gerar Etiquetas", action: this.printSelectedLabels.bind(this), icon: "po-icon-print" },
    { label: "Consultar Comodatos", action: () => this.router.navigate(["/faturamento/comodatos"]), icon: "po-icon-box" },
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "Número da nota"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "notaFiscal", label: "Número", width: "120px" },
    { property: "serieFiscal", label: "Série", width: "80px" },
    { property: "dtEmissao", label: "Emissão", type: "date", width: "120px" },
    { property: "cliente.razao", label: "Cliente" },
    { property: "vlr_liquido", label: "Vlr Líquido", type: "currency", format: "BRL", width: "150px" },
    { property: "chaveNfe", label: "Chave de Acesso", width: "250px" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Visualizar DANFE", action: (row: any) => this.billingService.viewDanfe(row.id), icon: "po-icon-document" },
    { label: "Download XML", action: (row: any) => this.billingService.downloadXml(row.id), icon: "po-icon-download" },
    { label: "Detalhes da Nota", action: (row: any) => this.router.navigate(["/faturamento/notas", row.id]), icon: "po-icon-eye" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(nota?: string) {
    this.isLoading = true;
    this.billingService.findAll({ nota }).subscribe({
      next: (res) => {
        this.items = res.items;
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

  printSelectedLabels() {
    const selectedItems = this.poTable.getSelectedRows();
    if (selectedItems.length === 0) {
      this.poNotification.warning("Selecione ao menos uma nota para gerar etiquetas.");
      return;
    }
    this.poNotification.information(`Gerando ${selectedItems.length} etiquetas de despacho...`);
    // Simulação de geração de etiquetas
    selectedItems.forEach(item => {
      console.log(`Etiqueta gerada para Nota: ${item.notaFiscal}`);
    });
  }
}
