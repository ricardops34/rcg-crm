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
  parameter: string;
  type: string;
  content: any;
  system: string;
  description: string;
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
            name="parameter"
            [(ngModel)]="parameter.parameter"
            p-label="Parametro"
            p-required
            p-clean>
          </po-input>

          <po-select
            class="po-md-4"
            name="type"
            [ngModel]="parameter.type"
            (ngModelChange)="onTypeChange($event)"
            p-label="Tipo"
            [p-options]="typeOptions"
            p-required>
          </po-select>
        </div>

        <po-divider p-label="Descricao"></po-divider>
        <div class="po-row">
          <po-input
            class="po-md-12"
            name="description"
            [(ngModel)]="parameter.description"
            p-label="Descrição explicativa do parâmetro"
            p-clean>
          </po-input>
        </div>

        <po-divider p-label="Conteudo e Escopo"></po-divider>
        <div class="po-row">
          <po-input
            *ngIf="parameter.type === 'CARACTER'"
            class="po-md-6"
            name="contentCharacter"
            [(ngModel)]="parameter.content"
            p-label="Conteudo"
            p-clean>
          </po-input>

          <po-number
            *ngIf="parameter.type === 'NUMERO'"
            class="po-md-6"
            name="contentNumber"
            [(ngModel)]="parameter.content"
            p-label="Conteudo">
          </po-number>

          <po-datepicker
            *ngIf="parameter.type === 'DATA'"
            class="po-md-6"
            name="contentDate"
            [(ngModel)]="parameter.content"
            p-label="Conteudo">
          </po-datepicker>

          <po-switch
            *ngIf="parameter.type === 'LOGICO'"
            class="po-md-6"
            name="contentLogical"
            [ngModel]="parameter.content === 'S'"
            (p-change)="parameter.content = $event ? 'S' : 'N'"
            p-label="Conteudo"
            p-label-off="Nao"
            p-label-on="Sim">
          </po-switch>

          <po-switch
            class="po-md-6"
            name="system"
            [ngModel]="parameter.system === 'S'"
            (p-change)="parameter.system = $event ? 'S' : 'N'"
            p-label="Parametro de Usuario?"
            p-label-off="Nao (Sistema)"
            p-label-on="Sim">
          </po-switch>
        </div>

        <div class="po-row" *ngIf="parameter.system === 'N'">
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
    parameter: "",
    type: "CARACTER",
    content: "",
    system: "N",
    description: ""
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
          parameter: res.parameter ?? "",
          type: res.type ?? "CARACTER",
          content: this.parseContent(res.type, res.content),
          system: res.system ?? "N",
          description: res.description ?? ""
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
    this.parameter.type = type;
    this.parameter.content = type === "LOGICO" ? "N" : "";
  }

  save() {
    if (!this.parameter.parameter?.trim()) {
      this.poNotification.warning("Informe o nome do parametro.");
      return;
    }

    this.isLoading = true;
    this.parameterService.save({
      ...this.parameter,
      parameter: this.parameter.parameter.trim(),
      content: this.normalizeContent(),
      description: this.parameter.description?.trim() || null
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
    if (this.parameter.type === "DATA") {
      return this.parameter.content ? String(this.parameter.content) : null;
    }

    if (this.parameter.type === "NUMERO") {
      return this.parameter.content === null || this.parameter.content === undefined || this.parameter.content === ""
        ? null
        : String(this.parameter.content);
    }

    if (this.parameter.type === "LOGICO") {
      return this.parameter.content === "S" ? "S" : "N";
    }

    return this.parameter.content ?? "";
  }
}
