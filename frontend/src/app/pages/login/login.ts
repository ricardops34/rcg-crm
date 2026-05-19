import { Component, ViewChild } from "@angular/core";
import { Router } from "@angular/router";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { PoPageLogin, PoPageLoginModule } from "@po-ui/ng-templates";
import { PoNotificationService, PoModalComponent, PoModule } from "@po-ui/ng-components";
import { AuthService } from "../../services/auth";

@Component({
  selector: "app-login",
  standalone: true,
  imports: [CommonModule, FormsModule, PoPageLoginModule, PoModule],
  templateUrl: "./login.html"
})
export class LoginComponent {
  @ViewChild("modal2fa", { static: true }) modal2fa!: PoModalComponent;
  @ViewChild("modalTerms", { static: true }) modalTerms!: PoModalComponent;

  twoFactorCode: string = "";
  isLoading: boolean = false;

  constructor(
    private authService: AuthService,
    private router: Router,
    private poNotification: PoNotificationService
  ) {}

  loginSubmit(formData: PoPageLogin) {
    this.isLoading = true;
    this.authService.login({ login: formData.login, password: formData.password }).subscribe({
      next: (res) => {
        this.isLoading = false;
        this.handleNextStep(res);
      },
      error: (err) => {
        this.isLoading = false;
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
      this.poNotification.success("Bem-vindo ao RCG CRM!");
      this.router.navigate(["/"]);
    }
  }

  confirm2fa() {
    this.isLoading = true;
    this.authService.verify2fa(this.twoFactorCode).subscribe({
      next: (res) => {
        this.isLoading = false;
        this.modal2fa.close();
        this.handleNextStep(res);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Código 2FA inválido.");
      }
    });
  }

  confirmTerms() {
    this.isLoading = true;
    this.authService.acceptTerms().subscribe({
      next: (res) => {
        this.isLoading = false;
        this.modalTerms.close();
        this.handleNextStep(res);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao aceitar termos.");
      }
    });
  }
}
