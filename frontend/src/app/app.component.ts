import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router, RouterOutlet, NavigationEnd } from "@angular/router";
import { 
  PoMenuItem, 
  PoModule, 
  PoToolbarAction, 
  PoToolbarProfile, 
  PoNotificationService,
  PoThemeService,
  PoThemeTypeEnum,
  PoThemeA11yEnum
} from "@po-ui/ng-components";
import { filter } from "rxjs/operators";
import { AuthService } from "./services/auth";
import { rcgPoUiTheme } from "../temas/rcg/rcg-theme";
import { alliaPoUiTheme } from "../temas/allia/allia-theme";

@Component({
  selector: "app-root",
  standalone: true,
  imports: [CommonModule, RouterOutlet, PoModule],
  templateUrl: "./app.component.html"
})
export class AppComponent implements OnInit {
  private router = inject(Router);
  public authService = inject(AuthService);
  private poNotification = inject(PoNotificationService);
  private readonly themeService = inject(PoThemeService);

  user: any;
  logo: string = "assets/logo_rcg.png";
  currentTheme: string = "rcg";
  dynamicMenus: Array<PoMenuItem> = [];
  isLoginPage: boolean = true;

  // Perfil conforme modelo menu superior.png
  readonly profile: PoToolbarProfile = {
    avatar: "assets/default-avatar.png",
    subtitle: "informatica@rcgdist.com.br",
    title: "Administrator"
  };

  readonly profileActions: Array<PoToolbarAction> = [
    { label: "Meu Perfil", action: () => this.router.navigate(["/profile"]), icon: "an an-user-circle" },
    { label: "Configurações", action: () => this.router.navigate(["/admin/settings"]), icon: "an an-gear-six" },
    { label: "Sair", action: () => this.logout(), icon: "an an-sign-out", type: "danger" }
  ];

  // Ações da toolbar conforme modelo menu superior.png
  readonly toolbarActions: Array<PoToolbarAction> = [
    { label: "Configurações", icon: "an an-gear-six", action: () => {} },
    { label: "Apps", icon: "an an-grid-four", action: () => {} },
    { label: "Mensagens", icon: "an an-chat-circle", action: () => {}, type: "danger" },
    { label: "Alterar Tema", icon: "an an-paint-brush", action: () => this.toggleTheme() }
  ];

  ngOnInit() {
    this.checkRoute();
    this.refreshUserInfo();
    this.loadTheme();
    
    if (this.authService.isAuthenticated()) {
      this.loadMenu();
    }

    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      this.checkRoute();
      if (!this.isLoginPage && this.authService.isAuthenticated()) {
        this.refreshUserInfo();
        this.loadMenu();
      }
    });
  }

  checkRoute() {
    const url = this.router.url.split('?')[0];
    this.isLoginPage = url === "/login" || url === "/" || url === "";
  }

  refreshUserInfo() {
    this.user = this.authService.getUser();
    if (this.user) {
      this.profile.title = this.user.name;
      this.profile.subtitle = this.user.email;
      this.profile.avatar = this.user.avatar || "assets/default-avatar.png";
    }
  }

  loadMenu() {
    this.authService.getMenu().subscribe({
      next: (res) => {
        this.dynamicMenus = res.map((module: any) => ({
          label: module.label,
          shortLabel: module.label.substring(0, 10),
          icon: module.icon || "po-icon-more",
          subItems: module.subItems.map((program: any) => ({
            label: program.label,
            shortLabel: program.label.substring(0, 10),
            action: () => this.navigateTo(program.action),
            icon: program.icon || "po-icon-circle"
          }))
        }));
      },
      error: () => console.warn("Erro ao carregar menu dinâmico.")
    });
  }

  navigateTo(controller: string) {
    const routes: Record<string, string> = {
      // CRM
      "DashboardVendedor":      "/dashboard",
      "MvcList":                "/mvc",
      "ClienteList":            "/clientes",
      "PosisaoClienteFormView": "/clientes/360",
      "AgendaAtendimentoList":  "/agenda-atendimento",
      "MetaList":               "/metas",
      "NegociacaoList":         "/financeiro/negociacoes",
      // Cadastros
      "VendedorList":           "/vendedores",
      "ProdutoList":            "/produtos",
      "TabelaPrecoList":        "/tabelas-precos",
      "CategoriaList":          "/categorias",
      "PedidoEstadoList":       "/pedidos/estados",
      // Financeiro
      "TituloReceberList":      "/financeiro/titulos",
      // Faturamento
      "NotaFiscalList":         "/faturamento/notas",
      "ComodatoList":           "/faturamento/comodatos",
      // Administração
      "SystemUserList":         "/admin/users",
      "SystemGroupList":        "/admin/groups",
      "SystemUnitList":         "/admin/units",
      "SystemModuleList":       "/admin/modules",
      "SystemProgramList":      "/admin/programs",
      "SystemProfileForm":      "/profile",
    };

    const path = routes[controller];
    if (path) {
      this.router.navigate([path]);
    } else {
      this.poNotification.warning(`A rota para ${controller} está sendo mapeada ou não foi implementada.`);
    }
  }

  toggleTheme() {
    this.currentTheme = this.currentTheme === "rcg" ? "allia" : "rcg";
    localStorage.setItem("theme", this.currentTheme);
    this.applyTheme();
  }

  loadTheme() {
    this.currentTheme = localStorage.getItem("theme") || "rcg";
    this.applyTheme();
  }

  applyTheme() {
    if (this.currentTheme === "allia") {
      this.themeService.setTheme(alliaPoUiTheme, PoThemeTypeEnum.light, PoThemeA11yEnum.AAA, true);
    } else {
      this.themeService.setTheme(rcgPoUiTheme, PoThemeTypeEnum.light, PoThemeA11yEnum.AAA, true);
    }
  }

  get menus(): Array<PoMenuItem> {
    const items: Array<PoMenuItem> = [
      { label: "Home", action: () => this.router.navigate(["/dashboard"]), icon: "an an-house", shortLabel: "Home" },
    ];

    if (this.dynamicMenus.length > 0) {
      items.push(...this.dynamicMenus);
    }

    items.push({ label: "Sair", action: () => this.logout(), icon: "an an-sign-out", shortLabel: "Sair", type: "danger" });
    return items;
  }

  logout() {
    this.authService.logout();
    this.router.navigate(["/login"]);
    this.dynamicMenus = [];
    this.isLoginPage = true;
  }
}
