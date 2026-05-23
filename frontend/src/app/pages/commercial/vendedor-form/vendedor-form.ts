import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService, 
  PoSelectOption, 
  PoBreadcrumb 
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { VendedorService } from "../../../services/vendedor";
import { UnitService } from "../../../services/unit";

@Component({
  selector: "app-vendedor-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./vendedor-form.html"
})
export class VendedorFormComponent implements OnInit {
  private vendedorService = inject(VendedorService);
  private unitService = inject(UnitService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  vendedor: any = {
    status: "A",
    vendedor: "S",
    supervisor: "N",
    desligado: "N"
  };
  
  isLoading: boolean = false;
  title: string = "Novo Vendedor";
  
  units: Array<PoSelectOption> = [];
  supervisors: Array<PoSelectOption> = [];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendedores", link: "/vendedores" },
      { label: "Cadastro" }
    ]
  };

  readonly statusOptions: Array<PoSelectOption> = [
    { label: "Ativo", value: "A" },
    { label: "Bloqueado", value: "B" }
  ];

  ngOnInit(): void {
    this.loadDependencies();
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Vendedor";
      this.loadVendedor(id);
    }
  }

  loadDependencies() {
    this.unitService.findAll().subscribe(res => {
      this.units = res.map((u: any) => ({ label: u.name, value: u.id }));
    });

    this.vendedorService.findAll(1, 100, { status: "A" }).subscribe(res => {
      this.supervisors = res.items
        .filter((v: any) => v.supervisor === "S")
        .map((v: any) => ({ label: v.nome, value: v.id }));
    });
  }

  loadVendedor(id: number) {
    this.isLoading = true;
    this.vendedorService.findOne(id).subscribe({
      next: (res) => {
        this.vendedor = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar dados do vendedor.");
      }
    });
  }

  save() {
    this.isLoading = true;
    // Garantir conversão de booleanos para S/N se necessário (PO-UI Switch usa boolean)
    const payload = { ...this.vendedor };
    
    this.vendedorService.save(payload).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Vendedor salvo com sucesso!");
        this.router.navigate(["/vendedores"]);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar vendedor.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/vendedores"]);
  }
}
