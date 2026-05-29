import { CommonModule } from "@angular/common";
import { Component, OnInit, ViewChild, inject } from "@angular/core";
import { FormsModule } from "@angular/forms";
import {
  CalendarApp,
  CalendarEvent,
  createCalendar,
  viewDay,
  viewMonthGrid,
  viewWeek
} from "@schedule-x/calendar";
import { CalendarComponent as ScheduleXCalendarComponent } from "@schedule-x/angular";
import { Temporal } from "temporal-polyfill";
import {
  PoBreadcrumb,
  PoInfoOrientation,
  PoModalComponent,
  PoModule,
  PoNotificationService,
  PoPageAction,
  PoSelectOption
} from "@po-ui/ng-components";
import { AuthService } from "../../../services/auth";
import { CrmService } from "../../../services/crm";
import { VendedorService } from "../../../services/vendedor";

interface AgendaEvent {
  id: string;
  tipo: "venda" | "atendimento";
  titulo: string;
  cor: string;
  clienteNome?: string;
  notaFiscal?: string;
  valor?: number;
  inicio?: string;
  fim?: string;
  data?: string;
  observacao?: string;
  vendedorNome?: string;
  atendimentoTipoDescricao?: string;
  atendimentoTipoId?: number;
  notaSaidaId?: number;
  clienteId?: number;
}

@Component({
  selector: "app-agenda-atendimento",
  standalone: true,
  imports: [CommonModule, FormsModule, PoModule, ScheduleXCalendarComponent],
  templateUrl: "./agenda-atendimento.html",
  styleUrl: "./agenda-atendimento.css"
})
export class AgendaAtendimentoComponent implements OnInit {
  @ViewChild("modalDetalhe", { static: true }) modalDetalhe!: PoModalComponent;
  @ViewChild("modalAtendimento", { static: true }) modalAtendimento!: PoModalComponent;

  private readonly authService = inject(AuthService);
  private readonly crmService = inject(CrmService);
  private readonly vendedorService = inject(VendedorService);
  private readonly poNotification = inject(PoNotificationService);
  private readonly timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || "America/Cuiaba";

  readonly breadcrumb: PoBreadcrumb = {
    items: [
      { label: "Home", link: "/" },
      { label: "Agenda Comercial" }
    ]
  };

  readonly pageActions: Array<PoPageAction> = [
    { label: "Hoje", action: () => this.goToday(), icon: "an an-calendar-blank" },
    { label: "Novo Atendimento", action: () => this.openAtendimento(), icon: "an an-plus" },
    { label: "Atualizar", action: () => this.refreshAgenda(), icon: "an an-arrows-clockwise" }
  ];

  readonly infoOrientation = PoInfoOrientation.Horizontal;

  vendedores: Array<PoSelectOption> = [];
  filtroTiposAtendimento: Array<PoSelectOption> = [];
  tiposAtendimento: Array<PoSelectOption> = [];
  tiposAtendimentoMap: Record<number, any> = {};

  selectedDate = this.toDateInputValue(new Date());
  selectedVendedor?: number;
  selectedAtendimentoTipo?: number;
  isGerente = false;
  isLoading = false;

  agenda: any = {
    summary: {
      totalNotas: 0,
      totalValor: 0,
      totalAtendimentos: 0
    },
    events: []
  };

  calendar!: CalendarApp;
  selectedEvent: AgendaEvent | null = null;
  atendimento: any = {};

  private currentRange?: { start: string; end: string };

  ngOnInit() {
    const user = this.authService.getUser();
    this.isGerente = !!user?.isGerente || !!user?.supervisorId;

    if (this.isGerente) {
      this.vendedorService.findAll(1, 1000, { status: "A", dashboard: "S" }).subscribe(res => {
        this.vendedores = res.items.map((item: any) => ({
          label: item.nome,
          value: item.id
        }));
      });
    }

    this.crmService.getTipos().subscribe(res => {
      this.tiposAtendimentoMap = Object.fromEntries(res.map((item: any) => [item.id, item]));
      this.tiposAtendimento = res.map((item: any) => ({
        label: item.descricao,
        value: item.id
      }));
      this.filtroTiposAtendimento = [
        { label: "Todos os tipos", value: undefined as any },
        ...this.tiposAtendimento
      ];
    });

    this.initializeCalendar();
  }

  goToday() {
    this.selectedDate = this.toDateInputValue(new Date());
    this.initializeCalendar();
  }

  goToDate() {
    this.initializeCalendar();
  }

  refreshAgenda() {
    if (!this.currentRange) {
      this.initializeCalendar();
      return;
    }

    this.loadAgenda(this.currentRange.start, this.currentRange.end);
  }

