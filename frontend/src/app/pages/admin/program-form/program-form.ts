import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import {
  PoModule,
  PoNotificationService,
  PoSelectOption,
  PoBreadcrumb,
  PoComboOption,
  PoComboFilterMode
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ProgramService } from "../../../services/program";
import { ModuleService } from "../../../services/module";

interface ProgramForm {
  id?: number;
  name: string;
  controller: string;
  systemModuleId: number | null;
  icon: string;
  order: number;
}

@Component({
  selector: "app-program-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()"
      [p-disable-submit]="isLoading">

      <form #programForm="ngForm">
        <po-divider p-label="Definição da Rotina"></po-divider>
        <div class="po-row">
          <po-input
            class="po-md-6"
            name="name"
            [(ngModel)]="program.name"
            p-label="Nome da Tela / Rotina"
            p-required
            p-clean>
          </po-input>
          <po-input
            class="po-md-6"
            name="controller"
            [(ngModel)]="program.controller"
            p-label="Controller (Identificador único da rotina)"
            p-required
            p-clean>
          </po-input>
        </div>

        <po-divider p-label="Posicionamento no Menu"></po-divider>
        <div class="po-row">
          <po-select
            class="po-md-5"
            name="systemModuleId"
            [ngModel]="program.systemModuleId"
            (ngModelChange)="program.systemModuleId = $event"
            p-label="Módulo (Grupo no Menu)"
            [p-options]="moduleOptions"
            p-required="true">
          </po-select>
          <po-number
            class="po-md-3"
            name="order"
            [(ngModel)]="program.order"
            p-label="Ordem no Menu"
            [p-min]="1">
          </po-number>
          <po-combo
            class="po-md-4"
            name="icon"
            [ngModel]="program.icon"
            (p-change)="program.icon = $event"
            p-label="Ícone (PO-UI / Animalia)"
            [p-options]="iconOptions"
            [p-filter-mode]="filterModeContains"
            p-clean>
            <ng-template p-combo-option-template let-option>
              <div style="display: flex; align-items: center; gap: 12px; padding: 6px 0;">
                <span [class]="option.value" style="font-size: 20px; width: 24px; text-align: center; color: var(--color-primary, #0056b3);"></span>
                <span style="font-size: 14px;">{{ option.label }}</span>
              </div>
            </ng-template>
          </po-combo>
        </div>

        <div class="po-row" *ngIf="!program.systemModuleId">
          <div class="po-md-12">
            <po-info
              p-label="Atenção"
              p-value="Rotinas sem módulo atribuído NÃO aparecem no menu do sistema.">
            </po-info>
          </div>
        </div>
      </form>

    </po-page-edit>
  `
})
export class ProgramFormComponent implements OnInit {
  readonly filterModeContains = PoComboFilterMode.contains;

  private programService = inject(ProgramService);
  private moduleService = inject(ModuleService);
  private router = inject(Router);
  private activatedRoute = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  program: ProgramForm = {
    name: '',
    controller: '',
    systemModuleId: null,
    icon: '',
    order: 1
  };

  isEdit = false;
  isLoading = false;
  title = "Nova Rotina";
  moduleOptions: Array<PoSelectOption> = [];

  readonly iconOptions: Array<PoComboOption> = [
    { label: 'Casa / Home (an-house)', value: 'an an-house' },
    { label: 'Usuário / User (an-user)', value: 'an an-user' },
    { label: 'Adicionar Usuário (an-user-plus)', value: 'an an-user-plus' },
    { label: 'Engrenagem / Configurações (an-gear-six)', value: 'an an-gear-six' },
    { label: 'Gráfico / Estatísticas (an-chart-line)', value: 'an an-chart-line' },
    { label: 'Área de Gráfico (an-chart-area)', value: 'an an-chart-area' },
    { label: 'Lista / MCV (an-list)', value: 'an an-list' },
    { label: 'Alvo / Objetivos (an-target)', value: 'an an-target' },
    { label: 'Cadeado / Permissões (an-lock)', value: 'an an-lock' },
    { label: 'Menu Lateral (an-sidebar-simple)', value: 'an an-sidebar-simple' },
    { label: 'Sinal de Mais / Novo (an-plus)', value: 'an an-plus' },
    { label: 'Lixeira / Excluir (an-trash)', value: 'an an-trash' },
    { label: 'Prédio / Unidades (an-buildings)', value: 'an an-buildings' },
    { label: 'Camadas / Módulos (an-layers)', value: 'an an-layers' },
    { label: 'Grid / Aplicativos (an-grid-four)', value: 'an an-grid-four' },
    { label: 'Calendário (an-calendar)', value: 'an an-calendar' },
    { label: 'Telefone (an-phone)', value: 'an an-phone' },
    { label: 'Envelope / E-mail (an-envelope)', value: 'an an-envelope' },
    { label: 'Mesa / Dashboard Vendedor (an-desktop)', value: 'an an-desktop' },
    { label: 'Estrela / Destaque (an-star)', value: 'an an-star' }
  ];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Administração", link: "/admin/users" },
      { label: "Rotinas", link: "/admin/programs" },
      { label: "Manutenção" }
    ]
  };

  ngOnInit() {
    this.loadModules();
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Rotina";
      this.loadProgram(+id);
    }
  }

  loadModules() {
    this.moduleService.findAll().subscribe({
      next: (modules: any[]) => {
        this.moduleOptions = [
          { label: '— Sem módulo (não aparece no menu) —', value: null as any },
          ...modules.map(m => ({ label: `${m.name}`, value: m.id }))
        ];
      },
      error: () => this.poNotification.error("Erro ao carregar módulos.")
    });
  }

  loadProgram(id: number) {
    this.isLoading = true;
    this.programService.findOne(id).subscribe({
      next: (res: any) => {
        this.program = {
          id: res.id,
          name: res.name,
          controller: res.controller,
          systemModuleId: res.systemModuleId ?? null,
          icon: res.icon ?? '',
          order: res.order ?? 1
        };
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar rotina.");
        this.router.navigate(["/admin/programs"]);
      }
    });
  }

  save() {
    if (!this.program.name || !this.program.controller) {
      this.poNotification.warning("Preencha o nome e o controller da rotina.");
      return;
    }
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
