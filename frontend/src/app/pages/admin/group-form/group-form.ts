import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService, 
  PoSelectOption, 
  PoTableColumn,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { GroupService } from "../../../services/group";
import { ProgramService } from "../../../services/program";

@Component({
  selector: "app-group-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./group-form.html"
})
export class GroupFormComponent implements OnInit {
  private groupService = inject(GroupService);
  private programService = inject(ProgramService);
  private router = inject(Router);
  private activatedRoute = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  group: any = { programs: [], role: "" };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Novo Perfil";

  allPrograms: Array<any> = [];
  
  readonly roleOptions: Array<PoSelectOption> = [
    { label: "Administrador (Full)", value: "ADMIN" },
    { label: "Gerência (BI & Equipes)", value: "GERENTE" },
    { label: "Supervisor (Sua Equipe)", value: "SUPERVISOR" },
    { label: "Vendedor (Seus Dados)", value: "VENDEDOR" },
    { label: "Cliente (Autoatendimento)", value: "CLIENTE" }
  ];

  readonly columns: Array<PoTableColumn> = [
    { property: "name", label: "Rotina / Programa" },
    { property: "controller", label: "Controller" },
    { property: "view", label: "Ver", type: "boolean", width: "80px" },
    { property: "insert", label: "Incluir", type: "boolean", width: "80px" },
    { property: "update", label: "Editar", type: "boolean", width: "80px" },
    { property: "delete", label: "Excluir", type: "boolean", width: "80px" }
  ];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Perfis de Acesso", link: "/admin/groups" },
      { label: "Manutenção" }
    ]
  };

  ngOnInit() {
    this.loadAllPrograms();

    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Perfil";
      this.loadGroup(id);
    }
  }

  loadAllPrograms() {
    this.programService.findAll().subscribe(res => {
      this.allPrograms = res.map((p: any) => ({
        ...p,
        view: false,
        insert: false,
        update: false,
        delete: false
      }));
    });
  }

  loadGroup(id: number) {
    this.isLoading = true;
    this.groupService.findOne(id).subscribe({
      next: (res) => {
        if (!res) {
          this.isLoading = false;
          this.poNotification.error("Perfil não encontrado.");
          this.router.navigate(["/admin/groups"]);
          return;
        }
        this.group = res;
        this.syncProgramsWithPermissions();
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar perfil.");
        this.router.navigate(["/admin/groups"]);
      }
    });
  }

  syncProgramsWithPermissions() {
    if (!this.group.programs) return;

    this.allPrograms.forEach(p => {
      const permission = this.group.programs.find((gp: any) => gp.id === p.id);
      if (permission) {
        p.view = permission.actions.view;
        p.insert = permission.actions.insert;
        p.update = permission.actions.update;
        p.delete = permission.actions.delete;
      } else {
        p.view = p.insert = p.update = p.delete = false;
      }
    });
    // Forçar atualização da referência para a tabela detectar mudanças
    this.allPrograms = [...this.allPrograms];
  }

  save() {
    if (!this.group.role) {
      this.poNotification.error("O campo 'Tipo de Acesso' é obrigatório.");
      return;
    }

    this.isLoading = true;

    // Converter matriz de checkboxes de volta para o formato esperado pelo backend
    const selectedPrograms = this.allPrograms
      .filter(p => p.view || p.insert || p.update || p.delete)
      .map(p => ({
        id: p.id,
        actions: {
          view: p.view,
          insert: p.insert,
          update: p.update,
          delete: p.delete
        }
      }));

    const payload = {
      ...this.group,
      programs: selectedPrograms
    };

    this.groupService.save(payload).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Perfil salvo com sucesso!");
        this.router.navigate(["/admin/groups"]);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar perfil.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/groups"]);
  }
}