  openDetail(event: AgendaEvent) {
    this.selectedEvent = event;
    this.modalDetalhe.open();
  }

  openAtendimento(event?: AgendaEvent | null, referenceDate?: Date) {
    const baseDate =
      referenceDate ||
      (event?.inicio ? new Date(event.inicio) : this.fromInputDate(this.selectedDate));
    const startDate = new Date(baseDate);
    startDate.setSeconds(0, 0);
    if (!event?.inicio && !referenceDate) {
      startDate.setHours(8, 0, 0, 0);
    }

    const endDate = new Date(startDate);
    endDate.setHours(startDate.getHours() + 1);

    this.selectedEvent = event || null;
    this.atendimento = {
      clienteId: event?.clienteId,
      notaSaidaId: event?.notaSaidaId,
      titulo:
        event?.tipo === "venda"
          ? `Atendimento pós-venda - ${event.clienteNome || "Cliente"}`
          : event?.titulo || "",
      observacao:
        event?.tipo === "venda"
          ? `Contato referente à ${event.titulo}.`
          : event?.observacao || "",
      atendimentoTipoId: undefined,
      horarioInicial: startDate,
      horarioFinal: endDate,
      retorno: undefined,
      cor: event?.cor
    };

    this.modalAtendimento.open();
  }

  saveAtendimento() {
    if (!this.atendimento.atendimentoTipoId || !this.atendimento.horarioInicial || !this.atendimento.horarioFinal) {
      this.poNotification.warning("Preencha tipo e horário do atendimento.");
      return;
    }

    this.isLoading = true;
    this.crmService.save(this.atendimento).subscribe({
      next: () => {
        this.poNotification.success("Atendimento registrado com sucesso.");
        this.modalAtendimento.close();
        this.modalDetalhe.close();
        this.refreshAgenda();
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao registrar atendimento.");
      }
    });
  }

  onFilterChange() {
    this.refreshAgenda();
  }

  onAtendimentoTipoChange(tipoId: any) {
    this.atendimento.atendimentoTipoId = tipoId;
    const tipo = this.tiposAtendimentoMap[Number(tipoId)];

    if (tipo?.cor) {
      this.atendimento.cor = tipo.cor;
    }

    if (!this.atendimento.titulo && tipo?.descricao) {
      this.atendimento.titulo = tipo.descricao;
    }
  }

  getEventTime(event: AgendaEvent) {
    if (event.tipo === "venda" || !event.inicio || !event.fim) {
      return "Dia todo";
    }

    const start = new Date(event.inicio);
    const end = new Date(event.fim);
    return `${start.toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" })} - ${end.toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" })}`;
  }

  getEventTagColor(event: AgendaEvent) {
    return event.tipo === "venda" ? "color-10" : "color-08";
  }

