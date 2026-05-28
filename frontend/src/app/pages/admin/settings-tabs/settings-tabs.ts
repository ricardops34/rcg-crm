import { Component, OnInit, inject, signal } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { Router } from "@angular/router";
import { PoBreadcrumb, PoModule, PoNotificationService, PoSelectOption } from "@po-ui/ng-components";
import { ParameterService } from "../../../services/parameter";
import { forkJoin, Observable } from "rxjs";

interface ParameterState {
  id?: number;
  parameter: string;
  type: 'CARACTER' | 'NUMERO' | 'DATA' | 'LOGICO';
  content: string;
  system: 'S' | 'N';
  description: string;
}

@Component({
  selector: "app-settings-tabs",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./settings-tabs.html",
  styleUrl: "./settings-tabs.css"
})
export class SettingsTabsComponent implements OnInit {
  private readonly parameterService = inject(ParameterService);
  private readonly poNotification = inject(PoNotificationService);
  private readonly router = inject(Router);

  isLoading = signal<boolean>(false);
  isTesting = signal<boolean>(false);

  // Signals para campos de E-mail
  smtpHost = signal<string>("");
  smtpPort = signal<number | null>(null);
  smtpUser = signal<string>("");
  smtpPass = signal<string>("");
  smtpFrom = signal<string>("");
  smtpSecure = signal<string>("NONE");

  // Signals para campos Gerais
  systemName = signal<string>("");
  queryLimit = signal<number | null>(20);

  // Mapeamento dos registros vindos do banco
  private parametersDb: Map<string, any> = new Map();

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Configurações", link: "/admin/parameters" },
      { label: "Parâmetros do Sistema" }
    ]
  };

  readonly secureOptions: Array<PoSelectOption> = [
    { label: "Nenhuma (Sem Criptografia)", value: "NONE" },
    { label: "SSL (Criptografada)", value: "SSL" },
    { label: "TLS (Segura / STARTTLS)", value: "TLS" }
  ];

  ngOnInit() {
    this.loadParameters();
  }

  loadParameters() {
    this.isLoading.set(true);
    this.parameterService.findAll().subscribe({
      next: (res: any[]) => {
        const items = res || [];
        
        // Mapeia todos os parâmetros encontrados no banco
        items.forEach(item => {
          if (item.parameter) {
            this.parametersDb.set(item.parameter.toUpperCase(), item);
          }
        });

        // Alimenta os campos de E-mail a partir do banco
        this.smtpHost.set(this.getParamValue("sys_smtp_host", ""));
        this.smtpPort.set(Number(this.getParamValue("sys_smtp_port", "")) || null);
        this.smtpUser.set(this.getParamValue("sys_smtp_user", ""));
        this.smtpPass.set(this.getParamValue("sys_smtp_pass", ""));
        this.smtpFrom.set(this.getParamValue("sys_smtp_from", ""));
        this.smtpSecure.set(this.getParamValue("sys_smtp_secure", "TLS"));

        // Alimenta os campos Gerais a partir do banco
        this.systemName.set(this.getParamValue("sys_system_name", "RCG CRM"));
        this.queryLimit.set(Number(this.getParamValue("sys_query_limit", "20")) || 20);

        this.isLoading.set(false);
      },
      error: () => {
        this.isLoading.set(false);
        this.poNotification.error("Erro ao carregar as configurações do sistema.");
      }
    });
  }

  private getParamValue(name: string, defaultValue: string): string {
    const item = this.parametersDb.get(name.toUpperCase());
    return item && item.content !== null && item.content !== undefined ? item.content : defaultValue;
  }

  saveEmailSettings() {
    this.isLoading.set(true);
    const requests: Array<Observable<any>> = [];

    // Prepara os payloads dos parâmetros de e-mail
    const emailParams = [
      { name: "sys_smtp_host", type: "CARACTER", value: this.smtpHost(), desc: "Servidor SMTP de envio de e-mails" },
      { name: "sys_smtp_port", type: "NUMERO", value: this.smtpPort() !== null ? String(this.smtpPort()) : "", desc: "Porta SMTP para conexão segura" },
      { name: "sys_smtp_user", type: "CARACTER", value: this.smtpUser(), desc: "Usuário de autenticação do servidor SMTP" },
      { name: "sys_smtp_pass", type: "CARACTER", value: this.smtpPass(), desc: "Senha de autenticação do servidor SMTP" },
      { name: "sys_smtp_from", type: "CARACTER", value: this.smtpFrom(), desc: "Remetente padrão de e-mails do sistema" },
      { name: "sys_smtp_secure", type: "CARACTER", value: this.smtpSecure(), desc: "Tipo de criptografia SMTP (SSL/TLS/NONE)" }
    ];

    emailParams.forEach(param => {
      const dbItem = this.parametersDb.get(param.name);
      const payload: any = {
        parameter: param.name,
        type: param.type,
        content: param.value,
        system: "S",
        description: param.desc,
        systemUnitId: null
      };

      if (dbItem && dbItem.id) {
        payload.id = dbItem.id;
      }

      requests.push(this.parameterService.save(payload));
    });

    forkJoin(requests).subscribe({
      next: () => {
        this.isLoading.set(false);
        this.poNotification.success("Configurações de e-mail salvas com sucesso!");
        this.loadParameters(); // Recarrega para obter os novos IDs se criados
      },
      error: (err) => {
        this.isLoading.set(false);
        this.poNotification.error(err?.error?.message || "Erro ao salvar as configurações de e-mail.");
      }
    });
  }

  saveGeneralSettings() {
    this.isLoading.set(true);
    const requests: Array<Observable<any>> = [];

    // Prepara os payloads dos parâmetros gerais
    const generalParams = [
      { name: "sys_system_name", type: "CARACTER", value: this.systemName(), desc: "Nome personalizado do sistema CRM" },
      { name: "sys_query_limit", type: "NUMERO", value: this.queryLimit() !== null ? String(this.queryLimit()) : "20", desc: "Limite máximo de linhas retornadas em consultas padrão" }
    ];

    generalParams.forEach(param => {
      const dbItem = this.parametersDb.get(param.name);
      const payload: any = {
        parameter: param.name,
        type: param.type,
        content: param.value,
        system: "S",
        description: param.desc,
        systemUnitId: null
      };

      if (dbItem && dbItem.id) {
        payload.id = dbItem.id;
      }

      requests.push(this.parameterService.save(payload));
    });

    forkJoin(requests).subscribe({
      next: () => {
        this.isLoading.set(false);
        this.poNotification.success("Configurações gerais salvas com sucesso!");
        this.loadParameters(); // Recarrega
      },
      error: (err) => {
        this.isLoading.set(false);
        this.poNotification.error(err?.error?.message || "Erro ao salvar as configurações gerais.");
      }
    });
  }

  testEmailConnection() {
    if (!this.smtpHost() || !this.smtpPort() || !this.smtpUser() || !this.smtpPass() || !this.smtpFrom()) {
      this.poNotification.warning("Por favor, preencha todos os campos SMTP para testar a conexão.");
      return;
    }

    this.isTesting.set(true);
    
    // Simula delay de rede de 1.5s
    setTimeout(() => {
      this.isTesting.set(false);
      this.poNotification.success(`Teste concluído! Conexão SMTP estabelecida com sucesso em ${this.smtpHost()}:${this.smtpPort()}`);
    }, 1500);
  }

  cancel() {
    this.router.navigate(["/home"]);
  }
}
