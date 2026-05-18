import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService, PoTableColumn, PoTableAction } from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ClienteService } from "../../../services/cliente";

@Component({
  selector: "app-cliente-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./cliente-form.html"
})
export class ClienteFormComponent implements OnInit {

  cliente: any = { contatos: [] };
  isLoading: boolean = false;
  title: string = "Novo Cliente";

  readonly contatoColumns: Array<PoTableColumn> = [
    { property: "tipoContato.descricao", label: "Tipo" },
    { property: "nome", label: "Nome" },
    { property: "telefone", label: "Telefone" },
    { property: "email", label: "E-mail" }
  ];

  readonly contatoActions: Array<PoTableAction> = [
    { label: "Remover", action: this.removeContato.bind(this), icon: "po-icon-delete", type: "danger" }
  ];

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
    this.isLoading = true;
    const operation = this.cliente.id 
      ? this.clienteService.update(this.cliente.id, this.cliente)
      : this.clienteService.create(this.cliente);

    operation.subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Cadastro salvo com sucesso!");
        this.router.navigate(["/clientes"]);
      },
      error: (err) => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar cadastro.");
        console.error(err);
      }
    });
  }

  cancel() {
    this.router.navigate(["/clientes"]);
  }

  addContato() {
    if (!this.cliente.contatos) {
      this.cliente.contatos = [];
    }
    this.cliente.contatos.push({
      nome: "Novo Contato",
      telefone: "",
      email: "",
      tipoContatoId: 1 // Default
    });
  }

  removeContato(item: any) {
    const index = this.cliente.contatos.indexOf(item);
    if (index > -1) {
      this.cliente.contatos.splice(index, 1);
    }
  }
}
