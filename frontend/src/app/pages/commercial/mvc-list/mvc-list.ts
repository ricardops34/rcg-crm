import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { 
  PoModule, 
  PoTableColumn, 
  PoPageAction, 
  PoPageFilter, 
  PoSelectOption, 
  PoTableAction, 
  PoModalComponent, 
  PoBreadcrumb, 
  PoDropdownAction 
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { AnalyticsService } from "../../../services/analytics";
import { AuthService } from "../../../services/auth";
import { VendedorService } from "../../../services/vendedor";
import { LocationService } from "../../../services/location";

@Component({
  selector: "app-mvc-list",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./mvc-list.html"
})
export class MvcListComponent implements OnInit {
  @ViewChild("modalDetails", { static: true }) modalDetails!: PoModalComponent;

  private analyticsService = inject(AnalyticsService);
  private authService = inject(AuthService);
  private vendedorService = inject(VendedorService);
  private locationService = inject(LocationService);

  items: Array<any> = [];
  selectedItem: any = {};
  isLoading: boolean = true;
  
  years: Array<PoSelectOption> = [
    { label: "2026", value: 2026 },
    { label: "2025", value: 2025 },
    { label: "2024", value: 2024 }
  ];
  
  selectedYear: number = new Date().getFullYear();
  selectedVendedor: number | undefined;
  selectedEstado: number | undefined;
  selectedMunicipio: number | undefined;
  selectedSituacao: string | undefined;
  minDias: number | undefined;

  vendedores: Array<PoSelectOption> = [];
  estados: Array<PoSelectOption> = [];
  municipios: Array<PoSelectOption> = [];
  
  situacoes: Array<PoSelectOption> = [
    { label: "Ativo", value: "A" },
    { label: "Bloqueado", value: "B" }
  ];

  isGerente: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendas", link: "/mvc" },
      { label: "Análise CRM (MCV)" }
    ]
  };

  readonly filter: PoPageFilter = {
    action: this.loadData.bind(this),
    placeholder: "Pesquisar por cliente"
  };

  readonly actions: Array<PoPageAction> = [
    { label: " XLS", action: () => {}, icon: "po-icon-export" },
    { label: " PDF", action: () => {}, icon: "po-icon-pdf" }
  ];

  readonly quickFilters: Array<PoDropdownAction> = [
    { label: " + 120 Dias", action: this.applyQuickFilter.bind(this, 120), icon: "po-icon-plus" },
    { label: " + 90 Dias", action: this.applyQuickFilter.bind(this, 90), icon: "po-icon-plus" },
    { label: " + 60 Dias", action: this.applyQuickFilter.bind(this, 60), icon: "po-icon-plus" },
    { label: " + 30 Dias", action: this.applyQuickFilter.bind(this, 30), icon: "po-icon-plus" },
    { label: " + 15 Dias", action: this.applyQuickFilter.bind(this, 15), icon: "po-icon-plus" },
    { label: " Bloqueados", action: this.applySituacaoFilter.bind(this, 'B'), icon: "po-icon-lock" },
    { label: " Ativos", action: this.applySituacaoFilter.bind(this, 'A'), icon: "po-icon-unlock" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Ver Detalhes", action: this.showDetails.bind(this), icon: "po-icon-eye" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "financeiro_status", label: "$", type: "subtitle", width: "50px", subtitles: [
      { value: "R", color: "color-07", label: "Atrasado", content: "Vencido" },
      { value: "B", color: "color-10", label: "Em dia", content: "A Vencer" }
    ]},
    { property: "situacao", label: "Situação", type: "label", width: "100px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "codigo", label: "Código", width: "100px" },
    { property: "ultima_compra", label: "Última Compra", type: "date", format: "dd/MM/yyyy", width: "120px" },
    { property: "cliente_nome", label: "Razão Social", width: "250px" },
    { property: "municipio_descricao", label: "Cidade", width: "150px" },
    { property: "difference", label: "Dif. mês e média", type: "currency", format: "BRL", width: "130px" },
    { property: "venda_mes", label: "Venda 30d", type: "currency", format: "BRL", width: "110px" },
    { property: "average3Months", label: "Média 90d", type: "currency", format: "BRL", width: "110px" },
    { property: "dias", label: "Dias", type: "number", width: "80px" },
    { property: "carteira", label: "Carteira", type: "label", width: "100px", labels: [
      { value: "S", color: "color-11", label: "Sim" },
      { value: "N", color: "color-08", label: "Não" }
    ]},
    { property: "vendedor_reduzido", label: "Vendedor", width: "120px" }
  ];

  ngOnInit(): void {
    const user = this.authService.getUser();
    this.isGerente = user?.isGerente || !!user?.supervisorId;
    
    this.loadInitialData();
    this.loadData();
  }

  loadInitialData() {
    if (this.isGerente) {
      this.vendedorService.findAll().subscribe(res => {
        this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
      });
    }

    this.locationService.getEstados().subscribe(res => {
      this.estados = res.map((e: any) => ({ label: e.sigla, value: e.id }));
    });
  }

  onEstadoChange(estadoId: any) {
    this.selectedEstado = estadoId;
    this.selectedMunicipio = undefined;
    if (estadoId) {
      this.locationService.getMunicipios(estadoId).subscribe(res => {
        this.municipios = res.map((m: any) => ({ label: m.descricao, value: m.id }));
      });
    } else {
      this.municipios = [];
    }
    this.loadData();
  }

  applyQuickFilter(dias: number) {
    this.minDias = dias;
    this.loadData();
  }

  applySituacaoFilter(situacao: string) {
    this.selectedSituacao = situacao;
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
      error: () => {
        this.isLoading = false;
      }
    });
  }

  showDetails(item: any) {
    this.selectedItem = item;
    this.modalDetails.open();
  }
}
