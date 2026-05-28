import { Component, ViewChild, inject, signal, ViewEncapsulation, OnInit } from "@angular/core";
import { Router, ActivatedRoute } from "@angular/router";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { PoPageLogin, PoPageLoginModule } from "@po-ui/ng-templates";
import { PoNotificationService, PoModalComponent, PoModule } from "@po-ui/ng-components";
import { AuthService } from "../../services/auth";
import { LoginUnitOption } from "../../services/models/auth.model";

@Component({
  selector: "app-login",
  standalone: true,
  imports: [CommonModule, FormsModule, PoPageLoginModule, PoModule],
  templateUrl: "./login.html",
  styleUrl: "./login.css",
  encapsulation: ViewEncapsulation.None
})
export class LoginComponent implements OnInit {
  private readonly defaultLoginLogo = "logo_bj.png";
  readonly secondaryLoginLogo = "logo_bj.png";

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
  loginLogo: string = this.authService.getLoginLogo();
  loginUnits: LoginUnitOption[] = [];
  selectedUnitId?: number;
  loginUnitField?: {
    property: string;
    value?: number;
    placeholder: string;
    options: Array<{ label: string; value: number }>;
  };
  isLoading = signal<boolean>(false);

  readonly customLiterals = {
    loginHint: ' ',
    customFieldPlaceholder: 'Selecione a unidade',
    welcome: 'Bem-vindo'
  };

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
    const selectedUnitId = (formData as PoPageLogin & { systemUnitId?: number }).systemUnitId;
    this.selectedUnitId = selectedUnitId;
    this.authService.login({
      login: formData.login,
      password: formData.password,
      systemUnitId: selectedUnitId
    }).subscribe({
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

  onLoginChange(login: string) {
    const normalizedLogin = (login || "").trim();

    if (normalizedLogin.length < 3) {
      this.resetUnitSelector();
      return;
    }

    this.authService.getUnitsByLogin(normalizedLogin).subscribe({
      next: (units) => {
        this.loginUnits = units;

        if (units.length <= 1) {
          this.selectedUnitId = units[0]?.value;
          this.loginUnitField = undefined;
          this.updateLoginLogoFromSelection();
          return;
        }

        const preferredUnitId = this.selectedUnitId && units.some((unit) => unit.value === this.selectedUnitId)
          ? this.selectedUnitId
          : units[0].value;

        this.selectedUnitId = preferredUnitId;
        this.loginUnitField = {
          property: "systemUnitId",
          value: preferredUnitId,
          placeholder: "Selecione a unidade",
          options: units.map((unit) => ({
            label: unit.label,
            value: unit.value
          }))
        };
        this.updateLoginLogoFromSelection();
      },
      error: () => {
        this.resetUnitSelector();
      }
    });
  }

  private updateLoginLogoFromSelection() {
    const selectedUnit = this.loginUnits.find((unit) => unit.value === this.selectedUnitId);
    this.loginLogo = selectedUnit?.logo || this.authService.getLoginLogo() || this.defaultLoginLogo;
  }

  private resetUnitSelector() {
    this.loginUnits = [];
    this.selectedUnitId = undefined;
    this.loginUnitField = undefined;
    this.loginLogo = this.authService.getLoginLogo() || this.defaultLoginLogo;
  }

  handleNextStep(res: any) {
    if (res.nextStep === "2FA") {
      this.modal2fa.open();
    } else if (res.nextStep === "TERMS") {
      this.modalTerms.open();
    } else {
      const user = res.user;
      this.loginLogo = this.authService.getLoginLogo();
      this.poNotification.success(`Bem-vindo, ${user?.name}!`);
      this.router.navigate(["/home"]);
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
