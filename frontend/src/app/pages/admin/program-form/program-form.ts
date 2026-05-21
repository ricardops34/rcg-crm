import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService,
  PoSelectOption,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ProgramService } from "../../../services/program";

@Component({
  selector: "app-program-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit 
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()">
      
      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <form #programForm="ngForm">
        <po-divider p-label="Definição da Rotina"></po-divider>
        <div class="po-row">
          <po-input class="po-md-6" name="name" [(ngModel)]="program.name" p-label="Nome da Tela / Rotina" p-required p-clean></po-input>
          <po-input class="po-md-6" name="controller" [(ngModel)]="program.controller" p-label="Controller (Classe PHP ou NestJS)" p-required p-clean></po-input>
        </div>
        <div class="po-row">
          <po-select 
            class="po-md-6" 
            name="module" 
            [ngModel]="program.module" 
            (p-change)="program.module = $event"
            p-label="Módulo (Agrupador do Menu)" 
            [p-options]="moduleOptions"
            p-required="true">
          </po-select>
          <po-input class="po-md-6" name="icon" [(ngModel)]="program.icon" p-label="Ícone (Opcional)" p-icon="po-icon-star"></po-input>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class ProgramFormComponent implements OnInit {
  private programService = inject(ProgramService);
  private router = inject(Router);
  private activatedRoute = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  program: any = { module: "Sistema" };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Nova Rotina";

  readonly moduleOptions: Array<PoSelectOption> = [
    { label: "Vendas e CRM", value: "Vendas e CRM" },
    { label: "Financeiro", value: "Financeiro" },
    { label: "Cadastros Base", value: "Cadastros" },
    { label: "Administração", value: "Admin" },
    { label: "Relatórios", value: "Relatórios" },
    { label: "Sistema", value: "Sistema" }
  ];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Rotinas", link: "/admin/programs" },
      { label: "Manutenção" }
    ]
  };

  ngOnInit() {
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Rotina";
      this.loadProgram(id);
    }
  }

  loadProgram(id: number) {
    this.isLoading = true;
    this.programService.findOne(id).subscribe({
      next: (res) => {
        this.program = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar rotina.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.programService.save(this.program).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Rotina salva com sucesso!");
        this.router.navigate(["/admin/programs"]);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar rotina.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/programs"]);
  }
}
