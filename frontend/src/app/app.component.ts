import { Component, OnInit, inject, effect, ViewChild, ChangeDetectorRef } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { Router, RouterOutlet, NavigationEnd } from "@angular/router";
import {
  PoMenuItem,
  PoModule,
  PoToolbarAction,
  PoToolbarProfile,
  PoNotificationService,
  PoThemeService,
  PoThemeTypeEnum,
  PoThemeA11yEnum,
  PoModalComponent,
  PoSelectOption,
  PoHeaderActionTool,
  PoHeaderBrand,
  PoHeaderUser
} from "@po-ui/ng-components";
import { filter } from "rxjs/operators";
import { AuthService } from "./services/auth";
import { rcgPoUiTheme } from "../temas/rcg/rcg-theme";
import { alliaPoUiTheme } from "../temas/allia/allia-theme";
import { RoutesRegistryService } from "./services/routes-registry.service";
import { ParameterService } from "./services/parameter";

@Component({
  selector: "app-root",
  standalone: true,
  imports: [CommonModule, RouterOutlet, PoModule, FormsModule],
  templateUrl: "./app.component.html"
})
export class AppComponent implements OnInit {
  @ViewChild('modalUnit', { static: true }) modalUnit!: PoModalComponent;

  private router = inject(Router);
  public authService = inject(AuthService);
  private poNotification = inject(PoNotificationService);
  private themeService = inject(PoThemeService);
  private routesRegistry = inject(RoutesRegistryService);
  public parameterService = inject(ParameterService);
  private cdr = inject(ChangeDetectorRef);

  user: any;
  logo: string = "logo_padrao.png";
  currentTheme: string = "rcg";
  dynamicMenus: Array<PoMenuItem> = [];
  menuItems: Array<PoMenuItem> = [];
  isLoginPage: boolean = true;
  selectedUnitId!: number;
  allowedUnitOptions: Array<PoSelectOption> = [];
  toolbarTitle: string = "CRM";

  headerBrand: PoHeaderBrand = {
    title: 'CRM',
    logo: 'logo_padrao.png',
    action: () => this.router.navigate(["/home"])
  };

  headerUser: PoHeaderUser = {
    avatar: 'assets/avatar/avatar_00.png',
    customerBrand: 'logo_bj.png',
    action: () => this.router.navigate(["/profile"])
  };

  headerActionTools: Array<PoHeaderActionTool> = [];

  constructor() {
    effect(() => {
      const user = this.authService.currentUser();
      const sysName = this.parameterService.systemName();
      
      if (user && user.unit) {
        if (user.unit.logo) {
          this.logo = user.unit.logo;
        } else {
          this.logo = "logo_bj.png";
        }
        this.toolbarTitle = `${sysName} - ${user.unit.name || ''}`;
        this.updateFavicon(user.unit.favicon);

        this.headerBrand = {
          title: this.toolbarTitle,
          logo: this.logo,
          action: () => this.router.navigate(["/home"])
        };

        this.headerUser = {
          avatar: user.avatar || "assets/avatar/avatar_00.png",
          customerBrand: this.logo,
          action: () => this.router.navigate(["/profile"])
        };

        // Gerar as ferramentas da barra superior dinamicamente
        const tools: Array<PoHeaderActionTool> = [];

        // Exibe o chaveamento de filial apenas se possuir 2 ou mais filiais autorizadas
        if (user.allowedUnits && user.allowedUnits.length > 1) {
          tools.push({
            label: 'Trocar Filial',
            icon: 'an an-arrows-clockwise',
            tooltip: 'Trocar unidade de trabalho',
            action: () => this.openUnitSwitchModal()
          });
        }

        tools.push({
          label: 'Alterar Tema',
          icon: 'an an-paint-brush',
          tooltip: 'Alternar tema visual',
          action: () => this.toggleTheme()
        });

        // Adiciona engrenagem de Configurações APENAS para perfil ADMIN
        if (this.authService.isAdmin()) {
          tools.push({
            label: 'Configurações',
            icon: 'an an-gear-six',
            tooltip: 'Configurações do sistema',
            action: () => this.router.navigate(["/admin/settings"])
          });
        }

        tools.push({
          label: 'Sair',
          icon: 'an an-sign-out',
          tooltip: 'Sair do sistema',
          action: () => this.logout()
        });

        this.headerActionTools = tools;
      } else {
        this.logo = "logo_padrao.png";
        this.toolbarTitle = "CRM";
        this.updateFavicon(null);

        this.headerBrand = {
          title: this.toolbarTitle,
          logo: this.logo,
          action: () => this.router.navigate(["/home"])
        };

        this.headerUser = {
          avatar: "assets/avatar/avatar_00.png",
          customerBrand: 'logo_padrao.png',
          action: () => this.router.navigate(["/profile"])
        };

        this.headerActionTools = [];
      }
    });
  }

