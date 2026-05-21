import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { 
  PoModule, 
  PoPageAction, 
  PoNotificationService 
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { AuthService } from "../../../services/auth";

@Component({
  selector: "app-terms-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit 
      p-title="Gestão de Termos de Uso e LGPD"
      (p-save)="save()"
      (p-cancel)="cancel()">
      
      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <form #termsForm="ngForm">
        <div class="po-row">
          <po-input class="po-md-2" name="version" [(ngModel)]="terms.version" p-label="Versão Atual" [p-required]="true"></po-input>
          <div class="po-md-10"></div>
        </div>

        <div class="po-row">
          <po-rich-text 
            class="po-md-12" 
            name="text" 
            [(ngModel)]="terms.text" 
            p-label="Texto dos Termos (HTML aceito)"
            p-help="Este texto será exibido no modal de login para todos os usuários."
            [p-required]="true">
          </po-rich-text>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class TermsFormComponent implements OnInit {
  private authService = inject(AuthService);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  terms: any = { text: "", version: "" };
  isLoading: boolean = false;

  ngOnInit() {
    this.loadTerms();
  }

  loadTerms() {
    this.isLoading = true;
    this.authService.getTerms().subscribe({
      next: (res) => {
        this.terms = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar termos.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.authService.saveTerms(this.terms).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Termos atualizados com sucesso!");
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar termos.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/users"]);
  }
}
