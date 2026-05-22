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
    { label: "Meu Perfil", action: () => this.router.navigate(["/profile"]), icon: "po-icon-user" },
    { label: "Configurações", action: () => this.router.navigate(["/admin/settings"]), icon: "po-icon-settings" },
    { label: "Sair", action: () => this.logout(), icon: "po-icon-exit", type: "danger" }
  ];

  // Ações da toolbar conforme modelo menu superior.png
  readonly toolbarActions: Array<PoToolbarAction> = [
    { label: "Configurações", icon: "po-icon-settings", action: () => {} },
    { label: "Apps", icon: "po-icon-grid", action: () => {} },
    { label: "Mensagens", icon: "po-icon-chat", action: () => {}, type: "danger" },
    { label: "Alterar Tema", icon: "po-icon-pallet", action: () => this.toggleTheme() }
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
    const routes: any = {
      "DashboardVendedor": "/dashboard",
      "MvcList": "/mvc",
      "ClienteList": "/clientes",
      "PosisaoClienteFormView": "/clientes/360",
      "AgendaAtendimentoList": "/agenda-atendimento",
      "MetaVendedorMesList": "/metas",
      "VendedorList": "/vendedores",
      "ProdutoList": "/produtos",
      "TabelaPrecoList": "/tabelas-precos",
      "SystemUserList": "/admin/users",
      "SystemGroupList": "/admin/groups",
      "SystemUnitList": "/admin/units",
      "SystemModuleList": "/admin/modules",
      "SystemProgramList": "/admin/programs",
      "SystemProfileForm": "/profile",
      "CategoriaList": "/categorias",
      "PedidoEstadoList": "/pedidos/estados"
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
      { label: "Home", action: () => this.router.navigate(["/dashboard"]), icon: "po-icon-home", shortLabel: "Home" },
    ];

    if (this.authService.isAuthenticated() && this.authService.hasPermission('MvcList')) {
      items.push({
        label: "Agenda Comercial",
        action: () => this.router.navigate(["/agenda-atendimento"]),
        icon: "po-icon-calendar",
        shortLabel: "Agenda"
      });
    }

    if (this.dynamicMenus.length > 0) {
      items.push(...this.dynamicMenus);
    }

    // Módulos Fiscais e Logísticos (Sempre visíveis se o menu dinâmico não trouxer)
    if (this.dynamicMenus.length === 0 || !this.dynamicMenus.some(m => m.label === 'Faturamento')) {
      items.push({ 
        label: "Faturamento", 
        shortLabel: "Fiscal",
        icon: "po-icon-finance", 
        subItems: [
          { label: "Notas Fiscais", action: () => this.router.navigate(["/faturamento/notas"]), icon: "po-icon-document", shortLabel: "Notas" },
          { label: "Comodatos", action: () => this.router.navigate(["/faturamento/comodatos"]), icon: "po-icon-box", shortLabel: "Comod" }
        ]
      });
    }

    const isAdmin = this.user?.roles?.includes('ADMIN') || this.user?.login === 'admin';
    if (isAdmin) {
      items.push({ 
        label: "Administração", 
        shortLabel: "Admin",
        icon: "po-icon-settings", 
        subItems: [
          { label: "Usuários", action: () => this.router.navigate(["/admin/users"]), icon: "po-icon-user", shortLabel: "Users" },
          { label: "Perfis de Acesso", action: () => this.router.navigate(["/admin/groups"]), icon: "po-icon-users", shortLabel: "Perfis" },
          { label: "Unidades", action: () => this.router.navigate(["/admin/units"]), icon: "po-icon-company", shortLabel: "Units" },
          { label: "Módulos", action: () => this.router.navigate(["/admin/modules"]), icon: "po-icon-vendas", shortLabel: "Módulos" },
          { label: "Rotinas", action: () => this.router.navigate(["/admin/programs"]), icon: "po-icon-xml", shortLabel: "Rotinas" }
        ]
      });
    }

    items.push({ label: "Sair", action: () => this.logout(), icon: "po-icon-exit", shortLabel: "Sair", type: "danger" });
    return items;
  }

  logout() {
    this.authService.logout();
    this.router.navigate(["/login"]);
    this.dynamicMenus = [];
    this.isLoginPage = true;
  }
}
