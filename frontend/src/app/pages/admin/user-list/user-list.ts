import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule, PoPageAction, PoTableColumn, PoTableAction, PoNotificationService, PoBreadcrumb } from "@po-ui/ng-components";
import { UserService } from "../../../services/user";

@Component({
  selector: "app-user-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="UsuÃ¡rios do Sistema"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">

      <po-table
        [p-columns]="columns"
        [p-items]="users"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        [p-loading-show-more]="loadingShowMore"
        [p-show-more-disabled]="!hasNext"
        (p-show-more)="showMore()"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>

    </po-page-list>
  `
})
export class UserListComponent implements OnInit {
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private filtroAtual = "";
  private allUsers: Array<any> = [];

  users: Array<any> = [];
  isLoading: boolean = false;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "SeguranÃ§a", link: "/admin/users" },
      { label: "UsuÃ¡rios" }
    ]
  };

  readonly filter: any = {
    action: this.loadUsers.bind(this),
    placeholder: "Pesquisar por nome ou login"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo UsuÃ¡rio", action: () => this.router.navigate(["/admin/users/new"]), icon: "po-icon-user-add" },
    { label: "Configurar Termos/LGPD", action: () => this.router.navigate(["/admin/users/terms"]), icon: "po-icon-document" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/users/edit/${row.id}`]), icon: "po-icon-edit" },
    { label: "Excluir", action: this.deleteUser.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "id", label: "ID", width: "80px" },
    { property: "name", label: "Nome Completo" },
    { property: "login", label: "Login" },
    { property: "systemUnit.name", label: "Unidade" },
    { property: "grupos", label: "Perfis" },
    { property: "acceptedTermPolicy", label: "LGPD", type: "label", labels: [
      { value: "Y", color: "color-10", label: "Aceitou" },
      { value: "N", color: "color-07", label: "Pendente" }
    ]},
    { property: "acceptedTermPolicyAt", label: "Data Aceite", type: "date", format: "dd/MM/yyyy HH:mm" },
    { property: "active", label: "Ativo", type: "label", labels: [
      { value: "Y", color: "color-10", label: "Sim" },
      { value: "N", color: "color-07", label: "NÃ£o" }
    ]}
  ];

  constructor(
    private userService: UserService,
    private router: Router,
    private poNotification: PoNotificationService
  ) {}

  ngOnInit() {
    this.loadUsers();
  }

  loadUsers(filter: string = "") {
    this.filtroAtual = filter;
    this.paginaAtual = 1;
    this.isLoading = true;
    this.userService.findAll().subscribe({
      next: (res) => {
        const users = res.map((u: any) => ({
          ...u,
          grupos: u.userGroups?.map((ug: any) => ug.systemGroup?.name).join(", ")
        }));
        this.allUsers = this.aplicarFiltroLocal(users, filter);
        this.atualizarPaginaVisivel();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.loadingShowMore = true;
    this.paginaAtual += 1;
    this.atualizarPaginaVisivel();
    this.loadingShowMore = false;
  }

  deleteUser(user: any) {
    if (confirm(`Deseja realmente excluir o usuÃ¡rio ${user.name}?`)) {
      this.isLoading = true;
      this.userService.delete(user.id).subscribe({
        next: () => {
          this.poNotification.success("UsuÃ¡rio excluÃ­do com sucesso!");
          this.loadUsers(this.filtroAtual);
        },
        error: () => {
          this.isLoading = false;
          this.poNotification.error("Erro ao excluir usuÃ¡rio.");
        }
      });
    }
  }

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.users = this.allUsers.slice(0, limite);
    this.hasNext = this.allUsers.length > limite;
  }

  private aplicarFiltroLocal(users: Array<any>, filter: string): Array<any> {
    if (!filter) {
      return users;
    }

    const filtroNormalizado = filter.toLowerCase();
    return users.filter(u =>
      u.name?.toLowerCase().includes(filtroNormalizado) ||
      u.login?.toLowerCase().includes(filtroNormalizado)
    );
  }
}
