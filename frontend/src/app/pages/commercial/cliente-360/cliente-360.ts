import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoTableColumn, 
  PoNotificationService 
} from "@po-ui/ng-components";
import { ClienteService } from "../../../services/cliente";

@Component({
  selector: "app-cliente-360",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./cliente-360.html"
})
export class Cliente360Component implements OnInit {
  private route = inject(ActivatedRoute);
  private router = inject(Router);
  private clienteService = inject(ClienteService);
  private poNotification = inject(PoNotificationService);

  cliente: any = {};
  comodatoItems: Array<any> = [];
  mixItems: Array<any> = [];
  financeiroItems: Array<any> = [];
  notasItems: Array<any> = [];
  atendimentosItems: Array<any> = [];
  
  isLoading: boolean = true;

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
    { property: "status", label: "Status", type: "label", labels: [
      { value: "Vencido", color: "color-07", label: "Vencido" },
      { value: "Vencendo", color: "color-08", label: "Vencendo" },
      { value: "A Vencer", color: "color-11", label: "A Vencer" }
    ]}
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
    // Carregar todas as abas em paralelo
    this.clienteService.getComodato(id).subscribe(res => this.comodatoItems = res);
    this.clienteService.getMix(id).subscribe(res => this.mixItems = res);
    this.clienteService.getFinanceiro(id).subscribe(res => this.financeiroItems = res);
    this.clienteService.getNotas(id).subscribe(res => this.notasItems = res);
    this.clienteService.getAtendimentos(id).subscribe(res => {
      this.atendimentosItems = res;
      this.isLoading = false;
    });
  }

  close() {
    this.router.navigate(["/mvc"]);
  }
}
