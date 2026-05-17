import { Component } from "@angular/core";
import { Router } from "@angular/router";
import { PoPageLogin, PoPageLoginModule } from "@po-ui/ng-templates";
import { PoNotificationService } from "@po-ui/ng-components";
import { AuthService } from "../../services/auth";

@Component({
  selector: "app-login",
  standalone: true,
  imports: [PoPageLoginModule],
  templateUrl: "./login.html"
})
export class LoginComponent {

  constructor(
    private authService: AuthService,
    private router: Router,
    private poNotification: PoNotificationService
  ) {}

  loginSubmit(formData: PoPageLogin) {
    this.authService.login({ login: formData.login, password: formData.password }).subscribe({
      next: () => {
        this.poNotification.success("Bem-vindo ao RCG CRM!");
        this.router.navigate(["/"]);
      },
      error: (err) => {
        this.poNotification.error("Usuário ou senha incorretos.");
      }
    });
  }
}
