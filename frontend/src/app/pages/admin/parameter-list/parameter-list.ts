import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoDialogService,
  PoModule,
  PoNotificationService,
  PoPageAction,
  PoPageFilter,
  PoTableAction,
  PoTableColumn
} from "@po-ui/ng-components";
import { ParameterService } from "../../../services/parameter";

@Component({
  selector: "app-parameter-list",
  standalone: true,
  imports: [CommonModule, PoModule],
  template: `
    <po-page-list
      p-title="Parametros do Sistema"
      p-subtitle="Gestao de parametros globais e por unidade"
      [p-breadcrumb]="breadcrumb"
      [p-actions]="actions"
      [p-filter]="filter">

      <po-table
        [p-columns]="columns"
        [p-items]="parameters"
        [p-actions]="tableActions"
        [p-loading]="isLoading"
        [p-loading-show-more]="loadingShowMore"
        [p-show-more-disabled]="!hasNext"
        (p-show-more)="showMore()"
        p-container="shadow"
        [p-striped]="true"
        [p-sort]="true">
      </po-table>
    </po-page-list>
  `
})
export class ParameterListComponent implements OnInit {
  private readonly parameterService = inject(ParameterService);
  private readonly router = inject(Router);
  private readonly poNotification = inject(PoNotificationService);
  private readonly poDialog = inject(PoDialogService);
  private paginaAtual = 1;
  private filtroAtual = "";

  get itensPorPagina(): number {
    return this.parameterService.queryLimit();
  }
  private allParameters: Array<any> = [];

  parameters: Array<any> = [];
  isLoading = false;
  loadingShowMore = false;
  hasNext = false;

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Administracao", link: "/admin/users" },
      { label: "Parametros" }
    ]
  };

  readonly filter: PoPageFilter = {
    action: this.loadParameters.bind(this),
    placeholder: "Pesquisar por parametro, conteudo ou unidade"
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Novo Parametro", action: () => this.router.navigate(["/admin/parameters/new"]), icon: "an an-plus" }
  ];

  readonly tableActions: Array<PoTableAction> = [
    { label: "Editar", action: (row: any) => this.router.navigate([`/admin/parameters/edit/${row.id}`]), icon: "an an-pencil-simple" },
    {
      label: "Tornar por Unidade",
      action: (row: any) => this.confirmSplitByUnit(row),
      icon: "an an-share-network",
      visible: (row: any) => row.systemUnitId === null
    },
    {
      label: "Excluir",
      action: (row: any) => this.confirmDelete(row),
      icon: "an an-trash",
      type: "danger",
      disabled: (row: any) => row.system === "N"
    }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "parameter", label: "Parametro" },
    { property: "description", label: "Descricao" },
    { property: "unitName", label: "Unidade", width: "180px" },
    {
      property: "type",
      label: "Tipo",
      width: "120px",
      type: "label",
      labels: [
        { value: "DATA", color: "color-01", label: "Data" },
        { value: "NUMERO", color: "color-08", label: "Numero" },
        { value: "LOGICO", color: "color-11", label: "Logico" },
        { value: "CARACTER", color: "color-07", label: "Caracter" }
      ]
    },
    { property: "displayContent", label: "Conteudo" },
    {
      property: "system",
      label: "Parametro de Usuario?",
      width: "180px",
      type: "label",
      labels: [
        { value: "S", color: "color-11", label: "Sim" },
        { value: "N", color: "color-07", label: "Nao (Sistema)" }
      ]
    }
  ];

  ngOnInit() {
    this.loadParameters();
  }

  loadParameters(filter: string = "") {
    this.filtroAtual = filter;
    this.paginaAtual = 1;
    this.isLoading = true;

    this.parameterService.findAll().subscribe({
      next: (res: any[]) => {
        this.allParameters = this.aplicarFiltroLocal((res || []).map((item) => ({
          ...item,
          unitName: item.systemUnit?.name ?? "Todas as unidades",
          displayContent: this.formatContent(item)
        })), filter);
        this.atualizarPaginaVisivel();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar parametros.");
      }
    });
  }

  showMore() {
    if (!this.hasNext || this.loadingShowMore) {
      return;
    }

    this.loadingShowMore = true;
    this.paginaAtual += 1;
    this.atualizarPaginaVisivel();
    this.loadingShowMore = false;
  }

  confirmDelete(parameter: any) {
    this.poDialog.confirm({
      title: "Excluir Parametro",
      message: `Deseja realmente excluir o parametro <strong>${parameter.parameter}</strong>?`,
      confirm: () => this.deleteParameter(parameter)
    });
  }

  confirmSplitByUnit(row: any) {
    this.poDialog.confirm({
      title: "Tornar por Unidade",
      message: `Deseja realmente transformar o parâmetro <strong>${row.parameter}</strong> em parâmetros individuais por unidade? Isso criará cópias dele para todas as unidades cadastradas e removerá este registro global.`,
      confirm: () => this.splitParameterByUnit(row)
    });
  }

  private splitParameterByUnit(parameter: any) {
    this.isLoading = true;
    this.parameterService.splitByUnit(parameter.id).subscribe({
      next: () => {
        this.poNotification.success("Parâmetro dividido por unidade com sucesso!");
        this.loadParameters(this.filtroAtual);
      },
      error: (error) => {
        this.isLoading = false;
        this.poNotification.error(error?.error?.message || "Erro ao dividir parâmetro por unidade.");
      }
    });
  }

  private deleteParameter(parameter: any) {
    this.isLoading = true;
    this.parameterService.delete(parameter.id).subscribe({
      next: () => {
        this.poNotification.success("Parametro excluido com sucesso!");
        this.loadParameters(this.filtroAtual);
      },
      error: (error) => {
        this.isLoading = false;
        this.poNotification.error(error?.error?.message || "Erro ao excluir parametro.");
      }
    });
  }

  private atualizarPaginaVisivel() {
    const limite = this.paginaAtual * this.itensPorPagina;
    this.parameters = this.allParameters.slice(0, limite);
    this.hasNext = this.allParameters.length > limite;
  }

  private aplicarFiltroLocal(parameters: Array<any>, filter: string): Array<any> {
    if (!filter) {
      return parameters;
    }

    const filtroNormalizado = filter.toLowerCase();
    return parameters.filter((item) =>
      item.parameter?.toLowerCase().includes(filtroNormalizado) ||
      item.unitName?.toLowerCase().includes(filtroNormalizado) ||
      item.displayContent?.toLowerCase().includes(filtroNormalizado) ||
      item.description?.toLowerCase().includes(filtroNormalizado)
    );
  }

  private formatContent(item: any): string {
    if (item.type === "LOGICO") {
      return item.content === "S" ? "Sim" : "Nao";
    }

    return item.content ?? "";
  }
}