  formatCurrency(value?: number) {
    if (!value) {
      return "";
    }

    return new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL"
    }).format(value);
  }

  private initializeCalendar() {
    this.calendar = createCalendar({
      locale: "pt-BR",
      firstDayOfWeek: 1,
      selectedDate: Temporal.PlainDate.from(this.selectedDate),
      defaultView: viewMonthGrid.name,
      views: [viewMonthGrid, viewWeek, viewDay],
      dayBoundaries: {
        start: "07:00",
        end: "20:00"
      },
      weekOptions: {
        gridHeight: 1400,
        nDays: 7,
        eventWidth: 96,
        eventOverlap: true,
        gridStep: 30,
        timeAxisFormatOptions: {
          hour: "2-digit",
          minute: "2-digit"
        }
      },
      calendars: {
        vendas: {
          colorName: "vendas",
          label: "Vendas",
          lightColors: {
            main: "#0f766e",
            container: "#d1fae5",
            onContainer: "#134e4a"
          }
        },
        atendimentos: {
          colorName: "atendimentos",
          label: "Atendimentos",
          lightColors: {
            main: "#2563eb",
            container: "#dbeafe",
            onContainer: "#1e3a8a"
          }
        }
      },
      callbacks: {
        onRangeUpdate: (range: any) => {
          const start = range.start.toPlainDate().toString();
          const end = range.end.toPlainDate().toString();
          this.currentRange = { start, end };
          this.loadAgenda(start, end);
        },
        onSelectedDateUpdate: (date: any) => {
          this.selectedDate = date.toString();
        },
        onEventClick: (event: CalendarEvent) => {
          this.openDetail(this.toAgendaEvent(event));
        },
        onClickDate: (date: any) => {
          this.selectedDate = date.toString();
        },
        onClickDateTime: (dateTime: any) => {
          this.selectedDate = dateTime.toPlainDate().toString();
          this.openAtendimento(null, this.temporalToDate(dateTime));
        }
      },
      events: []
    });
  }

  private loadAgenda(start: string, end: string) {
    this.isLoading = true;
    this.crmService.getAgendaRange(
      start,
      end,
      "month",
      this.selectedDate,
      this.selectedVendedor,
      this.selectedAtendimentoTipo
    ).subscribe({
      next: (res) => {
        this.agenda = res;
        this.calendar.events.set((res.events || []).map((event: AgendaEvent) => this.mapCalendarEvent(event)));
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
        this.poNotification.error("Erro ao carregar agenda comercial.");
      }
    });
  }

  private mapCalendarEvent(event: AgendaEvent): CalendarEvent {
    const baseEvent = {
      ...event,
      id: event.id,
      title: event.titulo,
      calendarId: event.tipo === "venda" ? "vendas" : "atendimentos",
      description: event.observacao,
      _options: {
        disableDND: true,
        disableResize: true
      },
      _customContent: {
        monthGrid: this.renderMonthGridEvent(event),
        monthAgenda: this.renderAgendaEvent(event),
        dateGrid: this.renderDateGridEvent(event),
        timeGrid: this.renderTimeGridEvent(event)
      }
    };

    if (event.tipo === "venda") {
      const date = Temporal.PlainDate.from(String(event.data || event.inicio).slice(0, 10));
      return {
        ...baseEvent,
        start: date,
        end: date
      };
    }

    return {
      ...baseEvent,
      start: this.toZonedDateTime(event.inicio || event.data || this.selectedDate),
      end: this.toZonedDateTime(event.fim || event.inicio || event.data || this.selectedDate)
    };
  }

  private toAgendaEvent(event: CalendarEvent): AgendaEvent {
    const typedEvent = event as unknown as AgendaEvent;
    return {
      ...typedEvent,
      titulo: typedEvent.titulo || event.title || "",
      cor: typedEvent.cor || "#2563eb",
      tipo: typedEvent.tipo || "atendimento"
    };
  }

  private renderMonthGridEvent(event: AgendaEvent) {
    const label = event.tipo === "venda" ? "NF" : (event.atendimentoTipoDescricao || "CRM");
    return `
      <div class="agenda-sx-chip" style="border-left-color:${this.escapeHtml(event.cor)}">
        <span class="agenda-sx-chip__label">${this.escapeHtml(label)}</span>
        <span class="agenda-sx-chip__title">${this.escapeHtml(event.titulo)}</span>
      </div>
    `;
  }

  private renderAgendaEvent(event: AgendaEvent) {
    return `
      <div class="agenda-sx-row" style="border-left-color:${this.escapeHtml(event.cor)}">
        <strong>${this.escapeHtml(event.titulo)}</strong>
        <span>${this.escapeHtml(event.clienteNome || "")}</span>
      </div>
    `;
  }

  private renderDateGridEvent(event: AgendaEvent) {
    return `
      <div class="agenda-sx-bar" style="background:${this.escapeHtml(event.cor)}">
        <span>${this.escapeHtml(event.titulo)}</span>
      </div>
    `;
  }

  private renderTimeGridEvent(event: AgendaEvent) {
    const secondaryLine = event.tipo === "venda"
      ? this.formatCurrency(event.valor) || "Venda"
      : event.clienteNome || event.atendimentoTipoDescricao || "Atendimento";

    return `
      <div class="agenda-sx-card" style="border-left-color:${this.escapeHtml(event.cor)}">
        <strong>${this.escapeHtml(event.titulo)}</strong>
        <span>${this.escapeHtml(secondaryLine)}</span>
      </div>
    `;
  }

  private toDateInputValue(date: Date) {
    const year = date.getFullYear();
    const month = `${date.getMonth() + 1}`.padStart(2, "0");
    const day = `${date.getDate()}`.padStart(2, "0");
    return `${year}-${month}-${day}`;
  }

  private fromInputDate(date: string) {
    return new Date(`${date}T12:00:00`);
  }

  private toZonedDateTime(value: string) {
    return Temporal.PlainDateTime.from(this.normalizeDateTime(value)).toZonedDateTime(this.timezone);
  }

  private normalizeDateTime(value: string) {
    const isoValue = value.replace(" ", "T");
    return isoValue.length === 10 ? `${isoValue}T08:00:00` : isoValue;
  }

  private temporalToDate(value: any) {
    return new Date(value.toString().replace(`[${this.timezone}]`, ""));
  }

  private escapeHtml(value: string) {
    return value
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll("\"", "&quot;")
      .replaceAll("'", "&#39;");
  }
}
