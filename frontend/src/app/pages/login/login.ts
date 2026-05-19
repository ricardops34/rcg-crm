import { Component, ViewChild, inject, signal, ViewEncapsulation } from "@angular/core";
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
  templateUrl: "./login.html",
  styleUrl: "./login.css",
  encapsulation: ViewEncapsulation.None
})
export class LoginComponent {
  @ViewChild("modal2fa", { static: true }) modal2fa!: PoModalComponent;
  @ViewChild("modalTerms", { static: true }) modalTerms!: PoModalComponent;

  private authService = inject(AuthService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  twoFactorCode: string = "";
  isLoading = signal<boolean>(false);

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
      this.poNotification.success("Bem-vindo ao RCG CRM!");
      this.router.navigate(["/"]);
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
