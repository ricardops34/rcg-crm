import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService, PoMultiselectOption } from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { GroupService } from "../../../services/group";
import { ProgramService } from "../../../services/program";

@Component({
  selector: "app-group-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  template: `
    <po-page-edit 
      [p-title]="title"
      [p-breadcrumb]="breadcrumb"
      (p-save)="save()"
      (p-cancel)="cancel()">
      
      <po-loading-overlay [p-screen-lock]="true" *ngIf="isLoading"></po-loading-overlay>

      <form #groupForm="ngForm">
        <po-divider p-label="Dados do Grupo"></po-divider>
        <div class="po-row">
          <po-input class="po-md-12" name="name" [(ngModel)]="group.name" p-label="Nome do Grupo" p-required p-clean></po-input>
        </div>

        <po-divider p-label="Acessos e Programas"></po-divider>
        <div class="po-row">
          <po-multiselect 
            class="po-md-12" 
            name="programs" 
            [(ngModel)]="group.programs" 
            p-label="Programas Permitidos" 
            [p-options]="programOptions" 
            p-placeholder="Selecione os programas que este grupo pode acessar">
          </po-multiselect>
        </div>
      </form>
      
    </po-page-edit>
  `
})
export class GroupFormComponent implements OnInit {

  group: any = { programs: [] };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Novo Grupo";

  programOptions: Array<PoMultiselectOption> = [];

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Grupos", link: "/admin/groups" },
      { label: "Cadastro" }
    ]
  };

  constructor(
    private groupService: GroupService,
    private programService: ProgramService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private poNotification: PoNotificationService
  ) {}

  ngOnInit() {
    this.loadPrograms();

    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Grupo";
      this.loadGroup(id);
    }
  }

  loadPrograms() {
    this.programService.findAll().subscribe(res => {
      this.programOptions = res.map((p: any) => ({ label: p.name, value: p.id }));
    });
  }

  loadGroup(id: number) {
    this.isLoading = true;
    this.groupService.findOne(id).subscribe({
      next: (res) => {
        this.group = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar grupo.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.groupService.save(this.group).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Grupo salvo com sucesso!");
        this.router.navigate(["/admin/groups"]);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar grupo.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/groups"]);
  }
}
