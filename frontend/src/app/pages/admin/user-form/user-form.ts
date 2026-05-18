import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService } from "@po-ui/ng-components";
import { UserService } from "../../../services/user";

@Component({
  selector: "app-user-form",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-edit 
      [p-title]="title"
      (p-save)="save()"
      (p-cancel)="cancel()">
      
      <form #userForm="ngForm">
        <div class="po-row">
          <po-input class="po-md-6" name="name" [(ngModel)]="user.name" p-label="Nome" p-required></po-input>
          <po-input class="po-md-3" name="login" [(ngModel)]="user.login" p-label="Login" p-required></po-input>
          <po-password class="po-md-3" name="password" [(ngModel)]="user.password" p-label="Senha" [p-required]="!isEdit"></po-password>
        </div>
        
        <div class="po-row">
          <po-input class="po-md-6" name="email" [(ngModel)]="user.email" p-label="E-mail" p-required></po-input>
          <po-select class="po-md-3" name="active" [(ngModel)]="user.active" p-label="Ativo" [p-options]="activeOptions"></po-select>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class UserFormComponent implements OnInit {

  user: any = { active: "Y" };
  isEdit: boolean = false;
  title: string = "Novo Usuário";

  readonly activeOptions = [
    { label: "Sim", value: "Y" },
    { label: "Não", value: "N" }
  ];

  constructor(
    private userService: UserService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private poNotification: PoNotificationService
  ) {}

  ngOnInit() {
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Usuário";
      this.loadUser(id);
    }
  }

  loadUser(id: number) {
    this.userService.findOne(id).subscribe(res => {
      this.user = res;
      this.user.password = ""; // Não carregar a senha
    });
  }

  save() {
    this.userService.save(this.user).subscribe({
      next: () => {
        this.poNotification.success("Usuário salvo com sucesso!");
        this.router.navigate(["/admin/users"]);
      },
      error: (err) => {
        this.poNotification.error("Erro ao salvar usuário.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/users"]);
  }
}
