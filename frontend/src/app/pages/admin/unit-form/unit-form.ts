import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService 
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { UnitService } from "../../../services/unit";

@Component({
  selector: "app-unit-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit 
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()">
      
      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <form #unitForm="ngForm">
        <po-divider p-label="Configuração da Unidade"></po-divider>
        <div class="po-row">
          <po-input class="po-md-8" name="name" [(ngModel)]="unit.name" p-label="Nome da Unidade" p-required p-clean></po-input>
          <po-input class="po-md-4" name="connectionName" [(ngModel)]="unit.connectionName" p-label="Nome da Conexão (ERP)" p-help="Ex: erp_producao"></po-input>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class UnitFormComponent implements OnInit {
  private unitService = inject(UnitService);
  private router = inject(Router);
  private activatedRoute = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  unit: any = {};
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Nova Unidade";

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Unidades", link: "/admin/units" },
      { label: "Manutenção" }
    ]
  };

  ngOnInit() {
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Unidade";
      this.loadUnit(id);
    }
  }

  loadUnit(id: number) {
    this.isLoading = true;
    this.unitService.findOne(id).subscribe({
      next: (res) => {
        this.unit = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar unidade.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.unitService.save(this.unit).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Unidade salva com sucesso!");
        this.router.navigate(["/admin/units"]);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar unidade.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/units"]);
  }
}
