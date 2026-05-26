import { CommonModule } from "@angular/common";
import { Component, inject } from "@angular/core";
import { Router } from "@angular/router";
import { PoModule } from "@po-ui/ng-components";

interface ConfiguratorShortcut {
  title: string;
  description: string;
  route?: string;
  icon: string;
  tone: "primary" | "success" | "warning";
}

interface ConfiguratorPillar {
  title: string;
  description: string;
  points: string[];
}

@Component({
  selector: "app-configurator-workbench",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./configurator-workbench.html",
  styleUrl: "./configurator-workbench.css"
})
export class ConfiguratorWorkbenchComponent {
  private router = inject(Router);

  readonly shortcuts: Array<ConfiguratorShortcut> = [
    {
      title: "Plano do construtor",
      description: "Centraliza a trilha do builder visual, runtime e serializacao XML.",
      route: "/admin/configurador",
      icon: "an an-sidebar-simple",
      tone: "primary"
    },
    {
      title: "Rotinas do sistema",
      description: "Mantem programas, controllers e rotas usados pelo menu dinamico.",
      route: "/admin/programs",
      icon: "an an-terminal-window",
      tone: "success"
    },
    {
      title: "Editor de menus",
      description: "Revisa modulos e navegacao que vao interpretar as telas dinamicas.",
      route: "/admin/menu-editor",
      icon: "an an-list",
      tone: "warning"
    }
  ];

  readonly pillars: Array<ConfiguratorPillar> = [
    {
      title: "Builder visual",
      description: "Espaco para montar formularios, consultas e listagens por composicao.",
      points: [
        "Paleta de componentes e drop zones por secao.",
        "Painel de propriedades com validacao, mascara e layout.",
        "Preview imediato com componentes PO UI."
      ]
    },
    {
      title: "Schema e XML",
      description: "A definicao da tela precisa permanecer declarativa, versionavel e segura.",
      points: [
        "Modelo canonico interno para edicao e validacao.",
        "Serializacao para XML apenas no save e publish.",
        "Restricao a actions e data sources via registry."
      ]
    },
    {
      title: "Runtime dinamico",
      description: "O menu deve abrir telas por controller, sem codificacao manual por pagina.",
      points: [
        "Route unica para carregar screenId e permissao.",
        "Renderer por tipo: edit, table, detail e custom.",
        "Fallback consistente para schema invalido ou incompleto."
      ]
    }
  ];

  readonly milestones: string[] = [
    "MVP com cadastro, consulta e listagem em layout responsivo.",
    "Persistencia em XML com parser e validacao estruturada.",
    "Renderizacao por metadata usando PO UI dinamico.",
    "Historico de alteracoes e manutencao sem tocar no codigo."
  ];

  navigateTo(route?: string) {
    if (!route) {
      return;
    }

    this.router.navigate([route]);
  }
}
