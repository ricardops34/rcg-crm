import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders, HttpParams } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";
import {
  AgendaView,
  CrmAgendaResponse,
  CrmAtendimento,
  CrmAtendimentoListItem,
  CrmTipoAtendimento
} from "./models/crm.model";

@Injectable({
  providedIn: "root"
})
export class CrmService {

  private readonly API_URL = `${environment.apiUrl}/crm`;

  constructor(private http: HttpClient) { }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  findAll(start?: string, end?: string, vendedorId?: number): Observable<CrmAtendimentoListItem[]> {
    let params = new HttpParams();
    if (start) params = params.set("start", start);
    if (end) params = params.set("end", end);
    if (vendedorId) params = params.set("vendedorId", String(vendedorId));

    return this.http.get<CrmAtendimentoListItem[]>(`${this.API_URL}/atendimentos`, {
      headers: this.getHeaders(),
      params
    });
  }

  getTipos(): Observable<CrmTipoAtendimento[]> {
    return this.http.get<CrmTipoAtendimento[]>(`${this.API_URL}/tipos`, { headers: this.getHeaders() });
  }

  getAgenda(
    view: AgendaView,
    date: string,
    vendedorId?: number,
    atendimentoTipoId?: number,
  ): Observable<CrmAgendaResponse> {
    let params = new HttpParams()
      .set("view", view)
      .set("date", date);

    if (vendedorId) {
      params = params.set("vendedorId", String(vendedorId));
    }

    if (atendimentoTipoId) {
      params = params.set("atendimentoTipoId", String(atendimentoTipoId));
    }

    return this.http.get<CrmAgendaResponse>(`${this.API_URL}/agenda`, { headers: this.getHeaders(), params });
  }

  getAgendaRange(
    start: string,
    end: string,
    view: AgendaView,
    date: string,
    vendedorId?: number,
    atendimentoTipoId?: number,
  ): Observable<CrmAgendaResponse> {
    let params = new HttpParams()
      .set("start", start)
      .set("end", end)
      .set("view", view)
      .set("date", date);

    if (vendedorId) {
      params = params.set("vendedorId", String(vendedorId));
    }

    if (atendimentoTipoId) {
      params = params.set("atendimentoTipoId", String(atendimentoTipoId));
    }

    return this.http.get<CrmAgendaResponse>(`${this.API_URL}/agenda`, { headers: this.getHeaders(), params });
  }

  save(data: CrmAtendimento): Observable<CrmAtendimento> {
    return this.http.post<CrmAtendimento>(`${this.API_URL}/atendimentos`, data, { headers: this.getHeaders() });
  }
}
