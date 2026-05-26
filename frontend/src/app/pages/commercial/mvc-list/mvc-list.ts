import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoModalComponent,
  PoBreadcrumb,
  PoNotificationService,
  PoSelectOption
} from "@po-ui/ng-components";
import {
  PoPageDynamicTableModule,
  PoPageDynamicTableCustomTableAction,
  PoPageDynamicTableCustomAction,
  PoPageDynamicTableField
} from "@po-ui/ng-templates";
import { FormsModule } from "@angular/forms";
import { AnalyticsService } from "../../../services/analytics";
import { AuthService } from "../../../services/auth";
import { VendedorService } from "../../../services/vendedor";
import { LocationService } from "../../../services/location";
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
  private locationService = inject(LocationService);
  private crmService = inject(CrmService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  summary: any = { goal: 0, realized: 0, achievement: 0 };
  isGerente: boolean = false;

  vendedores: Array<PoSelectOption> = [];
  estados: Array<PoSelectOption> = [];
  municipios: Array<PoSelectOption> = [];
  tiposAtendimento: Array<PoSelectOption> = [];
  fields: Array<PoPageDynamicTableField> = [];

  atendimento: any = {
    atendimentoTipoId: undefined,
    observacao: "",
    horarioInicial: new Date(),
    horarioFinal: new Date()
  };

  selectedCliente: any = {};

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Cockpit de Vendas (MCV)" }
    ]
  };

  serviceApi = `${environment.apiUrl}/analytics/mvc/table`;

  readonly tableCustomActions: Array<PoPageDynamicTableCustomTableAction> = [
    { label: "VisÃ£o 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "po-icon-eye" },
    { label: "Editar Cliente", action: (item: any) => this.router.navigate(["/clientes/edit", item.cliente_id]), icon: "po-icon-edit" },
    { label: "Novo Atendimento", action: (item: any) => this.openAtendimento(item), icon: "po-icon-chat" }
  ];

  readonly pageCustomActions: Array<PoPageDynamicTableCustomAction> = [
    { label: "Atualizar", action: () => this.refreshTable(), icon: "po-icon-refresh" },
    { label: "15 Dias", action: () => this.aplicarFiltroRapido(16, 30) },
    { label: "30 Dias", action: () => this.aplicarFiltroRapido(31, 60) },
    { label: "60 Dias", action: () => this.aplicarFiltroRapido(61, 90) },
    { label: "90 Dias", action: () => this.aplicarFiltroRapido(91, 120) },
    { label: "120 Dias", action: () => this.aplicarFiltroRapido(121, undefined) },
    { label: "Todos os Dias", action: () => this.aplicarFiltroRapido(undefined, undefined), icon: "po-icon-clear-content" }
  ];

  aplicarFiltroRapido(diasDe?: number, diasAte?: number) {
    let url = `${environment.apiUrl}/analytics/mvc/table`;
    const params: string[] = [];
    if (diasDe !== undefined) {
      params.push(`diasDe=${diasDe}`);
    }
    if (diasAte !== undefined) {
      params.push(`diasAte=${diasAte}`);
    }
    if (params.length > 0) {
      url += `?${params.join("&")}`;
    }
    this.serviceApi = url;

    if (diasDe !== undefined && diasAte !== undefined) {
      this.poNotification.information(`Filtrando inatividade entre ${diasDe} e ${diasAte} dias.`);
    } else if (diasDe !== undefined) {
      this.poNotification.information(`Filtrando inatividade acima de ${diasDe} dias.`);
    } else {
      this.poNotification.information("Exibindo todos os clientes (filtro rÃ¡pido limpo).");
    }

    this.refreshTable();
  }

  ngOnInit(): void {
    const user = this.authService.getUser();

    this.isGerente =
      user?.login === "admin" ||
      !!user?.roles?.includes("ADMIN") ||
      !!user?.roles?.includes("SUPERVISOR") ||
      !!user?.roles?.includes("GERENTE");

    this.rebuildFields();
    this.loadInitialData();
    this.loadKpis();
  }

  loadInitialData() {
    if (this.isGerente) {
      this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe(res => {
        this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
        this.rebuildFields();
      });
    }

    this.crmService.getTipos().subscribe(res => {
      this.tiposAtendimento = res.map((t: any) => ({ label: t.descricao, value: t.id }));
    });
  }

  private rebuildFields() {
    const fields: Array<PoPageDynamicTableField> = [
      {
        property: "financeiro_status", label: "$", type: "label", width: "80px", labels: [
          { value: "R", color: "color-07", label: "Atrasado" },
          { value: "B", color: "color-10", label: "Em dia" }
        ]
      },
      {
        property: "situacao", label: "SituaÃ§Ã£o", type: "label", width: "100px", labels: [
          { value: "A", color: "color-10", label: "Ativo" },
          { value: "B", color: "color-07", label: "Bloqueado" }
        ]
      },
      { property: "codigo", label: "CÃ³digo", width: "110px" },
      { property: "cliente_nome", label: "RazÃ£o Social", filter: true },
      { property: "fantasia", label: "Nome Fantasia", filter: true },
      { property: "difference", label: "Dif. MÃ©dia", type: "currency", format: "BRL", width: "140px" },
      { property: "venda_mes", label: "Venda 30d", type: "currency", format: "BRL", width: "130px" },
      { property: "average3Months", label: "MÃ©dia 90d", type: "currency", format: "BRL", width: "130px" },
      { property: "dias", label: "Dias", type: "number", width: "110px", filter: true }
    ];

    if (this.isGerente) {
      fields.push({
        property: "vendedor_reduzido",
        label: "Vendedor",
        width: "150px"
      });

      fields.push({
        property: "vendedor_id",
        label: "Vendedor",
        filter: true,
        options: [...this.vendedores]
      });
    }

    this.fields = fields;
  }

  loadKpis(year?: number, month?: number) {
    const y = year || new Date().getFullYear();
    const m = month || new Date().getMonth() + 1;
    this.analyticsService.getDashboardData(y, m).subscribe(res => {
      this.summary = res.summary;
    });
  }

  refreshTable() {
    if (this.dynamicTable) {
      this.dynamicTable.updateDataTable();
    }
    this.loadKpis();
  }

  openAtendimento(item: any) {
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
      this.poNotification.warning("Preencha o tipo e a observaÃ§Ã£o.");
      return;
    }
    this.crmService.save(this.atendimento).subscribe({
      next: () => {
        this.poNotification.success("Atendimento registrado com sucesso!");
        this.modalAtendimento.close();
        this.refreshTable();
      },
      error: () => {
        this.poNotification.error("Erro ao registrar atendimento.");
      }
    });
  }
}
