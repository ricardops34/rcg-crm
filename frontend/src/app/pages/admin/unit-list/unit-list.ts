import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoPageAction, PoTableColumn, PoTableAction, PoPageFilter, PoNotificationService } from "@po-ui/ng-components";
import { UnitService } from "../../../services/unit";

@Component({
  selector: "app-unit-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Unidades do Sistema"
      p-subtitle="Gestão de filiais e conexões"
      [p-actions]="actions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="units"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>
      
    </po-page-list>
  `
})
export class UnitListComponent implements OnInit {
  private unitService = inject(UnitService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  units: Array<any> = [];
  isLoading: boolean = false;

  readonly filter: PoPageFilter = {
    action: this.loadUnits.bind(this),
    placeholder: "Pesquisar unidade"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Nova Unidade", action: () => this.router.navigate(["/admin/units/new"]), icon: "po-icon-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/units/edit/${row.id}`]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteUnit.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "name", label: "Nome da Unidade" },
    { property: "connectionName", label: "Conexão (ERP)" }
  ];

  ngOnInit() {
    this.loadUnits();
  }

  loadUnits(filter?: string) {
    this.isLoading = true;
    this.unitService.findAll().subscribe({
      next: (res) => {
        this.units = res;
        if (filter) {
          this.units = this.units.filter(u => 
            u.name?.toLowerCase().includes(filter.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  deleteUnit(unit: any) {
    if (confirm(`Deseja realmente excluir a unidade ${unit.name}?`)) {
      this.isLoading = true;
      this.unitService.delete(unit.id).subscribe({
        next: () => {
          this.poNotification.success("Unidade excluída com sucesso!");
          this.loadUnits();
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir unidade.");
        }
      });
    }
  }
}
