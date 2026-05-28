import { Component, OnInit, ViewChild, inject, signal, DestroyRef } from "@angular/core";
import { takeUntilDestroyed } from "@angular/core/rxjs-interop";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoDisclaimer,
  PoModalComponent,
  PoModule,
  PoNotificationService,
  PoSelectOption
} from "@po-ui/ng-components";
import {
  PoPageDynamicTableCustomAction,
  PoPageDynamicTableCustomTableAction,
  PoPageDynamicTableField,
  PoPageDynamicTableModule,
  PoPageDynamicTableComponent
} from "@po-ui/ng-templates";
import { FormsModule } from "@angular/forms";
import { AnalyticsService } from "../../../services/analytics";
import { AuthService } from "../../../services/auth";
import { VendedorService } from "../../../services/vendedor";
import { CrmService } from "../../../services/crm";
import { environment } from "../../../../environments/environment";

interface ISummaryKPI {
  goal: number;
  realized: number;
  achievement: number;
}

interface IAtendimento {
  clienteId?: number;
  atendimentoTipoId?: number;
  observacao: string;
  horarioInicial: Date;
  horarioFinal: Date;
  retorno?: Date;
  [key: string]: any;
}

@Component({
  selector: "app-mvc-list",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule, PoPageDynamicTableModule],
  templateUrl: "./mvc-list.html",
  styleUrl: "./mvc-list.css"
})
export class MvcListComponent implements OnInit {
  @ViewChild("modalAtendimento", { static: true }) modalAtendimento!: PoModalComponent;
  @ViewChild("dynamicTable") dynamicTable!: PoPageDynamicTableComponent;

  private analyticsService = inject(AnalyticsService);
  private authService = inject(AuthService);
  private vendedorService = inject(VendedorService);
  private crmService = inject(CrmService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);
  private destroyRef = inject(DestroyRef);

  summary = signal<ISummaryKPI>({ goal: 0, realized: 0, achievement: 0 });
  isGerente = signal<boolean>(false);

  vendedores = signal<Array<PoSelectOption>>([]);
  tiposAtendimento = signal<Array<PoSelectOption>>([]);
  fields = signal<Array<PoPageDynamicTableField>>([]);
  quickFilterDisclaimers = signal<Array<PoDisclaimer>>([]);

  atendimento = signal<IAtendimento>({
    atendimentoTipoId: undefined,
    observacao: "",
    horarioInicial: new Date(),
    horarioFinal: new Date()
  });

