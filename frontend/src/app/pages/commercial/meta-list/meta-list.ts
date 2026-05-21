import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { 
  PoModule, 
  PoTableColumn, 
  PoTableAction, 
  PoPageAction, 
  PoPageFilter, 
  PoNotificationService,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { MetaVendedorService } from "../../../services/meta-vendedor";

@Component({
  selector: "app-meta-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Objetivos e Metas"
      p-subtitle="Gestão de metas mensais de vendas"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="items"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>
      
    </po-page-list>
  `
})
export class MetaListComponent implements OnInit {
  private metaService = inject(MetaVendedorService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  items: Array<any> = [];
  isLoading: boolean = true;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Metas de Venda" }
    ]
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Objetivo", action: () => this.router.navigate(["/metas/new"]), icon: "po-icon-target" },
    { label: "Atualizar", action: () => this.loadData(), icon: "po-icon-refresh" }
  ];

  readonly filter: PoPageFilter = {
    action: this.onFilter.bind(this),
    placeholder: "Pesquisar por ano ou vendedor"
  };

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "ano", label: "Ano", width: "80px" },
    { property: "mes", label: "Mês", width: "80px" },
    { property: "vendedor.nome", label: "Vendedor" },
    { property: "valor", label: "Valor Meta", type: "currency", format: "BRL" },
    { property: "numeroCliente", label: "Positivação", type: "number" },
    { property: "novoCliente", label: "Novos Clientes", type: "number" },
    { property: "tipo", label: "Tipo", type: "label", labels: [
      { value: "M", color: "color-10", label: "Mensal" },
      { value: "S", color: "color-08", label: "Semanal" }
    ]}
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate(["/metas/edit", row.id]), icon: "po-icon-edit" },
    { label: "Excluir", action: (row: any) => this.remove(row), icon: "po-icon-delete" }
  ];

  ngOnInit(): void {
    this.loadData();
  }

  loadData(filter?: string) {
    this.isLoading = true;
    this.metaService.findAll().subscribe({
      next: (res) => {
        this.items = res.items;
        if (filter) {
          this.items = this.items.filter(item => 
            item.ano?.includes(filter) ||
            item.vendedor?.nome?.toLowerCase().includes(filter.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar metas.");
      }
    });
  }

  onFilter(filter: string) {
    this.loadData(filter);
  }

  remove(row: any) {
    if (!confirm(`Excluir a meta de ${row.vendedor?.nome || "vendedor"} para ${row.mes}/${row.ano}?`)) {
      return;
    }

    this.isLoading = true;
    this.metaService.delete(row.id).subscribe({
      next: () => {
        this.poNotification.success("Meta excluída com sucesso!");
        this.loadData();
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao excluir meta.");
      }
    });
  }
}
