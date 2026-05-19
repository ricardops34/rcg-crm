import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService, PoSelectOption, PoMultiselectOption } from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { UserService } from "../../../services/user";
import { UnitService } from "../../../services/unit";
import { GroupService } from "../../../services/group";

@Component({
  selector: "app-user-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit 
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()">
      
      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <form #userForm="ngForm">
        <po-divider p-label="Informações Básicas"></po-divider>
        <div class="po-row">
          <po-input class="po-md-6" name="name" [(ngModel)]="user.name" p-label="Nome Completo" p-required p-clean></po-input>
          <po-input class="po-md-3" name="login" [(ngModel)]="user.login" p-label="Login de Acesso" p-required p-clean></po-input>
          <po-password class="po-md-3" name="password" [(ngModel)]="user.password" p-label="Senha" [p-required]="!isEdit ? 'true' : 'false'" p-clean></po-password>
        </div>
        
        <div class="po-row">
          <po-input class="po-md-6" name="email" [(ngModel)]="user.email" p-label="E-mail Corporativo" p-required p-clean p-icon="po-icon-mail"></po-input>
          <po-select class="po-md-3" name="systemUnitId" [ngModel]="user.systemUnitId" (p-change)="user.systemUnitId = $event" p-label="Unidade / Filial" [p-options]="unitOptions" p-required="true"></po-select>
          <po-select class="po-md-3" name="active" [ngModel]="user.active" (p-change)="user.active = $event" p-label="Status do Usuário" [p-options]="activeOptions"></po-select>
        </div>

        <po-divider p-label="Permissões e Acesso"></po-divider>
        <div class="po-row">
          <po-multiselect class="po-md-12" name="groups" [(ngModel)]="user.groups" p-label="Grupos de Permissão" [p-options]="groupOptions" p-placeholder="Selecione um ou mais grupos"></po-multiselect>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class UserFormComponent implements OnInit {

  user: any = { active: "Y", groups: [] };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Novo Usuário";

  unitOptions: Array<PoSelectOption> = [];
  groupOptions: Array<PoMultiselectOption> = [];

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Usuários", link: "/admin/users" },
      { label: "Cadastro" }
    ]
  };

  readonly activeOptions = [
    { label: "Sim", value: "Y" },
    { label: "Não", value: "N" }
  ];

  constructor(
    private userService: UserService,
    private unitService: UnitService,
    private groupService: GroupService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private poNotification: PoNotificationService
  ) {}

  ngOnInit() {
    this.loadUnits();
    this.loadGroups();

    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Usuário";
      this.loadUser(id);
    }
  }

  loadUnits() {
    this.unitService.findAll().subscribe(res => {
      this.unitOptions = res.map((u: any) => ({ label: u.nome, value: u.id }));
    });
  }

  loadGroups() {
    this.groupService.findAll().subscribe(res => {
      this.groupOptions = res.map((g: any) => ({ label: g.name, value: g.id }));
    });
  }

  loadUser(id: number) {
    this.isLoading = true;
    this.userService.findOne(id).subscribe({
      next: (res) => {
        this.user = res;
        this.user.password = ""; 
        if (this.user.userGroups) {
          this.user.groups = this.user.userGroups.map((ug: any) => ug.systemGroupId);
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar usuário.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.userService.save(this.user).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Usuário salvo com sucesso!");
        this.router.navigate(["/admin/users"]);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar usuário.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/users"]);
  }
}
