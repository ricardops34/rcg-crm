import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router, RouterOutlet } from "@angular/router";
import { PoMenuItem, PoModule, PoToolbarAction, PoToolbarProfile } from "@po-ui/ng-components";
import { AuthService } from "./services/auth";

@Component({
  selector: "app-root",
  standalone: true,
  imports: [CommonModule, RouterOutlet, PoModule],
  templateUrl: "./app.component.html"
})
export class AppComponent implements OnInit {
  private router = inject(Router);
  public authService = inject(AuthService);

  user: any;

  readonly profile: PoToolbarProfile = {
    avatar: "",
    subtitle: "",
    title: ""
  };

  readonly profileActions: Array<PoToolbarAction> = [
    { label: "Meu Perfil", action: () => this.router.navigate(["/profile"]), icon: "po-icon-user" },
    { label: "Sair", action: () => this.logout(), icon: "po-icon-exit", type: "danger" }
  ];

  ngOnInit() {
    this.refreshUserInfo();
  }

  refreshUserInfo() {
    this.user = this.authService.getUser();
    if (this.user) {
      this.profile.title = this.user.name;
      this.profile.subtitle = this.user.email;
      this.profile.avatar = this.user.avatar || "assets/default-avatar.png";
    }
  }

  get menus(): Array<PoMenuItem> {
    const items: Array<PoMenuItem> = [
      { label: "Dashboard", action: () => this.router.navigate(["/dashboard"]), icon: "po-icon-chart-area", shortLabel: "Dash" },
      { 
        label: "Vendas e CRM", 
        icon: "po-icon-finance", 
        subItems: [
          { label: "Análise CRM (MCV)", action: () => this.router.navigate(["/mvc"]), icon: "po-icon-vendas" },
          { label: "Objetivos e Metas", action: () => this.router.navigate(["/metas"]), icon: "po-icon-target" },
          { label: "Gestão de Equipe", action: () => this.router.navigate(["/vendedores"]), icon: "po-icon-user" },
          { label: "Clientes", action: () => this.router.navigate(["/clientes"]), icon: "po-icon-users" }
        ]
      }
    ];

    // Somente Administrador ou Gerência vê o menu de Segurança
    if (this.user?.isGerente) {
      items.push({ 
        label: "Segurança e Acesso", 
        icon: "po-icon-security", 
        subItems: [
          { label: "Usuários", action: () => this.router.navigate(["/admin/users"]), icon: "po-icon-user-add" },
          { label: "Grupos de Permissão", action: () => this.router.navigate(["/admin/groups"]), icon: "po-icon-users" }
        ]
      });
    }

    return items;
  }

  logout() {
    this.authService.logout();
    this.router.navigate(["/login"]);
  }

}

