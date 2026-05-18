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

  readonly menus: Array<PoMenuItem> = [
    { label: "Dashboard", action: () => this.router.navigate(["/dashboard"]), icon: "po-icon-chart-area", shortLabel: "Dash" },
    { label: "Vendas", icon: "po-icon-finance", subItems: [
      { label: "MVC (M�dia de Venda)", action: () => this.router.navigate(["/mvc"]) },
      { label: "Objetivos e Metas", action: () => this.router.navigate(["/metas"]) },
      { label: "Or�amentos", action: () => {} }
    ]},
    { label: "Cadastros", icon: "po-icon-users", subItems: [
      { label: "Clientes", action: () => this.router.navigate(["/clientes"]) },
      { label: "Vendedores", action: () => this.router.navigate(["/vendedores"]) }
    ]},
    { label: "Sair", action: () => {
      this.authService.logout();
      this.router.navigate(["/login"]);
    }, icon: "po-icon-exit" }
  ];

}
