import { Component, OnInit, ViewChild, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { 
  PoModule, 
  PoChartType, 
  PoChartSerie, 
  PoPageAction, 
  PoSelectOption,
  PoTableColumn,
  PoTableAction,
  PoModalComponent,
  PoNotificationService
} from '@po-ui/ng-components';
import { AnalyticsService } from '../../../services/analytics';
import { AuthService } from '../../../services/auth';
import { VendedorService } from '../../../services/vendedor';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class DashboardComponent implements OnInit {
  @ViewChild("modalDetails", { static: true }) modalDetails!: PoModalComponent;

  private analyticsService = inject(AnalyticsService);
  private authService = inject(AuthService);
  private vendedorService = inject(VendedorService);
  private poNotification = inject(PoNotificationService);
  private router = inject(Router);

  summary: any = { goal: 0, realized: 0, achievement: 0 };
  categorySeries: Array<PoChartSerie> = [];
  sellerSeries: Array<PoChartSerie> = [];
  
  chartType: PoChartType = PoChartType.Donut;
  barChartType: PoChartType = PoChartType.Bar;
  
  isLoading: boolean = true;
  isGerente: boolean = false;

  years: Array<PoSelectOption> = [
    { label: "2026", value: 2026 },
    { label: "2025", value: 2025 },
    { label: "2024", value: 2024 }
  ];
  months: Array<PoSelectOption> = Array.from({ length: 12 }, (_, i) => ({ label: `Mês ${i + 1}`, value: i + 1 }));
  
  selectedYear: number = new Date().getFullYear();
  selectedMonth: number = new Date().getMonth() + 1;
  selectedVendedor: number | undefined;
  vendedores: Array<PoSelectOption> = [];

  mvcItems: Array<any> = [];
  selectedItem: any = {};

  readonly pageActions: Array<PoPageAction> = [
    { label: 'Atualizar Dados', action: this.loadAllData.bind(this), icon: 'an an-arrows-clockwise' }
  ];

  readonly mvcColumns: Array<PoTableColumn> = [
    { property: "financeiro_status", label: "$", type: "subtitle", width: "50px", subtitles: [
      { value: "R", color: "color-07", label: "Atrasado", content: "Vencido" },
      { value: "B", color: "color-10", label: "Em dia", content: "A Vencer" }
    ]},
    { property: "situacao", label: "Situação", type: "label", width: "100px", labels: [
      { value: "A", color: "color-10", label: "Ativo" },
      { value: "B", color: "color-07", label: "Bloqueado" }
    ]},
    { property: "codigo", label: "Código", width: "100px" },
    { property: "cliente_nome", label: "Razão Social", width: "250px" },
    { property: "municipio_descricao", label: "Cidade", width: "150px" },
    { property: "difference", label: "Dif. mês e média", type: "currency", format: "BRL", width: "130px" },
    { property: "venda_mes", label: "Venda 30d", type: "currency", format: "BRL", width: "110px" },
    { property: "average3Months", label: "Média 90d", type: "currency", format: "BRL", width: "110px" },
    { property: "dias", label: "Dias", type: "number", width: "80px" }
  ];

  readonly mvcActions: Array<PoTableAction> = [
    { label: "Visão 360", action: (item: any) => this.router.navigate(["/clientes/360", item.cliente_id]), icon: "an an-eye" }
  ];

  ngOnInit(): void {
    const user = this.authService.getUser();
    this.isGerente = user?.isGerente || !!user?.supervisorId;
    
    if (this.isGerente) {
      this.loadVendedores();
    }
    
    this.loadAllData();
  }

  loadVendedores() {
    this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe(res => {
      this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
    });
  }

  loadAllData() {
    this.isLoading = true;
    
    // Load KPIs & Charts
    this.analyticsService.getDashboardData(this.selectedYear, this.selectedMonth).subscribe({
      next: (res) => {
        this.summary = res.summary;
        this.categorySeries = res.categories;
        this.sellerSeries = res.sellers || [];
        
        // Load MVC (somente se os KPIs carregarem com sucesso)
        this.analyticsService.getMvcData({
          year: this.selectedYear,
          vendedorId: this.selectedVendedor
        }).subscribe({
          next: (mvcRes) => {
            this.mvcItems = mvcRes.map((item: any) => ({
              ...item,
              $rowColor: item.difference < 0 ? "#FFF9A7" : undefined
            }));
            this.isLoading = false;
          },
          error: (err: any) => {
            console.error("Erro ao carregar MVC:", err);
            this.isLoading = false;
          }
        });
      },
      error: (err: any) => {
        console.error("Erro ao carregar KPIs:", err);
        this.isLoading = false;
        this.poNotification.error("Falha ao carregar indicadores do painel.");
      }
    });
  }
}
