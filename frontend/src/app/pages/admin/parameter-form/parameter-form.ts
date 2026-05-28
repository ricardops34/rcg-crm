import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { FormsModule } from "@angular/forms";
import { PoBreadcrumb, PoModule, PoNotificationService, PoSelectOption } from "@po-ui/ng-components";
import { ParameterService } from "../../../services/parameter";
import { UnitService } from "../../../services/unit";

interface ParameterFormData {
  id?: number;
  systemUnitId: number | null;
  systemParameter: string;
  systemType: string;
  systemContent: any;
  systemSystem: string;
}

@Component({
  selector: "app-parameter-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()"
      [p-disable-submit]="isLoading">

      <form #parameterForm="ngForm">
        <po-divider p-label="Identificacao do Parametro"></po-divider>
        <div class="po-row">
          <po-select
            class="po-md-4"
            name="systemUnitId"
            [ngModel]="parameter.systemUnitId"
            (ngModelChange)="parameter.systemUnitId = $event"
            p-label="Unidade"
            [p-options]="unitOptions">
          </po-select>

          <po-input
            class="po-md-4"
            name="systemParameter"
            [(ngModel)]="parameter.systemParameter"
            p-label="Parametro"
            p-required
            p-clean>
          </po-input>

          <po-select
            class="po-md-4"
            name="systemType"
            [ngModel]="parameter.systemType"
            (ngModelChange)="onTypeChange($event)"
            p-label="Tipo"
            [p-options]="typeOptions"
            p-required>
          </po-select>
        </div>

        <po-divider p-label="Conteudo e Escopo"></po-divider>
        <div class="po-row">
          <po-input
            *ngIf="parameter.systemType === 'CARACTER'"
            class="po-md-6"
            name="systemContentCharacter"
            [(ngModel)]="parameter.systemContent"
            p-label="Conteudo"
            p-clean>
          </po-input>

          <po-number
            *ngIf="parameter.systemType === 'NUMERO'"
            class="po-md-6"
            name="systemContentNumber"
            [(ngModel)]="parameter.systemContent"
            p-label="Conteudo">
          </po-number>

          <po-datepicker
            *ngIf="parameter.systemType === 'DATA'"
            class="po-md-6"
            name="systemContentDate"
            [(ngModel)]="parameter.systemContent"
            p-label="Conteudo">
          </po-datepicker>

          <po-switch
            *ngIf="parameter.systemType === 'LOGICO'"
            class="po-md-6"
            name="systemContentLogical"
            [ngModel]="parameter.systemContent === 'S'"
            (p-change)="parameter.systemContent = $event ? 'S' : 'N'"
            p-label="Conteudo"
            p-label-off="Nao"
            p-label-on="Sim">
          </po-switch>

          <po-switch
            class="po-md-6"
            name="systemSystem"
            [ngModel]="parameter.systemSystem === 'S'"
            (p-change)="parameter.systemSystem = $event ? 'S' : 'N'"
            p-label="Parametro de Usuario?"
            p-label-off="Nao (Sistema)"
            p-label-on="Sim">
          </po-switch>
        </div>

        <div class="po-row" *ngIf="parameter.systemSystem === 'N'">
          <div class="po-md-12">
            <po-info
              p-label="Atenção"
              p-value="Parametros de sistema nao podem ser excluidos.">
            </po-info>
          </div>
        </div>
      </form>
    </po-page-edit>
  `
})
export class ParameterFormComponent implements OnInit {
  private readonly parameterService = inject(ParameterService);
  private readonly unitService = inject(UnitService);
  private readonly router = inject(Router);
  private readonly activatedRoute = inject(ActivatedRoute);
  private readonly poNotification = inject(PoNotificationService);

  parameter: ParameterFormData = {
    systemUnitId: null,
    systemParameter: "",
    systemType: "CARACTER",
    systemContent: "",
    systemSystem: "N"
  };

  unitOptions: Array<PoSelectOption> = [];
  isLoading = false;
  title = "Novo Parametro";

  readonly typeOptions: Array<PoSelectOption> = [
    { label: "Caracter", value: "CARACTER" },
    { label: "Numero", value: "NUMERO" },
    { label: "Data", value: "DATA" },
    { label: "Logico", value: "LOGICO" }
  ];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Administracao", link: "/admin/users" },
      { label: "Parametros", link: "/admin/parameters" },
      { label: "Manutencao" }
    ]
  };

  ngOnInit() {
    this.loadUnits();
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Parametro";
      this.loadParameter(+id);
    }
  }

  loadUnits() {
    this.unitService.findAll().subscribe({
      next: (res) => {
        this.unitOptions = [
          { label: "Todas as unidades", value: null as any },
          ...(res || []).map((unit: any) => ({
            label: unit.name,
            value: unit.id
          }))
        ];
      },
      error: () => {
        this.poNotification.error("Erro ao carregar unidades.");
      }
    });
  }

  loadParameter(id: number) {
    this.isLoading = true;
    this.parameterService.findOne(id).subscribe({
      next: (res: any) => {
        this.parameter = {
          id: res.id,
          systemUnitId: res.systemUnitId ?? null,
          systemParameter: res.systemParameter ?? "",
          systemType: res.systemType ?? "CARACTER",
          systemContent: this.parseContent(res.systemType, res.systemContent),
          systemSystem: res.systemSystem ?? "N"
        };
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar parametro.");
        this.router.navigate(["/admin/parameters"]);
      }
    });
  }

  onTypeChange(type: string) {
    this.parameter.systemType = type;
    this.parameter.systemContent = type === "LOGICO" ? "N" : "";
  }

  save() {
    if (!this.parameter.systemParameter?.trim()) {
      this.poNotification.warning("Informe o nome do parametro.");
      return;
    }

    this.isLoading = true;
    this.parameterService.save({
      ...this.parameter,
      systemParameter: this.parameter.systemParameter.trim(),
      systemContent: this.normalizeContent()
    }).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Parametro salvo com sucesso!");
        this.router.navigate(["/admin/parameters"]);
      },
      error: (error) => {
        this.isLoading = false;
        this.poNotification.error(error?.error?.message || "Erro ao salvar parametro.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/parameters"]);
  }

  private parseContent(type: string, content: any) {
    if (type === "NUMERO" && content !== null && content !== undefined && content !== "") {
      return Number(content);
    }

    if (type === "LOGICO") {
      return content === "S" ? "S" : "N";
    }

    return content ?? "";
  }

  private normalizeContent() {
    if (this.parameter.systemType === "DATA") {
      return this.parameter.systemContent ? String(this.parameter.systemContent) : null;
    }

    if (this.parameter.systemType === "NUMERO") {
      return this.parameter.systemContent === null || this.parameter.systemContent === undefined || this.parameter.systemContent === ""
        ? null
        : String(this.parameter.systemContent);
    }

    if (this.parameter.systemType === "LOGICO") {
      return this.parameter.systemContent === "S" ? "S" : "N";
    }

    return this.parameter.systemContent ?? "";
  }
}
