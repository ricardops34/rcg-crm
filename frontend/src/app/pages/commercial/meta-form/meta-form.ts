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
import { MetaVendedorService } from "../../../services/meta-vendedor";
import { VendedorService } from "../../../services/vendedor";

@Component({
  selector: "app-meta-form",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./meta-form.html"
})
export class MetaFormComponent implements OnInit {
  private metaService = inject(MetaVendedorService);
  private vendedorService = inject(VendedorService);
  private activatedRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private poNotification = inject(PoNotificationService);

  meta: any = this.novaMeta();

  isLoading = false;
  title = "Novo Objetivo";

  vendedores: Array<PoSelectOption> = [];

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Metas", link: "/metas" },
      { label: "Manutencao" }
    ]
  };

  readonly monthOptions: Array<PoSelectOption> = [
    { label: "Janeiro", value: "01" },
    { label: "Fevereiro", value: "02" },
    { label: "Marco", value: "03" },
    { label: "Abril", value: "04" },
    { label: "Maio", value: "05" },
    { label: "Junho", value: "06" },
    { label: "Julho", value: "07" },
    { label: "Agosto", value: "08" },
    { label: "Setembro", value: "09" },
    { label: "Outubro", value: "10" },
    { label: "Novembro", value: "11" },
    { label: "Dezembro", value: "12" }
  ];

  ngOnInit(): void {
    this.loadVendedores();

    const id = Number(this.activatedRoute.snapshot.params["id"]);
    if (id) {
      this.title = "Editar Objetivo";
      this.loadMeta(id);
    }
  }

  loadVendedores() {
    this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe({
      next: (res) => {
        this.vendedores = Array.isArray(res?.items)
          ? res.items.map((vendedor: any) => ({ label: vendedor.nome, value: vendedor.id }))
          : [];
      },
      error: () => {
        this.vendedores = [];
        this.poNotification.error("Erro ao carregar vendedores.");
      }
    });
  }

  loadMeta(id: number) {
    this.isLoading = true;

    this.metaService.findOne(id).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        if (!res) {
          this.poNotification.error("Objetivo nao encontrado para edicao.");
          this.router.navigate(["/metas"]);
          return;
        }

        const metaPadrao = this.novaMeta();
        this.meta = {
          ...metaPadrao,
          ...res,
          ano: res.ano ? String(res.ano) : metaPadrao.ano,
          mes: res.mes ? String(res.mes).padStart(2, "0") : metaPadrao.mes,
          vendedorId: res.vendedorId ?? res.vendedor?.id ?? metaPadrao.vendedorId,
          valor: Number(res.valor ?? 0),
          numeroCliente: Number(res.numeroCliente ?? 0),
          novoCliente: Number(res.novoCliente ?? 0)
        };
      },
      error: () => {
        this.poNotification.error("Erro ao carregar objetivo.");
        this.router.navigate(["/metas"]);
      }
    });
  }

  suggestValue() {
    if (!this.meta.vendedorId || !this.meta.mes || !this.meta.ano) {
      this.poNotification.warning("Selecione vendedor, mes e ano para gerar sugestao.");
      return;
    }

    this.isLoading = true;
    this.metaService.getSuggestion(this.meta.vendedorId, this.meta.mes, this.meta.ano).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: (res) => {
        this.meta.valor = Number(res?.suggestion ?? 0);
        this.poNotification.information("Sugestao calculada com base no historico (+10%).");
      },
      error: () => {
        this.poNotification.error("Erro ao gerar sugestao de objetivo.");
      }
    });
  }

  save() {
    this.isLoading = true;

    const payload = {
      ...this.meta,
      ano: String(this.meta.ano ?? ""),
      mes: String(this.meta.mes ?? "").padStart(2, "0"),
      valor: Number(this.meta.valor ?? 0),
      numeroCliente: Number(this.meta.numeroCliente ?? 0),
      novoCliente: Number(this.meta.novoCliente ?? 0)
    };

    this.metaService.save(payload).pipe(
      finalize(() => {
        this.isLoading = false;
      })
    ).subscribe({
      next: () => {
        this.poNotification.success("Objetivo salvo com sucesso!");
        this.router.navigate(["/metas"]);
      },
      error: () => {
        this.poNotification.error("Erro ao salvar objetivo.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/metas"]);
  }

  private novaMeta() {
    return {
      ano: new Date().getFullYear().toString(),
      mes: (new Date().getMonth() + 1).toString().padStart(2, "0"),
      tipo: "M",
      valor: 0,
      numeroCliente: 0,
      novoCliente: 0,
      vendedorId: null
    };
  }
}
