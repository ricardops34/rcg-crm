import { Component } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router, RouterOutlet } from "@angular/router";
import { PoMenuItem, PoModule } from "@po-ui/ng-components";
import { AuthService } from "./services/auth";

@Component({
  selector: "app-root",
  standalone: true,
  imports: [CommonModule, RouterOutlet, PoModule],
  templateUrl: "./app.component.html",
  // // styleUrls: ["./app.component.css"]
})
export class AppComponent {

  constructor(public authService: AuthService, private router: Router) {}

  get menus(): Array<PoMenuItem> {
    const items: Array<PoMenuItem> = [
      { label: "Dashboard Operacional", action: () => this.router.navigate(["/dashboard"]), icon: "po-icon-chart-area", shortLabel: "Dash" },
      { 
        label: "Vendas e CRM", 
        icon: "po-icon-finance", 
        subItems: [
          { label: "Análise CRM (MVC)", action: () => this.router.navigate(["/mvc"]), icon: "po-icon-vendas" },
          { label: "Objetivos e Metas", action: () => this.router.navigate(["/metas"]), icon: "po-icon-target" },
          { label: "Gestão de Equipe", action: () => this.router.navigate(["/vendedores"]), icon: "po-icon-user" },
          { label: "Clientes", action: () => this.router.navigate(["/clientes"]), icon: "po-icon-users" }
        ]
      },
      { 
        label: "Segurança e Acesso", 
        icon: "po-icon-security", 
        subItems: [
          { label: "Usuários", action: () => this.router.navigate(["/admin/users"]), icon: "po-icon-user-add" },
          { label: "Grupos de Permissão", action: () => this.router.navigate(["/admin/groups"]), icon: "po-icon-users" }
        ]
      }
    ];

    items.push({ 
      label: "Sair", 
      action: () => {
        this.authService.logout();
        this.router.navigate(["/login"]);
      }, 
      icon: "po-icon-exit" 
    });

    return items;
  }

}

