import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoPageAction, PoTableColumn, PoTableAction } from "@po-ui/ng-components";
import { UserService } from "../../../services/user";

@Component({
  selector: "app-user-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Usuários do Sistema"
      [p-actions]="actions"
      [p-filter]="filter">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="users"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true"
        [p-draggable]="true">
      </po-table>
      
    </po-page-list>
  `
})
export class UserListComponent implements OnInit {

  users: Array<any> = [];
  isLoading: boolean = false;

  readonly filter: any = {
    action: this.loadUsers.bind(this),
    placeholder: "Pesquisar por nome ou login"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Usuário", action: () => this.router.navigate(["/admin/users/new"]), icon: "po-icon-user-add" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/users/edit/${row.id}`]), icon: "po-icon-edit" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "name", label: "Nome Completo" },
    { property: "login", label: "Login" },
    { property: "systemUnit.nome", label: "Unidade" },
    { property: "grupos", label: "Grupos" },
    { property: "email", label: "E-mail" },
    { property: "active", label: "Ativo", type: "label", labels: [
      { value: "Y", color: "color-10", label: "Sim" },
      { value: "N", color: "color-07", label: "Não" }
    ]}
  ];

  constructor(
    private userService: UserService,
    private router: Router
  ) {}

  ngOnInit() {
    this.loadUsers();
  }

  loadUsers(filter?: string) {
    this.isLoading = true;
    this.userService.findAll().subscribe({
      next: (res) => {
        this.users = res.map((u: any) => ({
          ...u,
          grupos: u.userGroups?.map((ug: any) => ug.systemGroup?.name).join(", ")
        }));
        if (filter) {
          this.users = this.users.filter(u => 
            u.name?.toLowerCase().includes(filter.toLowerCase()) || 
            u.login?.toLowerCase().includes(filter.toLowerCase())
          );
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }
}
