import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService, PoSelectOption, PoMultiselectOption } from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { UserService } from "../../../services/user";
import { UnitService } from "../../../services/unit";
import { GroupService } from "../../../services/group";

@Component({
  selector: "app-user-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./user-form.html"
})
export class UserFormComponent implements OnInit {

  user: any = { active: "Y", groups: [] };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Novo Usuário";

  unitOptions: Array<PoSelectOption> = [];
  groupOptions: Array<PoMultiselectOption> = [];

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Usuários", link: "/admin/users" },
      { label: "Manutenção" }
    ]
  };

  readonly activeOptions = [
    { label: "Sim", value: "Y" },
    { label: "Não", value: "N" }
  ];

  constructor(
    private userService: UserService,
    private unitService: UnitService,
    private groupService: GroupService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private poNotification: PoNotificationService
  ) {}

  ngOnInit() {
    this.loadInitialData();

    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.isEdit = true;
      this.title = "Editar Usuário";
      this.loadUser(id);
    }
  }

  loadInitialData() {
    this.unitService.findAll().subscribe(res => {
      this.unitOptions = res.map((u: any) => ({ label: u.nome, value: u.id }));
    });
    this.groupService.findAll().subscribe(res => {
      this.groupOptions = res.map((g: any) => ({ label: g.name, value: g.id }));
    });
  }

  loadUser(id: number) {
    this.isLoading = true;
    this.userService.findOne(id).subscribe({
      next: (res) => {
        this.user = res;
        this.user.password = ""; 
        if (this.user.userGroups) {
          this.user.groups = this.user.userGroups.map((ug: any) => ug.systemGroupId);
        }
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar usuário.");
      }
    });
  }

  save() {
    this.isLoading = true;
    this.userService.save(this.user).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Usuário salvo com sucesso!");
        this.router.navigate(["/admin/users"]);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar usuário.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/admin/users"]);
  }
}
