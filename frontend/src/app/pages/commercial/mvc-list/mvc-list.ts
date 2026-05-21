import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { 
  PoModule, 
  PoTableColumn, 
  PoPageAction, 
  PoPageFilter, 
  PoSelectOption, 
  PoTableAction, 
  PoModalComponent, 
  PoBreadcrumb, 
  PoNotificationService
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { AnalyticsService } from "../../../services/analytics";
import { AuthService } from "../../../services/auth";
import { VendedorService } from "../../../services/vendedor";
import { LocationService } from "../../../services/location";
import { CrmService } from "../../../services/crm";

@Component({
  selector: "app-mvc-list",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./mvc-list.html"
})
export class MvcListComponent implements OnInit {
  @ViewChild("modalAtendimento", { static: true }) modalAtendimento!: PoModalComponent;

  private analyticsService = inject(AnalyticsService);
  private authService = inject(AuthService);
  private vendedorService = inject(VendedorService);
  private locationService = inject(LocationService);
  private crmService = inject(CrmService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  items: Array<any> = [];
  isLoading: boolean = true;
  summary: any = { goal: 0, realized: 0, achievement: 0 };
  
  years: Array<PoSelectOption> = [
    { label: "2026", value: 2026 },
    { label: "2025", value: 2025 },
    { label: "2024", value: 2024 }
  ];
  months: Array<PoSelectOption> = Array.from({ length: 12 }, (_, i) => ({ label: `Mês ${i + 1}`, value: i + 1 }));
  
  selectedYear: number = new Date().getFullYear();
  selectedMonth: number = new Date().getMonth() + 1;
  selectedVendedor: number | undefined;
  selectedEstado: number | undefined;
  selectedMunicipio: number | undefined;
  selectedSituacao: string | undefined;
  minDias: number | undefined;

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

  situacoes: Array<PoSelectOption> = [
    { label: "Ativo", value: "A" },
    { label: "Bloqueado", value: "B" }
  ];

  isGerente: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Cockpit de Vendas (MCV)" }
    ]
  };

  readonly filter: PoPageFilter = {
    action: this.loadData.bind(this),
    placeholder: "Pesquisar por cliente"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Cliente", action: () => this.router.navigate(["/clientes/new"]), icon: "po-icon-user-add" },
    { label: "Atualizar", action: () => this.loadAllData(), icon: "po-icon-refresh" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Visão 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "po-icon-eye" },
    { label: "Editar Cliente", action: (item: any) => this.router.navigate(["/clientes/edit", item.cliente_id]), icon: "po-icon-edit" },
    { label: "Novo Atendimento", action: this.openAtendimento.bind(this), icon: "po-icon-chat" }
  ];

  readonly quickFilters: Array<any> = [
    { label: " + 120 Dias", action: this.applyQuickFilter.bind(this, 120), icon: "po-icon-plus" },
    { label: " + 90 Dias", action: this.applyQuickFilter.bind(this, 90), icon: "po-icon-plus" },
    { label: " + 60 Dias", action: this.applyQuickFilter.bind(this, 60), icon: "po-icon-plus" },
    { label: " + 30 Dias", action: this.applyQuickFilter.bind(this, 30), icon: "po-icon-plus" },
    { label: " Bloqueados", action: this.applySituacaoFilter.bind(this, 'B'), icon: "po-icon-lock" },
    { label: " Ativos", action: this.applySituacaoFilter.bind(this, 'A'), icon: "po-icon-unlock" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "financeiro_status", label: "$", type: "subtitle", width: "50px", subtitles: [
      { value: "R", color: "color-07", label: "Atrasado", content: "!" },
      { value: "B", color: "color-10", label: "Em dia", content: "$" }
    ]},
    { property: "situacao", label: "Situação", type: "label", width: "100px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "codigo", label: "Código", width: "100px" },
    { property: "cliente_nome", label: "Razão Social", width: "250px" },
    { property: "municipio_descricao", label: "Cidade", width: "150px" },
    { property: "difference", label: "Dif. Média", type: "currency", format: "BRL", width: "130px" },
    { property: "venda_mes", label: "Venda 30d", type: "currency", format: "BRL", width: "110px" },
    { property: "average3Months", label: "Média 90d", type: "currency", format: "BRL", width: "110px" },
    { property: "dias", label: "Dias", type: "number", width: "80px" }
  ];

  ngOnInit(): void {
    const user = this.authService.getUser();
    this.isGerente = user?.roles?.includes('ADMIN') || !!user?.supervisorId;
    
    this.loadInitialData();
    this.loadAllData();
  }

  loadInitialData() {
    if (this.isGerente) {
      this.vendedorService.findAll(1, 100).subscribe(res => {
        this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
      });
    }
    this.locationService.getEstados().subscribe(res => {
      this.estados = res.map((e: any) => ({ label: e.sigla, value: e.id }));
    });
    this.crmService.getTipos().subscribe(res => {
      this.tiposAtendimento = res.map((t: any) => ({ label: t.descricao, value: t.id }));
    });
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
    this.isLoading = true;
    this.crmService.save(this.atendimento).subscribe({
      next: () => {
        this.poNotification.success("Atendimento registrado com sucesso!");
        this.modalAtendimento.close();
        this.loadAllData();
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao registrar atendimento.");
      }
    });
  }

  onEstadoChange(estadoId: any) {
    this.selectedEstado = estadoId;
    if (estadoId) {
      this.locationService.getMunicipios(estadoId).subscribe(res => {
        this.municipios = res.map((m: any) => ({ label: m.descricao, value: m.id }));
      });
    }
    this.loadAllData();
  }

  applyQuickFilter(dias: number) {
    this.minDias = dias;
    this.loadAllData();
  }

  applySituacaoFilter(situacao: string) {
    this.selectedSituacao = situacao;
    this.loadAllData();
  }

  loadAllData() {
    this.isLoading = true;
    
    // KPIs
    this.analyticsService.getDashboardData(this.selectedYear, this.selectedMonth).subscribe(res => {
      this.summary = res.summary;
    });

    // Lista MCV
    this.loadData();
  }

  loadData(filterText?: string) {
    this.isLoading = true;
    this.analyticsService.getMvcData({
      year: this.selectedYear,
      vendedorId: this.selectedVendedor,
      estadoId: this.selectedEstado,
      municipioId: this.selectedMunicipio,
      situacao: this.selectedSituacao,
      dias: this.minDias
    }).subscribe({
      next: (res) => {
        let data = res.map((item: any) => ({
          ...item,
          $rowColor: item.difference < 0 ? "#FFF9A7" : undefined
        }));

        if (filterText) {
          data = data.filter((item: any) => 
            item.cliente_nome?.toLowerCase().includes(filterText.toLowerCase())
          );
        }

        this.items = data;
        this.isLoading = false;
      },
      error: () => this.isLoading = false
    });
  }
}
