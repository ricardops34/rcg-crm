import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoPageAction, PoTableColumn } from "@po-ui/ng-components";
import { UserService } from "../../../services/user";

@Component({
  selector: "app-user-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list 
      p-title="Usuários do Sistema"
      [p-actions]="actions">
      
      <po-table 
        [p-columns]="columns"
        [p-items]="users">
      </po-table>
      
    </po-page-list>
  `
})
export class UserListComponent implements OnInit {

  users: Array<any> = [];

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Usuário", action: () => this.router.navigate(["/admin/users/new"]), icon: "po-icon-user-add" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "5%" },
    { property: "name", label: "Nome" },
    { property: "login", label: "Login" },
    { property: "email", label: "E-mail" },
    { property: "active", label: "Ativo", type: "label", labels: [
      { value: "Y", color: "color-10", label: "Sim" },
      { value: "N", color: "color-07", label: "Não" }
    ]},
    { property: "actions", label: "Ações", type: "action", actions: [
      { label: "Editar", action: (row: any) => this.router.navigate([`/admin/users/edit/${row.id}`]), icon: "po-icon-edit" }
    ]}
  ];

  constructor(
    private userService: UserService,
    private router: Router
  ) {}

  ngOnInit() {
    this.loadUsers();
  }

  loadUsers() {
    this.userService.findAll().subscribe(res => {
      this.users = res;
    });
  }
}
