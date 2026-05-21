import { Component, ViewChild, inject, signal, ViewEncapsulation, OnInit } from "@angular/core";
import { Router, ActivatedRoute } from "@angular/router";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { PoPageLogin, PoPageLoginModule } from "@po-ui/ng-templates";
import { PoNotificationService, PoModalComponent, PoModule } from "@po-ui/ng-components";
import { AuthService } from "../../services/auth";

@Component({
  selector: "app-login",
  standalone: true,
  imports: [CommonModule, FormsModule, PoPageLoginModule, PoModule],
  templateUrl: "./login.html",
  styleUrl: "./login.css",
  encapsulation: ViewEncapsulation.None
})
export class LoginComponent implements OnInit {
  @ViewChild("modal2fa", { static: true }) modal2fa!: PoModalComponent;
  @ViewChild("modalTerms", { static: true }) modalTerms!: PoModalComponent;

  private authService = inject(AuthService);
  private router = inject(Router);
  private route = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  twoFactorCode: string = "";
  termsAccepted: boolean = false;
  termsContent: string = "";
  termsVersion: string = "";
  isLoading = signal<boolean>(false);

  ngOnInit() {
    this.loadTerms();
    this.checkSessionError();
  }

  checkSessionError() {
    this.route.queryParams.subscribe(params => {
      if (params['error'] === 'session') {
        this.poNotification.warning("Sessão expirada ou iniciada em outro dispositivo.");
      }
    });
  }

  loadTerms() {
    this.authService.getTerms().subscribe(res => {
      this.termsContent = res.text;
      this.termsVersion = res.version;
    });
  }

  loginSubmit(formData: PoPageLogin) {
    this.isLoading.set(true);
    this.authService.login({ login: formData.login, password: formData.password }).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.handleNextStep(res);
      },
      error: (err) => {
        this.isLoading.set(false);
        this.poNotification.error("Usuário ou senha incorretos.");
      }
    });
  }

  handleNextStep(res: any) {
    if (res.nextStep === "2FA") {
      this.modal2fa.open();
    } else if (res.nextStep === "TERMS") {
      this.modalTerms.open();
    } else {
      // Redirecionamento Inteligente (Baseado no Legado)
      const user = res.user;
      const frontpage = user?.frontpage?.controller;
      
      this.poNotification.success(`Bem-vindo, ${user?.name}!`);

      if (frontpage) {
        const routes: any = {
          "DashboardVendedor": "/dashboard",
          "MvcList": "/mvc",
          "ClienteList": "/clientes",
          "SystemUserList": "/admin/users"
        };
        const target = routes[frontpage] || "/dashboard";
        this.router.navigate([target]);
      } else {
        this.router.navigate(["/dashboard"]);
      }
    }
  }

  confirm2fa() {
    this.isLoading.set(true);
    this.authService.verify2fa(this.twoFactorCode).subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.modal2fa.close();
        this.handleNextStep(res);
      },
      error: () => {
        this.isLoading.set(false);
        this.poNotification.error("Código 2FA inválido.");
      }
    });
  }

  confirmTerms() {
    this.isLoading.set(true);
    this.authService.acceptTerms().subscribe({
      next: (res) => {
        this.isLoading.set(false);
        this.modalTerms.close();
        this.handleNextStep(res);
      },
      error: () => {
        this.isLoading.set(false);
        this.poNotification.error("Erro ao aceitar termos.");
      }
    });
  }
}
