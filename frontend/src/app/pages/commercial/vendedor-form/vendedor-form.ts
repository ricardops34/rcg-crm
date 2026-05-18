import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService } from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { VendedorService } from "../../../services/vendedor";

@Component({
  selector: "app-vendedor-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./vendedor-form.html"
})
export class VendedorFormComponent implements OnInit {

  vendedor: any = {};
  isLoading: boolean = false;
  title: string = "Novo Vendedor";

  readonly breadcrumb: any = {
    items: [
      { label: "Home", link: "/" },
      { label: "Vendedores", link: "/vendedores" },
      { label: "Cadastro" }
    ]
  };

  constructor(

    private vendedorService: VendedorService,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private poNotification: PoNotificationService
  ) { }

  ngOnInit(): void {
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Vendedor";
      this.loadVendedor(id);
    }
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
    this.vendedorService.save(this.vendedor).subscribe({
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
