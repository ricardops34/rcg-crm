import { Component, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ActivatedRoute, Router } from "@angular/router";
import { PoModule, PoNotificationService } from "@po-ui/ng-components";
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

  meta: any = { mes: (new Date().getMonth() + 1).toString().padStart(2, "0"), ano: new Date().getFullYear().toString() };
  vendedores: Array<any> = [];
  isLoading: boolean = false;
  title: string = "Novo Objetivo";

  constructor(
    private metaService: MetaVendedorService,
    private vendedorService: VendedorService,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private poNotification: PoNotificationService
  ) { }

  ngOnInit(): void {
    this.loadVendedores();
    const id = this.activatedRoute.snapshot.params["id"];
    if (id) {
      this.title = "Editar Objetivo";
      this.loadMeta(id);
    }
  }

  loadVendedores() {
    this.vendedorService.findAll().subscribe(res => {
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
        this.poNotification.error("Erro ao carregar meta.");
      }
    });
  }

  applySuggestion() {
    if (!this.meta.vendedorId || !this.meta.mes || !this.meta.ano) {
      this.poNotification.warning("Selecione vendedor, m�s e ano para obter a sugest�o.");
      return;
    }

    this.metaService.getSuggestion(this.meta.vendedorId, this.meta.mes, this.meta.ano).subscribe(res => {
      this.meta.valor = res.suggestion;
      this.poNotification.information("Sugest�o de +10% sobre o ano anterior aplicada.");
    });
  }

  save() {
    this.metaService.save(this.meta).subscribe({
      next: () => {
        this.poNotification.success("Meta salva com sucesso!");
        this.router.navigate(["/metas"]);
      },
      error: (err) => {
        this.poNotification.error("Erro ao salvar meta.");
      }
    });
  }

  cancel() {
    this.router.navigate(["/metas"]);
  }
}