  updateFavicon(faviconBase64: string | null | undefined) {
    if (typeof document !== 'undefined') {
      const faviconElement = document.getElementById("app-favicon") as HTMLLinkElement ||
        document.querySelector("link[rel*='icon']") as HTMLLinkElement;

      const defaultFavicon = "favicon.ico";

      if (faviconElement) {
        faviconElement.href = faviconBase64 ? faviconBase64 : defaultFavicon;
      } else {
        const link = document.createElement('link');
        link.id = 'app-favicon';
        link.rel = 'icon';
        link.type = 'image/x-icon';
        link.href = faviconBase64 ? faviconBase64 : defaultFavicon;
        document.head.appendChild(link);
      }
    }
  }

  openUnitSwitchModal() {
    const user = this.authService.currentUser();
    if (user && user.allowedUnits && user.allowedUnits.length > 0) {
      this.allowedUnitOptions = user.allowedUnits.map((u: any) => ({
        label: u.name,
        value: u.id
      }));
      this.selectedUnitId = user.unit?.id || 0;
      this.modalUnit.open();
    } else {
      this.poNotification.warning("Nenhuma filial adicional autorizada para o seu usuário.");
    }
  }

  confirmUnitSwitch() {
    if (!this.selectedUnitId) return;
    this.modalUnit.close();

    this.authService.switchUnit(this.selectedUnitId).subscribe({
      next: () => {
        this.poNotification.success("Filial alterada com sucesso!");
        this.refreshUserInfo();
        this.loadMenu();
        this.router.navigate(["/home"]);
      },
      error: () => {
        this.poNotification.error("Erro ao alterar filial de trabalho. Verifique suas permissões.");
      }
    });
  }

  // Perfil do usuário logado
  profile: PoToolbarProfile = {
    avatar: "assets/avatar/avatar_00.png",
    subtitle: "",
    title: "Carregando..."
  };

  readonly profileActions: Array<PoToolbarAction> = [
    { label: "Meu Perfil", action: () => this.router.navigate(["/profile"]), icon: "an an-user-circle" },
    { label: "Sair", action: () => this.logout(), icon: "an an-sign-out", type: "danger" }
  ];

  // Ações da toolbar conforme modelo menu superior.png
  readonly toolbarActions: Array<PoToolbarAction> = [
    { label: "Trocar Filial", icon: "an an-arrows-clockwise", action: () => this.openUnitSwitchModal() },
    { label: "Apps", icon: "an an-grid-four", action: () => { } },
    { label: "Mensagens", icon: "an an-chat-circle", action: () => { }, type: "danger" },
    { label: "Alterar Tema", icon: "an an-paint-brush", action: () => this.toggleTheme() }
  ];

  ngOnInit() {
    this.checkRoute();
    this.refreshUserInfo();
    this.loadTheme();

    if (this.authService.isAuthenticated()) {
      this.loadMenu();
      this.parameterService.loadDefaultParameters();
    }

    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      this.checkRoute();
      if (!this.isLoginPage && this.authService.isAuthenticated()) {
        this.refreshUserInfo();
        this.loadMenu();
        this.parameterService.loadDefaultParameters();
      }
      this.cdr.detectChanges();
    });
  }

  checkRoute() {
    const url = this.router.url.split('?')[0];
    this.isLoginPage = url === "/login" || url === "/" || url === "";
  }

  refreshUserInfo() {
    this.user = this.authService.getUser();
    if (this.user) {
      this.profile = {
        title: this.user.name || this.user.login,
        subtitle: this.user.email || "",
        avatar: this.user.avatar || "assets/avatar/avatar_00.png"
      };
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
        this.updateMenu();
      },
      error: () => console.warn("Erro ao carregar menu dinâmico.")
    });
  }

  navigateTo(controller: string) {
    const path = this.routesRegistry.getPathByController(controller);
    if (path) {
      this.router.navigate([path]);
    } else if (controller && (controller.startsWith('/') || controller.includes('/'))) {
      this.router.navigate([controller]);
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

  updateMenu() {
    const items: Array<PoMenuItem> = [
      { label: "Home", action: () => this.router.navigate(["/home"]), icon: "an an-house", shortLabel: "Home" },
    ];

    if (this.dynamicMenus.length > 0) {
      items.push(...this.dynamicMenus);
    }

    items.push({ label: "Sair", action: () => this.logout(), icon: "an an-sign-out", shortLabel: "Sair", type: "danger" });
    this.menuItems = items;
  }

  logout() {
    this.authService.logout();
    this.router.navigate(["/login"]);
    this.dynamicMenus = [];
    this.isLoginPage = true;
  }
}
