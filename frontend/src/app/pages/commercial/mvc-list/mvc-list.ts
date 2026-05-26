import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoDisclaimer,
  PoModule,
  PoModalComponent,
  PoNotificationService,
  PoSelectOption
} from "@po-ui/ng-components";
import {
  PoPageDynamicTableCustomAction,
  PoPageDynamicTableCustomTableAction,
  PoPageDynamicTableField,
  PoPageDynamicTableModule
} from "@po-ui/ng-templates";
import { FormsModule } from "@angular/forms";
import { AnalyticsService } from "../../../services/analytics";
import { AuthService } from "../../../services/auth";
import { VendedorService } from "../../../services/vendedor";
import { CrmService } from "../../../services/crm";
import { environment } from "../../../../environments/environment";

@Component({
  selector: "app-mvc-list",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule, PoPageDynamicTableModule],
  templateUrl: "./mvc-list.html"
})
export class MvcListComponent implements OnInit {
  @ViewChild("modalAtendimento", { static: true }) modalAtendimento!: PoModalComponent;
  @ViewChild("dynamicTable") dynamicTable!: any;

  private analyticsService = inject(AnalyticsService);
  private authService = inject(AuthService);
  private vendedorService = inject(VendedorService);
  private crmService = inject(CrmService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  summary: any = { goal: 0, realized: 0, achievement: 0 };
  isGerente = false;

  vendedores: Array<PoSelectOption> = [];
  tiposAtendimento: Array<PoSelectOption> = [];
  fields: Array<PoPageDynamicTableField> = [];
  quickFilterDisclaimers: Array<PoDisclaimer> = [];

  atendimento: any = {
    atendimentoTipoId: undefined,
    observacao: "",
    horarioInicial: new Date(),
    horarioFinal: new Date()
  };

  selectedCliente: any = {};
  private readonly serviceApiBase = `${environment.apiUrl}/analytics/mvc/table`;
  private quickFilterParams: Record<string, any> = {};

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Cockpit de Vendas (MCV)" }
    ]
  };

  serviceApi = this.serviceApiBase;

  readonly tableCustomActions: Array<PoPageDynamicTableCustomTableAction> = [
    { label: "Visão 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "po-icon-eye" },
    { label: "Novo Atendimento", action: (item: any) => this.openAtendimento(item), icon: "po-icon-chat" }
  ];

  readonly pageCustomActions: Array<PoPageDynamicTableCustomAction> = [
    { label: "Atualizar", action: () => this.refreshTable(), icon: "po-icon-refresh" },
    { label: "16 a 30 Dias", action: () => this.aplicarFiltroRapido(16, 30) },
    { label: "31 a 60 Dias", action: () => this.aplicarFiltroRapido(31, 60) },
    { label: "61 a 90 Dias", action: () => this.aplicarFiltroRapido(61, 90) },
    { label: "91 a 120 Dias", action: () => this.aplicarFiltroRapido(91, 120) },
    { label: "Acima de 120 Dias", action: () => this.aplicarFiltroRapido(121) },
    { label: "Todos os Dias", action: () => this.aplicarFiltroRapido(), icon: "po-icon-clear-content" }
  ];

  ngOnInit(): void {
    const user = this.authService.getUser();

    this.isGerente =
      user?.login === "admin" ||
      !!user?.roles?.includes("ADMIN") ||
      !!user?.roles?.includes("SUPERVISOR") ||
      !!user?.roles?.includes("GERENTE");

    console.log("[MVC-DEBUG][FRONT] ngOnInit", {
      user,
      isGerente: this.isGerente,
      serviceApiBase: this.serviceApiBase,
      serviceApi: this.serviceApi,
      quickFilterParams: this.quickFilterParams,
      // DIAGNÓSTICO: o PO-UI chama o serviceApi sem parâmetros extras na carga inicial.
      // Se aparecer dados errados, o problema está na query do backend sem filtros.
      // Se aparecer encoding errado, o problema está no banco/conexão.
    });

    this.rebuildFields();
    this.loadInitialData();
    this.loadKpis();
  }

  aplicarFiltroRapido(diasDe?: number, diasAte?: number) {
    this.quickFilterParams = {};
    this.quickFilterDisclaimers = [];

    if (diasDe !== undefined) {
      this.quickFilterParams["diasDe"] = diasDe;
    }

    if (diasAte !== undefined) {
      this.quickFilterParams["diasAte"] = diasAte;
    }

    console.log("[MVC-DEBUG][FRONT] aplicarFiltroRapido", {
      diasDe,
      diasAte,
      quickFilterParams: this.quickFilterParams
    });

    if (diasDe !== undefined && diasAte !== undefined) {
      this.quickFilterDisclaimers = [
        {
          label: "Inatividade",
          property: "diasFaixa",
          value: `${diasDe} a ${diasAte} dias`
        }
      ];
      this.poNotification.information(`Filtrando inatividade entre ${diasDe} e ${diasAte} dias.`);
    } else if (diasDe !== undefined) {
      this.quickFilterDisclaimers = [
        {
          label: "Inatividade",
          property: "diasFaixa",
          value: `Acima de ${diasDe} dias`
        }
      ];
      this.poNotification.information(`Filtrando inatividade acima de ${diasDe} dias.`);
    } else {
      this.poNotification.information("Exibindo todos os clientes.");
    }

    this.refreshTable();
  }

  loadInitialData() {
    if (this.isGerente) {
      this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe({
        next: (res) => {
          this.vendedores = Array.isArray(res?.items)
            ? res.items.map((vendedor: any) => ({ label: vendedor.nome, value: vendedor.id }))
            : [];
          console.log("[MVC-DEBUG][FRONT] vendedores carregados", {
            total: this.vendedores.length,
            itens: this.vendedores
          });
          this.rebuildFields();
        },
        error: () => {
          this.vendedores = [];
          console.error("[MVC-DEBUG][FRONT] erro ao carregar vendedores");
          this.poNotification.error("Erro ao carregar vendedores do filtro.");
        }
      });
    }

    this.crmService.getTipos().subscribe({
      next: (res) => {
        this.tiposAtendimento = Array.isArray(res)
          ? res.map((tipo: any) => ({ label: tipo.descricao, value: tipo.id }))
          : [];
        console.log("[MVC-DEBUG][FRONT] tipos atendimento carregados", {
          total: this.tiposAtendimento.length
        });
      },
      error: () => {
        this.tiposAtendimento = [];
        console.error("[MVC-DEBUG][FRONT] erro ao carregar tipos de atendimento");
        this.poNotification.error("Erro ao carregar tipos de atendimento.");
      }
    });
  }

  private rebuildFields() {
    const fields: Array<PoPageDynamicTableField> = [
      { property: "cliente_id", key: true, visible: false },
      {
        property: "financeiro_status",
        label: "Fin.",
        type: "label",
        width: "60px",
        labels: [
          {
            value: "R",
            color: "color-07",
            label: " ",
            icon: "po-icon-warning",
            tooltip: "Possui títulos VENCIDOS"
          },
          {
            value: "B",
            color: "color-10",
            label: " ",
            icon: "po-icon-ok",
            tooltip: "Títulos em dia"
          }
        ]
      },
      {
        property: "situacao",
        label: "Situação",
        type: "label",
        width: "100px",
        labels: [
          { value: "A", color: "color-10", label: "Ativo" },
          { value: "B", color: "color-07", label: "Bloqueado" }
        ]
      },
      {
        property: "tem_comodato",
        label: "Comodato",
        type: "label",
        width: "110px",
        labels: [
          { value: "S", color: "color-11", label: "Sim" },
          { value: "N", color: "color-01", label: "Não" }
        ]
      },
      { property: "codigo", label: "Código", width: "110px" },
      { property: "cliente_nome", label: "Razão Social", filter: true },
      { property: "fantasia", label: "Nome Fantasia", filter: true },
      { property: "difference", label: "Dif. Média", type: "currency", format: "BRL", width: "140px" },
      { property: "venda_mes", label: "Venda 30d", type: "currency", format: "BRL", width: "130px" },
      { property: "average3Months", label: "Média 90d", type: "currency", format: "BRL", width: "130px" },
      { property: "dias", label: "Dias", type: "number", width: "110px", filter: true }
    ];

    if (this.isGerente) {
      fields.push({
        property: "vendedor_reduzido",
        label: "Vendedor",
        width: "150px"
      });
    }

    this.fields = fields;
    console.log("[MVC-DEBUG][FRONT] fields reconstruidos", this.fields);
  }

  loadKpis(year?: number, month?: number) {
    const selectedYear = year || new Date().getFullYear();
    const selectedMonth = month || new Date().getMonth() + 1;
    this.analyticsService.getDashboardData(selectedYear, selectedMonth).subscribe(res => {
      this.summary = res.summary;
      console.log("[MVC-DEBUG][FRONT] KPIs carregados", {
        year: selectedYear,
        month: selectedMonth,
        summary: this.summary
      });
    });
  }

  refreshTable() {
    if (this.dynamicTable) {
      const filtrosAtuais = { ...((this.dynamicTable as any).params || {}) };
      delete filtrosAtuais["diasDe"];
      delete filtrosAtuais["diasAte"];

      const filtrosCompletos = {
        page: 1,
        ...filtrosAtuais,
        ...this.quickFilterParams
      };

      // Montar a URL completa que será chamada para diagnóstico
      const urlParams = new URLSearchParams();
      Object.entries(filtrosCompletos).forEach(([k, v]) => {
        if (v !== undefined && v !== null) urlParams.set(k, String(v));
      });
      const urlDiag = `${this.serviceApi}?${urlParams.toString()}`;

      (this.dynamicTable as any).params = { ...filtrosCompletos };
      console.log("[MVC-DEBUG][FRONT] refreshTable", {
        filtrosAtuais,
        quickFilterParams: this.quickFilterParams,
        filtrosCompletos,
        serviceApi: this.serviceApi,
        urlDiagnostico: urlDiag,
      });
      this.dynamicTable.updateDataTable(filtrosCompletos);
    } else {
      console.warn("[MVC-DEBUG][FRONT] refreshTable sem dynamicTable");
    }

    this.loadKpis();
  }

  removeQuickFilter(disclaimer: PoDisclaimer) {
    console.log("[MVC-DEBUG][FRONT] removeQuickFilter", disclaimer);
    if (disclaimer.property === "diasFaixa") {
      this.quickFilterParams = {};
      this.quickFilterDisclaimers = [];
      this.refreshTable();
    }
  }

  clearQuickFilters() {
    console.log("[MVC-DEBUG][FRONT] clearQuickFilters");
    this.quickFilterParams = {};
    this.quickFilterDisclaimers = [];
    this.refreshTable();
  }

  openAtendimento(item: any) {
    console.log("[MVC-DEBUG][FRONT] openAtendimento", item);
    this.selectedCliente = item;
    this.atendimento = {
      clienteId: item.cliente_id,
      atendimentoTipoId: undefined,
      observacao: "",
      horarioInicial: new Date(),
      horarioFinal: new Date()
    };
    this.modalAtendimento.open();
  }

  saveAtendimento() {
    if (!this.atendimento.atendimentoTipoId || !this.atendimento.observacao) {
      this.poNotification.warning("Preencha o tipo e a observação.");
      return;
    }

    console.log("[MVC-DEBUG][FRONT] saveAtendimento payload", this.atendimento);
    this.crmService.save(this.atendimento).subscribe({
      next: () => {
        console.log("[MVC-DEBUG][FRONT] atendimento salvo com sucesso");
        this.poNotification.success("Atendimento registrado com sucesso!");
        this.modalAtendimento.close();
        this.refreshTable();
      },
      error: () => {
        console.error("[MVC-DEBUG][FRONT] erro ao salvar atendimento");
        this.poNotification.error("Erro ao registrar atendimento.");
      }
    });
  }
}
