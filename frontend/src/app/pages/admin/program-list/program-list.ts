import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { 
  PoModule, 
  PoPageAction, 
  PoTableColumn, 
  PoTableAction, 
  PoPageFilter, 
  PoNotificationService,
  PoBreadcrumb 
} from "@po-ui/ng-components";
import { ProgramService } from "../../../services/program";

@Component({
  selector: "app-program-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Rotinas do Sistema"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="programs"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>
      
    </po-page-list>
  `
})
export class ProgramListComponent implements OnInit {
  private programService = inject(ProgramService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  programs: Array<any> = [];
  isLoading: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Segurança", link: "/admin/users" },
      { label: "Rotinas" }
    ]
  };

  readonly filter: PoPageFilter = {
    action: this.loadPrograms.bind(this),
    placeholder: "Pesquisar por nome ou controller"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Nova Rotina", action: () => this.router.navigate(["/admin/programs/new"]), icon: "po-icon-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/programs/edit/${row.id}`]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteProgram.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "name", label: "Nome da Rotina" },
    { property: "controller", label: "Controller (PHP Class)" }
  ];

  ngOnInit() {
    this.loadPrograms();
  }

  loadPrograms(filter?: string) {
    this.isLoading = true;
    this.programService.findAll().subscribe({
      next: (res) => {
        this.programs = res;
        if (filter) {
          this.programs = this.programs.filter(p => 
            p.name?.toLowerCase().includes(filter.toLowerCase()) ||
            p.controller?.toLowerCase().includes(filter.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  deleteProgram(program: any) {
    if (confirm(`Deseja realmente excluir a rotina ${program.name}?`)) {
      this.isLoading = true;
      this.programService.delete(program.id).subscribe({
        next: () => {
          this.poNotification.success("Rotina excluída com sucesso!");
          this.loadPrograms();
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir rotina.");
        }
      });
    }
  }
}
