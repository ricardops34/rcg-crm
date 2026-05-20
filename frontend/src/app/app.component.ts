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

  user: any;
  logo: string = "assets/logo_rcg.png";
  currentTheme: string = "rcg";
  dynamicMenus: Array<PoMenuItem> = [];
  isLoginPage: boolean = true;

  readonly profile: PoToolbarProfile = {
    avatar: "",
    subtitle: "",
    title: ""
  };

  readonly profileActions: Array<PoToolbarAction> = [
    { label: "Meu Perfil", action: () => this.router.navigate(["/profile"]), icon: "po-icon-user" },
    { label: "Sair", action: () => this.logout(), icon: "po-icon-exit", type: "danger" }
  ];

  readonly toolbarActions: Array<PoToolbarAction> = [
    { 
      label: "Notificações", 
      icon: "po-icon-notification", 
      action: () => this.poNotification.information("Você não possui novas notificações.") 
    },
    { 
      label: "Alterar Tema", 
      icon: "po-icon-pallet", 
      action: () => this.toggleTheme() 
    }
  ];

  private readonly themeService = inject(PoThemeService);

  ngOnInit() {
    this.checkRoute();
    this.refreshUserInfo();
    this.loadTheme();
    
    if (this.authService.isAuthenticated()) {
      this.loadMenu();
    }

    // Monitorar mudanças de rota para ocultar/exibir o shell
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
    this.isLoginPage = this.router.url === "/login" || this.router.url === "/" || this.router.url === "";
  }

  refreshUserInfo() {
    this.user = this.authService.getUser();
    console.log("[APP] Usuário atual:", this.user);
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
          icon: module.icon || "po-icon-more",
          subItems: module.subItems.map((program: any) => ({
            label: program.label,
            action: () => this.navigateTo(program.action),
            icon: program.icon || "po-icon-circle"
          }))
        }));
      },
      error: () => {
        console.warn("Falha ao carregar menu dinâmico.");
      }
    });
  }

  navigateTo(controller: string) {
    const routes: any = {
      "DashboardVendedor": "/dashboard",
      "MvcList": "/mvc",
      "ClienteList": "/clientes",
      "MetaVendedorMesList": "/metas",
      "VendedorList": "/vendedores",
      "SystemUserList": "/admin/users",
      "SystemGroupList": "/admin/groups",
      "SystemUnitList": "/admin/units",
      "SystemModuleList": "/admin/modules",
      "SystemProgramList": "/admin/programs",
      "SystemProfileForm": "/profile"
    };

    const path = routes[controller];
    if (path) {
      this.router.navigate([path]);
    } else {
      this.poNotification.warning(`Rota para o controlador ${controller} ainda não implementada.`);
    }
  }

  toggleTheme() {
    if (this.currentTheme === "rcg") {
      this.currentTheme = "allia";
    } else {
      this.currentTheme = "rcg";
    }
    localStorage.setItem("theme", this.currentTheme);
    this.applyTheme();
    
    const themeAction = this.toolbarActions.find(a => a.label.startsWith("Tema:") || a.label === "Alterar Tema");
    if (themeAction) {
      themeAction.label = `Tema: ${this.currentTheme.toUpperCase()}`;
    }
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
      { label: "Início", action: () => this.router.navigate(["/dashboard"]), icon: "po-icon-home", shortLabel: "Home" },
    ];

    if (this.dynamicMenus.length > 0) {
      items.push(...this.dynamicMenus);
    }

    const isAdmin = this.user?.roles?.includes('ADMIN') || this.user?.login === 'admin' || this.user?.isGerente;

    if (isAdmin) {
      items.push({ 
        label: "Segurança e Acesso", 
        icon: "po-icon-security", 
        subItems: [
          { label: "Meu Perfil", action: () => this.router.navigate(["/profile"]), icon: "po-icon-user" },
          { label: "Usuários", action: () => this.router.navigate(["/admin/users"]), icon: "po-icon-user-add" },
          { label: "Perfis de Acesso", action: () => this.router.navigate(["/admin/groups"]), icon: "po-icon-users" },
          { label: "Unidades", action: () => this.router.navigate(["/admin/units"]), icon: "po-icon-company" },
          { label: "Módulos do Sistema", action: () => this.router.navigate(["/admin/modules"]), icon: "po-icon-vendas" },
          { label: "Rotinas do Sistema", action: () => this.router.navigate(["/admin/programs"]), icon: "po-icon-xml" }
        ]
      });
    }

    return items;
  }

  logout() {
    this.authService.logout();
    this.router.navigate(["/login"]);
    this.dynamicMenus = [];
    this.isLoginPage = true;
  }
}
