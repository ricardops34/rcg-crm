import { Component, inject, OnInit } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router } from "@angular/router";
import { PoModule } from "@po-ui/ng-components";
import { AuthService } from "../../services/auth";
import { CrmService } from "../../services/crm";

@Component({
  selector: "app-home",
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: "./home.html",
  styleUrl: "./home.css"
})
export class HomeComponent implements OnInit {
  private authService = inject(AuthService);
  private router = inject(Router);
  private crmService = inject(CrmService);

  userName: string = this.authService.getUser()?.name || "Usuário";
  currentDate: Date = new Date();
  
  agendaEvents: any[] = [];
  isLoadingAgenda: boolean = false;

  // Links rápidos baseados no RCG CRM
  quickLinks = [
    { 
      label: "Cockpit de Vendas (MCV)", 
      route: "/mvc", 
      icon: "an an-chart-line-up", 
      color: "#555ad1", 
      desc: "Acompanhe sua carteira e metas de forma otimizada" 
    },
    { 
      label: "Carteira de Clientes", 
      route: "/clientes", 
      icon: "an an-users-three", 
      color: "#00b28e", 
      desc: "Cadastro detalhado e visualização 360" 
    },
    { 
      label: "Agenda de Atendimentos", 
      route: "/agenda-atendimento", 
      icon: "an an-calendar-blank", 
      color: "#f1ad3d", 
      desc: "Seus compromissos e agendamentos comerciais" 
    },
    { 
      label: "Catálogo de Produtos", 
      route: "/produtos", 
      icon: "an an-package", 
      color: "#e04e4e", 
      desc: "Preços, estoques e informações técnicas" 
    },
    { 
      label: "Faturamento e Notas", 
      route: "/faturamento/notas", 
      icon: "an an-receipt", 
      color: "#9c27b0", 
      desc: "Listagem de saídas e status de notas emitidas" 
    }
  ];

  // Avisos & Notícias simulados
  announcements = [
    { 
      title: "Meta RCG CRM Superada", 
      date: "24/05/2026", 
      type: "success", 
      text: "Parabéns a toda a equipe comercial! Superamos a nossa meta consolidada em 12% no mês vigente!" 
    },
    { 
      title: "MCV com Performance Ultra-Rápida", 
      date: "25/05/2026", 
      type: "info", 
      text: "Lançamos uma otimização profunda no Cockpit de Vendas (MCV) para consultas em menos de 15ms!" 
    }
  ];

  // Aniversariantes do Mês simulados
  birthdays = [
    { 
      name: "Mariana Silva", 
      date: "26 de Maio", 
      role: "Supervisora Comercial", 
      initial: "M" 
    },
    { 
      name: "João Pedro Santos", 
      date: "29 de Maio", 
      role: "Vendedor Interno", 
      initial: "J" 
    },
    { 
      name: "Carla Souza", 
      date: "02 de Junho", 
      role: "Gerente de Contas", 
      initial: "C" 
    }
  ];

  ngOnInit() {
    this.loadAgendaDoDia();
  }

  loadAgendaDoDia() {
    this.isLoadingAgenda = true;
    const todayStr = this.toDateInputValue(new Date());
    this.crmService.getAgenda("day", todayStr).subscribe({
      next: (res) => {
        this.agendaEvents = (res.events || []).map((e: any) => ({
          ...e,
          formattedTime: this.getEventTime(e)
        }));
        this.isLoadingAgenda = false;
      },
      error: (err) => {
        console.warn("Erro ao buscar agenda para painel inicial:", err);
        this.isLoadingAgenda = false;
      }
    });
  }

  getEventTagColor(event: any): string {
    return event.tipo === "venda" ? "success" : "info";
  }

  private toDateInputValue(date: Date): string {
    const year = date.getFullYear();
    const month = `${date.getMonth() + 1}`.padStart(2, "0");
    const day = `${date.getDate()}`.padStart(2, "0");
    return `${year}-${month}-${day}`;
  }

  private getEventTime(event: any): string {
    if (event.tipo === "venda" || !event.inicio || !event.fim) {
      return "Dia todo";
    }

    const start = new Date(event.inicio);
    const end = new Date(event.fim);
    return `${start.toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" })} - ${end.toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" })}`;
  }

  navigateTo(route: string) {
    this.router.navigate([route]);
  }
}
