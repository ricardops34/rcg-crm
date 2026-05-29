import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import {
  PoBreadcrumb,
  PoModule,
  PoNotificationService,
  PoSelectOption
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { finalize } from "rxjs";
import { VendedorService } from "../../../services/vendedor";
import { UnitService } from "../../../services/unit";

@Component({
  selector: "app-vendedor-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./vendedor-form.html",
  styleUrl: ./vendedor-form.css"
})
export class VendedorFormComponent implements OnInit {
  private vendedorService = inject(VendedorService);
  private unitService = inject(UnitService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  vendedor: any = this.novoVendedor();

  isLoading = false;
  title = "Novo Vendedor";

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

    const id = Number(this.activatedRoute.snapshot.params["id"]);
    if (id) {
      this.title = "Editar Vendedor";
      this.loadVendedor(id);
    }
  }

  loadDependencies() {
    this.unitService.findAll().subscribe({
      next: (res) => {
        this.units = Array.isArray(res)
          ? res.map((unit: any) => ({ label: unit.name, value: unit.id }))
          : [];
      },
      error: () => {
        this.units = [];
        this.poNotification.error("Erro ao carregar unidades de negocio.");
      }
    });

    this.vendedorService.findAll(1, 100, { status: "A" }).subscribe({
      next: (res) => {
        this.supervisors = Array.isArray(res?.items)
          ? res.items
              .filter((vendedor: any) => vendedor.supervisor === "S")
              .map((vendedor: any) => ({ label: vendedor.nome, value: vendedor.id }))
          : [];
      },
      error: () => {
        this.supervisors = [];
        this.poNotification.error("Erro ao carregar supervisores.");
      }
    });
  }

  loadVendedor(id: number) {
    this.isLoading = true;

    this.vendedorService.findOne(id).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        if (!res) {
          this.poNotification.error("Vendedor nao encontrado para edicao.");
          this.router.navigate(["/vendedores"]);
          return;
        }

        this.vendedor = {
          ...this.novoVendedor(),
          ...res
        };
      },
      error: () => {
        this.poNotification.error("Erro ao carregar dados do vendedor.");
        this.router.navigate(["/vendedores"]);
      }
    });
  }

  save() {
    this.isLoading = true;
    const payload = { ...this.vendedor };

    this.vendedorService.save(payload).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: () => {
        this.poNotification.success("Vendedor salvo com sucesso!");
        this.router.navigate(["/vendedores"]);
      },
      error: () => {
        this.poNotification.error("Erro ao salvar vendedor.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/vendedores"]);
  }

  private novoVendedor() {
    return {
      status: "A",
      vendedor: "S",
      supervisor: "N",
      desligado: "N",
      dashboard: "N"
    };
  }
}
