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
      (p-cancel)="cancel()"
      [p-disable-submit]="isLoading">

      <form #unitForm="ngForm">
        <po-divider p-label="Configuração da Unidade"></po-divider>
        <div class="po-row">
          <po-input class="po-md-8" name="name" [(ngModel)]="unit.name" p-label="Nome da Unidade" p-required p-clean></po-input>
          <po-input class="po-md-4" name="connectionName" [(ngModel)]="unit.connectionName" p-label="Nome da Conexão (ERP)" p-help="Ex: erp_producao"></po-input>
        </div>

        <po-divider p-label="Identidade Visual da Unidade (Tenant)"></po-divider>
        <div class="po-row">
          <!-- Upload da Logo -->
          <div class="po-md-6 po-mb-2">
            <span class="po-field-title" style="display: block; margin-bottom: 8px;">Logomarca Personalizada</span>
            <po-button p-label="Selecionar Imagem da Logo" p-icon="an an-upload-simple" (p-click)="logoInput.click()"></po-button>
            <input #logoInput type="file" (change)="onLogoSelected($event)" style="display: none" accept="image/*">
            
            <div class="po-mt-2" style="display: flex; align-items: center; gap: 12px; min-height: 80px;">
              @if (unit.logo) {
                <img [src]="unit.logo" style="max-height: 80px; max-width: 200px; border: 1px solid #ddd; padding: 4px; border-radius: 4px; background: #f9f9f9;">
                <po-button p-label="Remover" [p-danger]="true" p-size="small" (p-click)="unit.logo = null"></po-button>
              } @else {
                <div style="font-size: 12px; color: #777; font-style: italic;">Nenhuma logo personalizada carregada. Usando a padrão do sistema.</div>
              }
            </div>
          </div>

          <!-- Upload do Favicon -->
          <div class="po-md-6 po-mb-2">
            <span class="po-field-title" style="display: block; margin-bottom: 8px;">Favicon Personalizado (.ico/.png)</span>
            <po-button p-label="Selecionar Imagem do Favicon" p-icon="an an-upload-simple" (p-click)="faviconInput.click()"></po-button>
            <input #faviconInput type="file" (change)="onFaviconSelected($event)" style="display: none" accept="image/x-icon, image/png, image/jpeg">
            
            <div class="po-mt-2" style="display: flex; align-items: center; gap: 12px; min-height: 80px;">
              @if (unit.favicon) {
                <img [src]="unit.favicon" style="height: 32px; width: 32px; border: 1px solid #ddd; padding: 2px; border-radius: 4px; background: #f9f9f9;">
                <po-button p-label="Remover" [p-danger]="true" p-size="small" (p-click)="unit.favicon = null"></po-button>
              } @else {
                <div style="font-size: 12px; color: #777; font-style: italic;">Nenhum favicon personalizado carregado. Usando o padrão do sistema.</div>
              }
            </div>
          </div>
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

  onLogoSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        this.unit.logo = reader.result as string;
      };
      reader.readAsDataURL(file);
    }
  }

  onFaviconSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        this.unit.favicon = reader.result as string;
      };
      reader.readAsDataURL(file);
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
