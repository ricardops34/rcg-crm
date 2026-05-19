import { Component, OnInit, ViewChild } from "@angular/core";
import { CommonModule } from "@angular/common";
import { PoModule, PoTableColumn, PoPageAction, PoPageFilter, PoSelectOption, PoTableAction, PoModalComponent } from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { AnalyticsService } from "../../../services/analytics";
import { AuthService } from "../../../services/auth";
import { VendedorService } from "../../../services/vendedor";

@Component({
  selector: "app-mvc-list",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./mvc-list.html"
})
export class MvcListComponent implements OnInit {
  @ViewChild("modalDetails", { static: true }) modalDetails!: PoModalComponent;

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
  vendedores: Array<PoSelectOption> = [];
  isGerente: boolean = false;

  readonly filter: PoPageFilter = {
    action: this.loadData.bind(this),
    placeholder: "Pesquisar por cliente"
  };

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendas", link: "/mvc" },
      { label: "Análise CRM (MCV)" }
    ]
  };

  readonly tableActions: Array<PoTableAction> = [
    { label: "Ver Detalhes", action: this.showDetails.bind(this), icon: "po-icon-eye" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "cliente_nome", label: "Cliente", width: "250px" },
    { property: "situacao", label: "Status", type: "label", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" },
      { value: "S", color: "color-11", label: "Suspenso" }
    ]},
    { property: "dias", label: "Dias s/ Compra", type: "number", width: "130px" },
    { property: "janeiro", label: "Jan", type: "currency", format: "BRL", width: "100px" },
    { property: "fevereiro", label: "Fev", type: "currency", format: "BRL", width: "100px" },
    { property: "marco", label: "Mar", type: "currency", format: "BRL", width: "100px" },
    { property: "abril", label: "Abr", type: "currency", format: "BRL", width: "100px" },
    { property: "maio", label: "Mai", type: "currency", format: "BRL", width: "100px" },
    { property: "junho", label: "Jun", type: "currency", format: "BRL", width: "100px" },
    { property: "julho", label: "Jul", type: "currency", format: "BRL", width: "100px" },
    { property: "agosto", label: "Ago", type: "currency", format: "BRL", width: "100px" },
    { property: "setembro", label: "Set", type: "currency", format: "BRL", width: "100px" },
    { property: "outubro", label: "Out", type: "currency", format: "BRL", width: "100px" },
    { property: "novembro", label: "Nov", type: "currency", format: "BRL", width: "100px" },
    { property: "dezembro", label: "Dez", type: "currency", format: "BRL", width: "100px" },
    { property: "average3Months", label: "Média (3m)", type: "currency", format: "BRL", width: "120px" },
    { property: "difference", label: "Tendência", type: "subtitle", width: "150px", subtitles: [
      { value: 0, color: "color-07", label: "Queda", content: "Queda de Consumo" },
      { value: 1, color: "color-10", label: "Alta", content: "Crescimento" }
    ]}
  ];

  constructor(
    private analyticsService: AnalyticsService,
    private authService: AuthService,
    private vendedorService: VendedorService
  ) { }

  ngOnInit(): void {
    const user = this.authService.getUser();
    this.isGerente = user?.isGerente || !!user?.supervisorId;
    
    if (this.isGerente) {
      this.loadVendedores();
    }
    
    this.loadData();
  }

  loadVendedores() {
    this.vendedorService.findAll().subscribe(res => {
      this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
    });
  }

  loadData(filter?: string) {
    this.isLoading = true;
    this.analyticsService.getMvcData(this.selectedYear, this.selectedVendedor).subscribe({
      next: (res) => {
        this.items = res.map((item: any) => ({
          ...item,
          // 0 para queda (negativo), 1 para alta (positivo ou zero)
          difference: item.difference < 0 ? 0 : 1
        }));

        if (filter) {
          this.items = this.items.filter(item => 
            item.cliente_nome?.toLowerCase().includes(filter.toLowerCase())
          );
        }
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

