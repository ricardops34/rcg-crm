import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { PoModule, PoTableColumn } from "@po-ui/ng-components";
import { AnalyticsService } from "../../../services/analytics";

@Component({
  selector: "app-mvc-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./mvc-list.html"
})
export class MvcListComponent implements OnInit {

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly columns: Array<PoTableColumn> = [
    { property: "cliente_nome", label: "Cliente", width: "200px" },
    { property: "dias", label: "Dias s/ Compra", type: "number", width: "100px" },
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
    { property: "difference", label: "Diferença", type: "currency", format: "BRL", width: "120px" }
  ];

  constructor(private analyticsService: AnalyticsService) { }

  ngOnInit(): void {
    this.loadData();
  }

  loadData() {
    this.isLoading = true;
    this.analyticsService.getMvcData().subscribe({
      next: (res) => {
        this.items = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }
}
