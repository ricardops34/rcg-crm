import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router, RouterOutlet } from "@angular/router";
import { 
  PoMenuItem, 
  PoModule, 
  PoToolbarAction, 
  PoToolbarProfile, 
  PoNotificationService 
} from "@po-ui/ng-components";
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
  private poNotification = inject(PoNotificationService);

  user: any;
  logo: string = "assets/logo_rcg.png";
  currentTheme: string = "light";
  dynamicMenus: Array<PoMenuItem> = [];

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
      icon: "po-icon-as-light-mode", 
      action: () => this.toggleTheme() 
    }
  ];

  ngOnInit() {
    this.refreshUserInfo();
    this.loadTheme();
    if (this.authService.isAuthenticated()) {
      this.loadMenu();
    }
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
    this.currentTheme = this.currentTheme === "light" ? "dark" : "light";
    localStorage.setItem("theme", this.currentTheme);
    this.applyTheme();
    
    const themeAction = this.toolbarActions.find(a => a.label === "Alterar Tema");
    if (themeAction) {
      themeAction.icon = this.currentTheme === "light" ? "po-icon-as-light-mode" : "po-icon-as-dark-mode";
    }
  }

  loadTheme() {
    this.currentTheme = localStorage.getItem("theme") || "light";
    this.applyTheme();
  }

  applyTheme() {
    if (this.currentTheme === "dark") {
      document.body.classList.add("po-theme-dark");
    } else {
      document.body.classList.remove("po-theme-dark");
    }
  }

  get menus(): Array<PoMenuItem> {
    const items: Array<PoMenuItem> = [
      { label: "Início", action: () => this.router.navigate(["/dashboard"]), icon: "po-icon-home", shortLabel: "Home" },
    ];

    if (this.dynamicMenus.length > 0) {
      items.push(...this.dynamicMenus);
    }

    // BYPASS PARA ADMINISTRADOR: Garante que as rotas administrativas apareçam
    const isAdmin = this.user?.roles?.includes('ADMIN') || this.user?.login === 'admin' || this.user?.isGerente;

    if (isAdmin) {
      items.push({ 
        label: "Segurança e Acesso", 
        icon: "po-icon-security", 
        subItems: [
          { label: "Meu Perfil", action: () => this.router.navigate(["/profile"]), icon: "po-icon-user" },
          { label: "Usuários", action: () => this.router.navigate(["/admin/users"]), icon: "po-icon-user-add" },
          { label: "Perfis de Acesso", action: () => this.router.navigate(["/admin/groups"]), icon: "po-icon-users" },
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
  }
}
