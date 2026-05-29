import { Component, OnInit, OnDestroy, ViewChild, inject } from "@angular/core";
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
import { Subject, catchError, finalize, of, takeUntil } from "rxjs";
import { ClienteService } from "../../../services/cliente";
import { NegociacaoService } from "../../../services/negociacao";

@Component({
  selector: "app-cliente-360",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./cliente-360.html"
})
export class Cliente360Component implements OnInit, OnDestroy {
  @ViewChild("modalNegociacao", { static: true }) modalNegociacao!: PoModalComponent;

  private route = inject(ActivatedRoute);
  private router = inject(Router);
  private clienteService = inject(ClienteService);
  private negociacaoService = inject(NegociacaoService);
  private poNotification = inject(PoNotificationService);
  private destroy$ = new Subject<void>();

  cliente: any = {};
  venda30d: number | null = null;
  comodatoItems: Array<any> = [];
  mixItems: Array<any> = [];
  financeiroItems: Array<any> = [];
  notasItems: Array<any> = [];
  atendimentosItems: Array<any> = [];
  estoqueItems: Array<any> = [];
  overdueTitles: Array<any> = [];
  selectedTitles: Array<any> = [];

  isLoadingCliente = false;
  isLoadingNegociacao = false;
  // Loading por aba — alimenta [p-loading] em cada po-table
  tabLoading: Record<string, boolean> = {};
  // Previne requests duplicados ao trocar de aba rapidamente
  private loadingTabs: Record<string, boolean> = {};

  activeTab = "cadastro";
  private clienteId?: number;
  private loadedTabs: Record<string, boolean> = {};
  notasHasNext = false;
  notasMonthsOffset = 0;
  readonly notasMonthsWindow = 12;
  isLoadingMoreNotas = false;

