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

  meta: any = {
    ano: new Date().getFullYear().toString(),
    mes: (new Date().getMonth() + 1).toString().padStart(2, '0'),
    tipo: "M",
    valor: 0
  };
  
  isLoading: boolean = false;
  title: string = "Novo Objetivo";
  
  vendedores: Array<PoSelectOption> = [];
  
  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Metas", link: "/metas" },
      { label: "Manutenção" }
    ]
  };

  readonly monthOptions: Array<PoSelectOption> = [
    { label: "Janeiro", value: "01" }, { label: "Fevereiro", value: "02" },
    { label: "Março", value: "03" }, { label: "Abril", value: "04" },
    { label: "Maio", value: "05" }, { label: "Junho", value: "06" },
    { label: "Julho", value: "07" }, { label: "Agosto", value: "08" },
    { label: "Setembro", value: "09" }, { label: "Outubro", value: "10" },
    { label: "Novembro", value: "11" }, { label: "Dezembro", value: "12" }
  ];

  ngOnInit(): void {
    this.loadVendedores();
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Objetivo";
      this.loadMeta(id);
    }
  }

  loadVendedores() {
    this.vendedorService.findAll(1, 100).subscribe(res => {
      this.vendedores = res.items.map((v: any) => ({ label: v.nome, value: v.id }));
    });
  }

  loadMeta(id: number) {
    this.isLoading = true;
    this.metaService.findOne(id).subscribe({
      next: (res) => {
        this.meta = res;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar objetivo.");
      }
    });
  }

  suggestValue() {
    if (!this.meta.vendedorId || !this.meta.mes || !this.meta.ano) {
      this.poNotification.warning("Selecione vendedor, mês e ano para gerar sugestão.");
      return;
    }
    this.isLoading = true;
    this.metaService.getSuggestion(this.meta.vendedorId, this.meta.mes, this.meta.ano).subscribe(res => {
      this.meta.valor = res.suggestion;
      this.isLoading = false;
      this.poNotification.information("Sugestão calculada com base no histórico (+10%).");
    });
  }

  save() {
    this.isLoading = true;
    this.metaService.save(this.meta).subscribe({
      next: () => {
        this.isLoading = false;
        this.poNotification.success("Objetivo salvo com sucesso!");
        this.router.navigate(["/metas"]);
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao salvar objetivo.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/metas"]);
  }
}
