import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoTableColumn, 
  PoNotificationService,
  PoPageAction,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { BillingService } from "../../../services/billing";

@Component({
  selector: "app-nota-detail",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-default 
      [p-title]="'Nota Fiscal: ' + (nota.notaFiscal || '')"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions">
      
      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <div class="po-row">
        <po-info class="po-md-3" p-label="Número / Série" [p-value]="(nota.notaFiscal || '') + ' - ' + (nota.serieFiscal || '')"></po-info>
        <po-info class="po-md-3" p-label="Data Emissão" [p-value]="(nota.dtEmissao | date:'dd/MM/yyyy') || ''"></po-info>
        <po-info class="po-md-6" p-label="Chave de Acesso" [p-value]="nota.chaveNfe || ''"></po-info>
      </div>

      <po-divider p-label="Cliente e Valores"></po-divider>
      <div class="po-row">
        <po-info class="po-md-6" p-label="Razão Social" [p-value]="nota.cliente?.razao || ''"></po-info>
        <po-info class="po-md-3" p-label="Vlr Mercadoria" [p-value]="(nota.vlrMercadoria | currency:'BRL') || ''"></po-info>
        <po-info class="po-md-3" p-label="Vlr Líquido" [p-value]="(nota.vlr_liquido | currency:'BRL') || ''"></po-info>
      </div>

      <po-divider p-label="Itens da Nota"></po-divider>
      <po-table 
        [p-columns]="itemColumns" 
        [p-items]="nota.itens"
        p-container="shadow">
      </po-table>

    </po-page-default>
  `
})
export class NotaDetailComponent implements OnInit {
  private route = inject(ActivatedRoute);
  private router = inject(Router);
  private billingService = inject(BillingService);
  private poNotification = inject(PoNotificationService);

  nota: any = {};
  isLoading: boolean = true;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Notas Fiscais", link: "/faturamento/notas" },
      { label: "Detalhamento" }
    ]
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Visualizar DANFE", action: () => this.billingService.viewDanfe(this.nota.id), icon: "po-icon-document" },
    { label: "Download XML", action: () => this.billingService.downloadXml(this.nota.id), icon: "po-icon-download" },
    { label: "Voltar", action: () => this.router.navigate(["/faturamento/notas"]), icon: "po-icon-arrow-left" }
  ];

  readonly itemColumns: Array<PoTableColumn> = [
    { property: "produto.codErp", label: "Cód. ERP", width: "120px" },
    { property: "produto.descricao", label: "Produto" },
    { property: "qtd", label: "Qtd", type: "number", width: "80px" },
    { property: "vlrUnitario", label: "Unitário", type: "currency", format: "BRL", width: "120px" },
    { property: "vlrTotal", label: "Total", type: "currency", format: "BRL", width: "120px" },
    { property: "percIcms", label: "% ICMS", type: "number", width: "80px" },
    { property: "vlrIcms", label: "Vlr ICMS", type: "currency", format: "BRL", width: "120px" }
  ];

  ngOnInit() {
    const id = this.route.snapshot.params["id"];
    if (id) {
      this.loadNota(id);
    }
  }

  loadNota(id: number) {
    this.isLoading = true;
    this.billingService.findOne(id).subscribe({
      next: (res) => {
        this.nota = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar detalhes da nota.");
      }
    });
  }
}