  negociacao: any = { observacao: "" };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Voltar", action: this.close.bind(this), icon: "an an-arrow-left" },
    { label: "Atualizar", action: () => this.reloadCurrentTab(), icon: "an an-arrows-clockwise" }
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
    // snapshot evita subscription não encerrada em queryParams
    const id = this.route.snapshot.params["id"];
    const tabParam = this.route.snapshot.queryParams["tab"];
    if (tabParam) {
      this.activeTab = tabParam;
    }
    if (id) {
      this.clienteId = Number(id);
      this.loadCliente(id);
    }
  }

  ngOnDestroy() {
    this.destroy$.next();
    this.destroy$.complete();
  }

  loadCliente(id: number) {
    this.clienteService.findOne(id).pipe(takeUntil(this.destroy$)).subscribe({
      next: (res) => {
        this.cliente = res;
        this.loadedTabs["cadastro"] = true;
        // Carrega venda30d de forma assíncrona sem bloquear a tela
        this.clienteService.getVenda30d(id)
          .pipe(takeUntil(this.destroy$))
          .subscribe({ next: (r) => { this.venda30d = r.venda30d; } });
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados do cliente.");
      }
    });
  }

  onTabActivated(tab: string) {
    this.activeTab = tab;
    this.loadTab(tab);
  }

  reloadCurrentTab() {
    if (!this.clienteId) return;
    this.loadedTabs[this.activeTab] = false;
    this.loadingTabs[this.activeTab] = false;
    this.loadTab(this.activeTab);
  }

  private loadTab(tab: string) {
    // Não carrega se: sem ID, já carregado, ou já está em voo (previne request duplicado)
    if (!this.clienteId || this.loadedTabs[tab] || this.loadingTabs[tab]) {
      return;
    }

    const fallback = (label: string) => {
      this.poNotification.error(`Erro ao carregar ${label}.`);
      return of([]);
    };

    const startLoading = () => {
      this.loadingTabs[tab] = true;
      this.tabLoading[tab] = true;
    };

    const stopLoading = () => {
      this.tabLoading[tab] = false;
      this.loadingTabs[tab] = false;
    };

    const loaders: Record<string, () => void> = {
      estoque: () => {
        startLoading();
        this.clienteService.getEstoqueEstimado(this.clienteId!).pipe(
          catchError(() => fallback("estoque estimado")),
          finalize(stopLoading)
        ).subscribe((res: any) => {
          this.estoqueItems = res ?? [];
          this.loadedTabs[tab] = true;
        });
      },
      cobranca: () => {
        startLoading();
        this.negociacaoService.getOverdueTitles(this.clienteId!).pipe(
          catchError(() => fallback("cobrança")),
          finalize(stopLoading)
        ).subscribe((res: any) => {
          this.overdueTitles = res ?? [];
          this.selectedTitles = [...this.overdueTitles];
          this.loadedTabs[tab] = true;
        });
      },
      mix: () => {
        startLoading();
        this.clienteService.getMix(this.clienteId!).pipe(
          catchError(() => fallback("mix de produtos")),
          finalize(stopLoading)
        ).subscribe((res: any) => {
          this.mixItems = res ?? [];
          this.loadedTabs[tab] = true;
        });
      },
      financeiro: () => {
        startLoading();
        this.clienteService.getFinanceiro(this.clienteId!).pipe(
          catchError(() => fallback("financeiro")),
          finalize(stopLoading)
        ).subscribe((res: any) => {
          this.financeiroItems = res ?? [];
          this.loadedTabs[tab] = true;
        });
      },
      notas: () => {
        startLoading();
        this.notasMonthsOffset = 0;
        this.clienteService.getNotas(this.clienteId!, 0, this.notasMonthsWindow).pipe(
          catchError(() => fallback("notas fiscais")),
          finalize(stopLoading)
        ).subscribe((res: any) => {
          this.notasItems = res?.items ?? [];
          this.notasHasNext = Boolean(res?.hasNext);
          this.loadedTabs[tab] = true;
        });
      },
      comodato: () => {
        startLoading();
        this.clienteService.getComodato(this.clienteId!).pipe(
          catchError(() => fallback("comodato")),
          finalize(stopLoading)
        ).subscribe((res: any) => {
          this.comodatoItems = res ?? [];
          this.loadedTabs[tab] = true;
        });
      },
      atendimentos: () => {
        startLoading();
        this.clienteService.getAtendimentos(this.clienteId!).pipe(
          catchError(() => fallback("atendimentos")),
          finalize(stopLoading)
        ).subscribe((res: any) => {
          this.atendimentosItems = res ?? [];
          this.loadedTabs[tab] = true;
        });
      }
    };

    loaders[tab]?.();
  }

  loadMoreNotas() {
    if (!this.clienteId || !this.notasHasNext || this.isLoadingMoreNotas) return;

    this.isLoadingMoreNotas = true;
    const nextOffset = this.notasMonthsOffset + this.notasMonthsWindow;

    this.clienteService.getNotas(this.clienteId, nextOffset, this.notasMonthsWindow).pipe(
      finalize(() => { this.isLoadingMoreNotas = false; })
    ).subscribe({
      next: (res: any) => {
        this.notasMonthsOffset = nextOffset;
        this.notasItems = [...this.notasItems, ...(res?.items ?? [])];
        this.notasHasNext = Boolean(res?.hasNext);
      },
      error: () => {
        this.poNotification.error("Erro ao carregar mais notas fiscais.");
      }
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
    if (!this.negociacao.observacao?.trim()) {
      this.poNotification.warning("Informe uma observação para a negociação.");
      return;
    }

    this.isLoadingNegociacao = true;
    const payload = {
      clienteId: this.cliente.id,
      observacao: this.negociacao.observacao.trim(),
      tituloIds: this.selectedTitles.map((t: any) => t.id)
    };

    this.negociacaoService.create(payload).subscribe({
      next: () => {
        this.isLoadingNegociacao = false;
        this.poNotification.success("Negociação gerada com sucesso!");
        this.modalNegociacao.close();
        this.loadedTabs["cobranca"] = false;
        this.loadingTabs["cobranca"] = false;
        this.loadTab("cobranca");
      },
      error: (err: any) => {
        this.isLoadingNegociacao = false;
        this.poNotification.error(err.error?.message || "Erro ao gerar negociação.");
      }
    });
  }

  close() {
    this.router.navigate(["/mvc"]);
  }
}