  selectedCliente = signal<any>({});
  readonly serviceApiBase = `${environment.apiUrl}/analytics/mvc/table`;
  private quickFilterParams: Record<string, any> = {};

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Cockpit de Vendas (MCV)" }
    ]
  };

  serviceApi = signal<string>(this.serviceApiBase);

  readonly tableCustomActions: Array<PoPageDynamicTableCustomTableAction> = [
    { label: "Visão 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "an an-eye" },
    { label: "Novo Atendimento", action: (item: any) => this.openAtendimento(item), icon: "an an-chats" }
  ];

  readonly pageCustomActions: Array<PoPageDynamicTableCustomAction> = [
    { label: "Atualizar", action: () => this.refreshTable(), icon: "an an-arrows-clockwise" },
    { label: "Inatividade: até 15 Dias", action: () => this.aplicarFiltroRapido(0, 15) },
    { label: "Inatividade: 16 a 30 Dias", action: () => this.aplicarFiltroRapido(16, 30) },
    { label: "Inatividade: 31 a 60 Dias", action: () => this.aplicarFiltroRapido(31, 60) },
    { label: "Inatividade: 61 a 90 Dias", action: () => this.aplicarFiltroRapido(61, 90) },
    { label: "Inatividade: 91 a 120 Dias", action: () => this.aplicarFiltroRapido(91, 120) },
    { label: "Inatividade: Acima de 120 Dias", action: () => this.aplicarFiltroRapido(121) },
    { label: "Inatividade: Todos", action: () => this.aplicarFiltroRapido() }
  ];

  ngOnInit(): void {
    const user = this.authService.getUser();

    this.isGerente.set(
      user?.login === "admin" ||
      !!user?.roles?.includes("ADMIN") ||
      !!user?.roles?.includes("SUPERVISOR") ||
      !!user?.roles?.includes("GERENTE")
    );

    this.rebuildFields();
    this.loadInitialData();
    this.loadKpis();
  }

  aplicarFiltroRapido(diasDe?: number, diasAte?: number) {
    this.quickFilterParams = {};
    
    if (diasDe !== undefined) {
      this.quickFilterParams["diasDe"] = diasDe;
    }

    if (diasAte !== undefined) {
      this.quickFilterParams["diasAte"] = diasAte;
    }

    if (diasDe !== undefined && diasAte !== undefined) {
      this.quickFilterDisclaimers.set([
        { label: "Inatividade", property: "diasFaixa", value: `${diasDe} a ${diasAte} dias` }
      ]);
      this.poNotification.information(`Filtrando inatividade entre ${diasDe} e ${diasAte} dias.`);
    } else if (diasDe !== undefined) {
      this.quickFilterDisclaimers.set([
        { label: "Inatividade", property: "diasFaixa", value: `Acima de ${diasDe} dias` }
      ]);
      this.poNotification.information(`Filtrando inatividade acima de ${diasDe} dias.`);
    } else {
      this.quickFilterDisclaimers.set([]);
      this.poNotification.information("Exibindo todos os clientes.");
    }

    this.refreshTable();
  }

  loadInitialData() {
    if (this.isGerente()) {
      this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" })
        .pipe(takeUntilDestroyed(this.destroyRef))
        .subscribe({
          next: (res) => {
            this.vendedores.set(Array.isArray(res?.items)
              ? res.items.map((vendedor: any) => ({ label: vendedor.nome, value: vendedor.id }))
              : []);
            this.rebuildFields();
          },
          error: () => {
            this.vendedores.set([]);
            this.poNotification.error("Erro ao carregar vendedores do filtro.");
          }
        });
    }

    this.crmService.getTipos()
      .pipe(takeUntilDestroyed(this.destroyRef))
      .subscribe({
        next: (res) => {
          this.tiposAtendimento.set(Array.isArray(res)
            ? res.map((tipo: any) => ({ label: tipo.descricao, value: tipo.id }))
            : []);
        },
        error: () => {
          this.tiposAtendimento.set([]);
          this.poNotification.error("Erro ao carregar tipos de atendimento.");
        }
      });
  }

  private rebuildFields() {
    const newFields: Array<PoPageDynamicTableField> = [
      { property: "cliente_id", key: true, visible: false },
      {
        property: "statusIcons",
        label: "Situação",
        type: "icon",
        width: "120px",
        sortable: false,
        icons: [
          { value: "SIT_A", color: "color-10", icon: "an an-lock-open", tooltip: "Cliente ATIVO" },
          { value: "SIT_B", color: "color-07", icon: "an an-lock", tooltip: "Cliente BLOQUEADO" },
          { value: "FIN_B", color: "color-07", icon: "an an-currency-dollar", tooltip: "Possui títulos VENCIDOS" },
          { value: "FIN_A", color: "color-02", icon: "an an-currency-dollar", tooltip: "Títulos a VENCER" },
          { value: "FIN_C", color: "color-10", icon: "an an-currency-dollar", tooltip: "Sem títulos pendentes" },
          { value: "COM_S", color: "color-08", icon: "an an-package", tooltip: "Possui COMODATO ativo" },
          { value: "COM_N", color: "color-04", icon: "an an-package", tooltip: "Sem comodato" }
        ]
      } as any,
      { property: "codigo", label: "Código", width: "110px" },
      { property: "cliente_nome", label: "Razão Social", filter: true },
      { property: "fantasia", label: "Nome Fantasia", filter: true },
      { 
        property: "difference", 
        label: "Dif. Média", 
        type: "currency", 
        format: "BRL", 
        width: "140px",
        color: (row: any) => row.difference < 0 ? 'color-07 negative-diff' : undefined
      } as any,
      { property: "venda_mes", label: "Venda 30d", type: "currency", format: "BRL", width: "130px" },
      { property: "average3Months", label: "Média 90d", type: "currency", format: "BRL", width: "130px" },
      { 
        property: "dias", 
        label: "Dias", 
        type: "number", 
        width: "110px", 
        filter: true,
        color: (row: any) => {
          if (row.dias > 180) return 'color-07';
          if (row.dias >= 90) return 'color-08';
          if (row.dias >= 60) return 'color-09';
          return 'color-10';
        }
      } as any
    ];

    if (this.isGerente()) {
      newFields.push({ property: "vendedor_reduzido", label: "Vendedor", width: "150px" });
    }

    this.fields.set(newFields);
  }

  loadKpis(year?: number, month?: number) {
    const selectedYear = year || new Date().getFullYear();
    const selectedMonth = month || new Date().getMonth() + 1;
    this.analyticsService.getDashboardData(selectedYear, selectedMonth)
      .pipe(takeUntilDestroyed(this.destroyRef))
      .subscribe(res => {
        this.summary.set(res.summary);
      });
  }

  refreshTable() {
    if (this.dynamicTable) {
      const dynTable: any = this.dynamicTable;
      const filtrosAtuais = { ...(dynTable.params || {}) };
      delete filtrosAtuais["diasDe"];
      delete filtrosAtuais["diasAte"];
      delete filtrosAtuais["page"];

      const filtrosBase = {
        ...filtrosAtuais,
        ...this.quickFilterParams
      };

      dynTable.params = { ...filtrosBase };
      if (typeof dynTable.updateDataTable === 'function') {
         dynTable.updateDataTable({ page: 1, ...filtrosBase });
      }
    }

    this.loadKpis();
  }

  removeQuickFilter(disclaimer: PoDisclaimer) {
    if (disclaimer.property === "diasFaixa") {
      this.quickFilterParams = {};
      this.quickFilterDisclaimers.set([]);
      this.refreshTable();
    }
  }

  clearQuickFilters() {
    this.quickFilterParams = {};
    this.quickFilterDisclaimers.set([]);
    this.refreshTable();
  }

  openAtendimento(item: any) {
    this.selectedCliente.set(item);
    this.atendimento.set({
      clienteId: item.cliente_id,
      atendimentoTipoId: undefined,
      observacao: "",
      horarioInicial: new Date(),
      horarioFinal: new Date()
    });
    this.modalAtendimento.open();
  }

  saveAtendimento() {
    const currentAtendimento = this.atendimento();
    if (!currentAtendimento.atendimentoTipoId || !currentAtendimento.observacao) {
      this.poNotification.warning("Preencha o tipo e a observação.");
      return;
    }

    this.crmService.save(currentAtendimento)
      .pipe(takeUntilDestroyed(this.destroyRef))
      .subscribe({
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

  updateAtendimentoTipo(id: number) {
    this.atendimento.update(a => ({...a, atendimentoTipoId: id}));
  }

  updateObservacao(obs: string) {
    this.atendimento.update(a => ({...a, observacao: obs}));
  }

  updateRetorno(dt: string | Date) {
    let dateObj: Date | undefined;
    if (dt) {
      dateObj = typeof dt === 'string' ? new Date(dt) : dt;
    }
    this.atendimento.update(a => ({...a, retorno: dateObj}));
  }
}
