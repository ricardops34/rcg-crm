import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoPageAction, PoTableColumn, PoTableAction, PoPageFilter } from "@po-ui/ng-components";
import { GroupService } from "../../../services/group";

@Component({
  selector: "app-group-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Perfis de Acesso"
      [p-actions]="actions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="groups"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>
      
    </po-page-list>
  `
})
export class GroupListComponent implements OnInit {

  groups: Array<any> = [];
  isLoading: boolean = false;

  readonly filter: PoPageFilter = {
    action: this.loadGroups.bind(this),
    placeholder: "Pesquisar perfil"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Perfil", action: () => this.router.navigate(["/admin/groups/new"]), icon: "po-icon-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/groups/edit/${row.id}`]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteGroup.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "name", label: "Nome do Perfil" },
    { property: "role", label: "Tipo de Acesso (Behavior)", type: "label", labels: [
      { value: "ADMIN", color: "color-07", label: "Administrador" },
      { value: "GERENTE", color: "color-11", label: "Gerente" },
      { value: "SUPERVISOR", color: "color-08", label: "Supervisor" },
      { value: "VENDEDOR", color: "color-10", label: "Vendedor" },
      { value: "CLIENTE", color: "color-09", label: "Cliente" }
    ]}
  ];

  constructor(
    private groupService: GroupService,
    private router: Router
  ) {}

  ngOnInit() {
    this.loadGroups();
  }

  loadGroups(filter?: string) {
    this.isLoading = true;
    this.groupService.findAll().subscribe({
      next: (res) => {
        this.groups = res;
        if (filter) {
          this.groups = this.groups.filter(g => 
            g.name?.toLowerCase().includes(filter.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  deleteGroup(group: any) {
    if (confirm(`Deseja realmente excluir o grupo ${group.name}?`)) {
      this.groupService.delete(group.id).subscribe(() => {
        this.loadGroups();
      });
    }
  }
}
