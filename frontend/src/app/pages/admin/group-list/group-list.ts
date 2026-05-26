import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoModule,
  PoPageAction,
  PoTableColumn,
  PoTableAction,
  PoPageFilter,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { GroupService } from "../../../services/group";

@Component({
  selector: "app-group-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="Perfis de Acesso"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">

      <po-table
        [p-columns]="columns"
        [p-items]="groups"
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
export class GroupListComponent implements OnInit {
  private readonly itensPorPagina = 20;
  private paginaAtual = 1;
  private filtroAtual = "";
  private allGroups: Array<any> = [];

  groups: Array<any> = [];
  isLoading: boolean = false;
  loadingShowMore: boolean = false;
  hasNext: boolean = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "SeguranÃ§a", link: "/admin/users" },
      { label: "Perfis de Acesso" }
    ]
  };

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

  loadGroups(filter: string = "") {
    this.filtroAtual = filter;
    this.paginaAtual = 1;
    this.isLoading = true;
    this.groupService.findAll().subscribe({
      next: (res) => {
        this.allGroups = this.aplicarFiltroLocal(res || [], filter);
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

  deleteGroup(group: any) {
    if (confirm(`Deseja realmente excluir o grupo ${group.name}?`)) {
      this.groupService.delete(group.id).subscribe(() => {
        this.loadGroups(this.filtroAtual);
      });
    }
  }

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.groups = this.allGroups.slice(0, limite);
    this.hasNext = this.allGroups.length > limite;
  }

  private aplicarFiltroLocal(groups: Array<any>, filter: string): Array<any> {
    if (!filter) {
      return groups;
    }

    const filtroNormalizado = filter.toLowerCase();
    return groups.filter(g => g.name?.toLowerCase().includes(filtroNormalizado));
  }
}
