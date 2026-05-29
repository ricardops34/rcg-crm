import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService, 
  PoSelectOption, 
  PoMultiselectOption,
  PoBreadcrumb
} from "@po-ui/ng-components";
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
  private userService = inject(UserService);
  private unitService = inject(UnitService);
  private groupService = inject(GroupService);
  private router = inject(Router);
  private activatedRoute = inject(ActivatedRoute);
  private poNotification = inject(PoNotificationService);

  user: any = { active: "Y", groups: [], units: [] };
  isEdit: boolean = false;
  isLoading: boolean = false;
  title: string = "Novo Usuário";

  unitOptions: Array<any> = [];
  groupOptions: Array<PoMultiselectOption> = [];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Usuários", link: "/admin/users" },
      { label: "Manutenção" }
    ]
  };

  readonly activeOptions = [
    { label: "Ativo", value: "Y" },
    { label: "Inativo", value: "N" }
  ];

  readonly forcePasswordOptions = [
    { label: "Sim — exigir nova senha no próximo login", value: "Y" },
    { label: "Não", value: "N" }
  ];

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
      this.unitOptions = res.map((u: any) => ({ label: u.name, value: u.id }));
    });
    this.groupService.findAll().subscribe(res => {
      this.groupOptions = res.map((g: any) => ({ label: g.name, value: g.id }));
    });
  }

  loadUser(id: number) {
    this.isLoading = true;
    this.userService.findOne(id).subscribe({
      next: (res) => {
        if (!res) {
          this.isLoading = false;
          this.poNotification.error("Usuário não encontrado.");
          this.router.navigate(["/admin/users"]);
          return;
        }
        this.user = { ...res, password: '' };
        this.user.groups = res.userGroups?.map((ug: any) => ug.systemGroupId) ?? [];
        this.user.units = res.userUnits?.map((uu: any) => uu.systemUnitId) ?? [];
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar usuário.");
        this.router.navigate(["/admin/users"]);
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
