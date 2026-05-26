import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import {
  PoModule,
  PoTableColumn,
  PoNotificationService,
  PoPageAction,
  PoModalComponent
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { catchError, finalize, forkJoin, of } from "rxjs";
import { ClienteService } from "../../../services/cliente";
import { NegociacaoService } from "../../../services/negociacao";

@Component({
  selector: "app-cliente-360",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./cliente-360.html"
})
export class Cliente360Component implements OnInit {
  @ViewChild("modalNegociacao", { static: true }) modalNegociacao!: PoModalComponent;

  private route = inject(ActivatedRoute);
  private router = inject(Router);
  private clienteService = inject(ClienteService);
  private negociacaoService = inject(NegociacaoService);
  private poNotification = inject(PoNotificationService);

  cliente: any = {};
  comodatoItems: Array<any> = [];
  mixItems: Array<any> = [];
  financeiroItems: Array<any> = [];
  notasItems: Array<any> = [];
  atendimentosItems: Array<any> = [];
  estoqueItems: Array<any> = [];
  overdueTitles: Array<any> = [];
  selectedTitles: Array<any> = [];

  isLoading = true;
  activeTab = "estoque";

  negociacao: any = {
    observacao: ""
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Voltar", action: this.close.bind(this), icon: "po-icon-arrow-left" },
    { label: "Atualizar", action: () => this.ngOnInit(), icon: "po-icon-refresh" }
  ];

  readonly estoqueColumns: Array<PoTableColumn> = [
    { property: "produto_nome", label: "Produto" },
    { property: "um", label: "UM", width: "70px" },
    { property: "qtd_embalagem", label: "Emb.", type: "number", format: "1.0-2", width: "90px" },
    { property: "data_ultima_compra", label: "Última Compra", type: "date" },
    { property: "qtd_ultima_compra", label: "Qtd Últ. Compra", type: "number", format: "1.0-2" },
    { property: "consumo_medio_dia", label: "Consumo/Dia", type: "number", format: "1.0-4" },
    { property: "intervalo_medio_dias", label: "Intervalo Médio", type: "number", format: "1.0-2" },
    { property: "estoque_estimado", label: "Estoque Estimado", type: "number", format: "1.0-2" },
    { property: "cobertura_dias", label: "Cobertura (Dias)", type: "number", format: "1.0-1" },
    { property: "data_estimada_necessidade", label: "Necessidade Estimada", type: "date" },
    { property: "qtd_sugerida", label: "Qtd. Sugerida", type: "number", format: "1.0-2" }
  ];

  readonly overdueColumns: Array<PoTableColumn> = [
    { property: "numero", label: "Número" },
    { property: "parcela", label: "Parc." },
    { property: "venc_real", label: "Vencimento", type: "date" },
    { property: "saldo", label: "Saldo", type: "currency", format: "BRL" },
    { property: "valor", label: "Valor Original", type: "currency", format: "BRL" }
  ];

  readonly comodatoColumns: Array<PoTableColumn> = [
    { property: "nota_fiscal", label: "Nota Fiscal" },
    { property: "serie_fiscal", label: "Série" },
    { property: "dt_emissao", label: "Emissão", type: "date" },
    { property: "vlr_mercadoria", label: "Valor", type: "currency", format: "BRL" }
  ];

  readonly mixColumns: Array<PoTableColumn> = [
    { property: "produto_nome", label: "Produto" },
    { property: "total_qtd", label: "Qtd Total", type: "number" },
    { property: "ultima_compra", label: "Última Compra", type: "date" },
    { property: "total_valor", label: "Valor Total", type: "currency", format: "BRL" }
  ];

  readonly financeiroColumns: Array<PoTableColumn> = [
    { property: "numero", label: "Número" },
    { property: "parcela", label: "Parcela" },
    { property: "vencimento", label: "Vencimento", type: "date" },
    { property: "saldo", label: "Saldo", type: "currency", format: "BRL" },
    {
      property: "status",
      label: "Status",
      type: "label",
      labels: [
        { value: "Vencido", color: "color-07", label: "Vencido" },
        { value: "Vencendo", color: "color-08", label: "Vencendo" },
        { value: "A Vencer", color: "color-11", label: "A Vencer" }
      ]
    }
  ];

  readonly notasColumns: Array<PoTableColumn> = [
    { property: "nota_fiscal", label: "Nota" },
    { property: "dt_emissao", label: "Emissão", type: "date" },
    { property: "vlr_bruto", label: "Vlr Bruto", type: "currency", format: "BRL" },
    { property: "vlr_liquido", label: "Vlr Líquido", type: "currency", format: "BRL" },
    { property: "vendedor_nome", label: "Vendedor" }
  ];

  readonly atendimentosColumns: Array<PoTableColumn> = [
    { property: "dt_atendimento", label: "Data", type: "date" },
    { property: "tipo_atendimento", label: "Tipo" },
    { property: "vendedor_nome", label: "Vendedor" },
    { property: "observacao", label: "Observação" }
  ];

  ngOnInit() {
    const id = this.route.snapshot.params["id"];
    this.route.queryParams.subscribe(params => {
      if (params["tab"]) {
        this.activeTab = params["tab"];
      }
    });

    if (id) {
      this.loadCliente(id);
    }
  }

  loadCliente(id: number) {
    this.isLoading = true;
    this.clienteService.findOne(id).subscribe({
      next: (res) => {
        this.cliente = res;
        this.loadAllDetails(id);
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados do cliente.");
        this.isLoading = false;
      }
    });
  }

  loadAllDetails(id: number) {
    const fallback = (label: string) => {
      console.error(`[360-DEBUG][FRONT] erro ao carregar ${label}`);
      return of([]);
    };

    forkJoin({
      comodato: this.clienteService.getComodato(id).pipe(catchError(() => fallback("comodato"))),
      mix: this.clienteService.getMix(id).pipe(catchError(() => fallback("mix"))),
      financeiro: this.clienteService.getFinanceiro(id).pipe(catchError(() => fallback("financeiro"))),
      notas: this.clienteService.getNotas(id).pipe(catchError(() => fallback("notas"))),
      estoque: this.clienteService.getEstoqueEstimado(id).pipe(catchError(() => fallback("estoque estimado"))),
      atendimentos: this.clienteService.getAtendimentos(id).pipe(catchError(() => fallback("atendimentos"))),
      overdueTitles: this.negociacaoService.getOverdueTitles(id).pipe(catchError(() => fallback("cobrança")))
    }).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe((res: any) => {
      this.comodatoItems = res.comodato;
      this.mixItems = res.mix;
      this.financeiroItems = res.financeiro;
      this.notasItems = res.notas;
      this.atendimentosItems = res.atendimentos;
      this.overdueTitles = res.overdueTitles;
      this.selectedTitles = [...res.overdueTitles];
      this.estoqueItems = res.estoque;
    });
  }

  openNegociacao() {
    if (this.overdueTitles.length === 0) {
      this.poNotification.warning("Cliente não possui títulos vencidos para negociação.");
      return;
    }

    this.selectedTitles = [...this.overdueTitles];
    this.negociacao.observacao = "";
    this.modalNegociacao.open();
  }

  confirmNegociacao() {
    if (this.selectedTitles.length < this.overdueTitles.length) {
      this.poNotification.error("É obrigatório selecionar TODOS os títulos vencidos para gerar a negociação.");
      return;
    }

    if (!this.negociacao.observacao) {
      this.poNotification.warning("Informe uma observação para a negociação.");
      return;
    }

    this.isLoading = true;
    const payload = {
      clienteId: this.cliente.id,
      observacao: this.negociacao.observacao,
      tituloIds: this.selectedTitles.map(t => t.id)
    };

    this.negociacaoService.create(payload).subscribe({
      next: () => {
        this.poNotification.success("Negociação gerada com sucesso!");
        this.modalNegociacao.close();
        this.loadAllDetails(this.cliente.id);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error(err.error?.message || "Erro ao gerar negociação.");
      }
    });
  }

  close() {
    this.router.navigate(["/mvc"]);
  }
}
