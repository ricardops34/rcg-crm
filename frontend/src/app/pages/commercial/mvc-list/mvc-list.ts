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
import { PoPageDynamicTableModule, PoPageDynamicTableCustomTableAction, PoPageDynamicTableCustomAction, PoPageDynamicTableField } from "@po-ui/ng-templates";
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

  readonly serviceApi = `${environment.apiUrl}/analytics/mvc/table`;

  readonly fields: Array<any> = [
    {
      property: "financeiro_status", label: "$", type: "label", width: "80px", labels: [
        { value: "R", color: "color-07", label: "Atrasado" },
        { value: "B", color: "color-10", label: "Em dia" }
      ]
    },
    {
      property: "situacao", label: "Situação", type: "label", width: "100px", labels: [
        { value: "A", color: "color-10", label: "Ativo" },
        { value: "B", color: "color-07", label: "Bloqueado" }
      ]
    },
    { property: "codigo", label: "Código", width: "110px" },
    { property: "cliente_nome", label: "Razão Social" },
    { property: "fantasia", label: "Nome Fantasia" },
    { property: "municipio_descricao", label: "Cidade", width: "160px" },
    { property: "estado_sigla", label: "Estado (UF)", width: "100px" },
    { property: "vendedor_reduzido", label: "Vendedor", width: "150px" },
    { property: "difference", label: "Dif. Média", type: "currency", format: "BRL", width: "140px" },
    { property: "venda_mes", label: "Venda 30d", type: "currency", format: "BRL", width: "130px" },
    { property: "average3Months", label: "Média 90d", type: "currency", format: "BRL", width: "130px" },
    
    // Campo principal de Filtro Rápido (Dias da Última Compra)
    { property: "dias", label: "Dias", type: "number", width: "110px", filter: true },
    
    // Campo de Filtro Avançado (Restrito: Admin, Supervisor e Gerente)
    { property: "vendedor_id", label: "Vendedor", filter: true, visible: false, options: [] }
  ];

  readonly tableCustomActions: Array<PoPageDynamicTableCustomTableAction> = [
    { label: "Visão 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "po-icon-eye" },
    { label: "Editar Cliente", action: (item: any) => this.router.navigate(["/clientes/edit", item.cliente_id]), icon: "po-icon-edit" },
    { label: "Novo Atendimento", action: (item: any) => this.openAtendimento(item), icon: "po-icon-chat" }
  ];

  readonly pageCustomActions: Array<PoPageDynamicTableCustomAction> = [
    { label: "Atualizar", action: () => this.refreshTable(), icon: "po-icon-refresh" }
  ];

  ngOnInit(): void {
    const user = this.authService.getUser();
    // Exigência estrita: Admin, Supervisor ou Gerente
    this.isGerente = 
      !!user?.roles?.includes('ADMIN') || 
      !!user?.roles?.includes('SUPERVISOR') || 
      !!user?.roles?.includes('GERENTE');

    this.loadInitialData();
    this.loadKpis();
  }

  loadInitialData() {
    if (this.isGerente) {
      this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe(res => {
        this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
        const field = this.fields.find(f => f.property === 'vendedor_id');
        if (field) field.options = this.vendedores;
      });
    } else {
      // Se não for gestor, remove totalmente o filtro de vendedor
      const idx = this.fields.findIndex(f => f.property === 'vendedor_id');
      if (idx > -1) this.fields.splice(idx, 1);
    }

    this.crmService.getTipos().subscribe(res => {
      this.tiposAtendimento = res.map((t: any) => ({ label: t.descricao, value: t.id }));
    });
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
      this.poNotification.warning("Preencha o tipo e a observação.");
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
