import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { 
  PoModule, 
  PoNotificationService, 
  PoTableColumn, 
  PoTableAction, 
  PoSelectOption,
  PoBreadcrumb
} from "@po-ui/ng-components";
import { FormsModule } from "@angular/forms";
import { ClienteService } from "../../../services/cliente";
import { VendedorService } from "../../../services/vendedor";
import { LocationService } from "../../../services/location";

@Component({
  selector: "app-cliente-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./cliente-form.html",
  styleUrl: './cliente-form.css'
})
export class ClienteFormComponent implements OnInit {
  private clienteService = inject(ClienteService);
  private vendedorService = inject(VendedorService);
  private locationService = inject(LocationService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  cliente: any = { 
    contatos: [], 
    status: "A", 
    tipo: "J",
    contribuinte: "S",
    destacaIe: "N"
  };
  
  isLoading: boolean = false;
  title: string = "Novo Cliente";

  vendedores: Array<PoSelectOption> = [];
  estados: Array<PoSelectOption> = [];
  municipios: Array<PoSelectOption> = [];
  condicoes: Array<PoSelectOption> = [];
  tabelas: Array<PoSelectOption> = [];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Clientes", link: "/clientes" },
      { label: "Manutenção" }
    ]
  };

  readonly contatoColumns: Array<PoTableColumn> = [
    { property: "tipoContatoId", label: "Tipo", type: "label", labels: [
      { value: 1, label: "Comercial" },
      { value: 2, label: "Financeiro" },
      { value: 3, label: "Outros" }
    ]},
    { property: "nome", label: "Nome" },
    { property: "telefone", label: "Telefone" },
    { property: "email", label: "E-mail" }
  ];

  readonly contatoActions: Array<PoTableAction> = [
    { label: "Remover", action: this.removeContato.bind(this), icon: "an an-trash", type: "danger" }
  ];

  ngOnInit(): void {
    this.loadInitialData();

    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Cliente";
      this.loadCliente(id);
    }
  }

  loadInitialData() {
    this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe(res => {
      this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
    });
    this.locationService.getEstados().subscribe(res => {
      this.estados = res.map((e: any) => ({ label: e.sigla, value: e.id }));
    });
    // Adicionar carregamento de Condições e Tabelas se houver services para isso
  }

  onEstadoChange(estadoId: any) {
    if (estadoId) {
      this.locationService.getMunicipios(estadoId).subscribe(res => {
        this.municipios = res.map((m: any) => ({ label: m.descricao, value: m.id }));
      });
    }
  }

  loadCliente(id: number) {
    this.isLoading = true;
    this.clienteService.findOne(id).subscribe({
      next: (res) => {
        this.cliente = res;
        if (this.cliente.municipioId) {
          // Precisamos carregar os municípios do estado desse município
          // Por simplicidade, se tivermos o UF, carregamos
          if (this.cliente.uf) {
             const estado = this.estados.find(e => e.label === this.cliente.uf);
             if (estado) this.onEstadoChange(estado.value);
          }
        }
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
      nome: "",
      telefone: "",
      email: "",
      tipoContatoId: 1
    });
  }

  removeContato(item: any) {
    const index = this.cliente.contatos.indexOf(item);
    if (index > -1) {
      this.cliente.contatos.splice(index, 1);
    }
  }
}
