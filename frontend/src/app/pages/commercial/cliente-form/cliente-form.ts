import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService } from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ClienteService } from "../../../services/cliente";

@Component({
  selector: "app-cliente-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./cliente-form.html"
})
export class ClienteFormComponent implements OnInit {

  cliente: any = {};
  isLoading: boolean = false;
  title: string = "Novo Cliente";

  constructor(
    private clienteService: ClienteService,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private poNotification: PoNotificationService
  ) { }

  ngOnInit(): void {
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Cliente";
      this.loadCliente(id);
    }
  }

  loadCliente(id: number) {
    this.isLoading = true;
    this.clienteService.findOne(id).subscribe({
      next: (res) => {
        this.cliente = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar dados do cliente.");
      }
    });
  }

  save() {
    this.poNotification.success("Cadastro salvo com sucesso!");
    this.router.navigate(["/clientes"]);
  }

  cancel() {
    this.router.navigate(["/clientes"]);
  }
}
