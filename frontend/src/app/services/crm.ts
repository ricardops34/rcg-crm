import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class CrmService {

  private readonly API_URL = `${environment.apiUrl}/crm`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  findAll(start?: string, end?: string, vendedorId?: number): Observable<any> {
    const params = new URLSearchParams();
    if (start) params.set("start", start);
    if (end) params.set("end", end);
    if (vendedorId) params.set("vendedorId", String(vendedorId));

    const query = params.toString();
    const url = query ? `${this.API_URL}/atendimentos?${query}` : `${this.API_URL}/atendimentos`;

    return this.http.get<any>(url, { headers: this.getHeaders() });
  }

  getTipos(): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/tipos`, { headers: this.getHeaders() });
  }

  getAgenda(
    view: "month" | "week" | "day",
    date: string,
    vendedorId?: number,
    atendimentoTipoId?: number,
  ): Observable<any> {
    const params = new URLSearchParams({
      view,
      date
    });

    if (vendedorId) {
      params.set("vendedorId", String(vendedorId));
    }

    if (atendimentoTipoId) {
      params.set("atendimentoTipoId", String(atendimentoTipoId));
    }

    return this.http.get<any>(`${this.API_URL}/agenda?${params.toString()}`, { headers: this.getHeaders() });
  }

  getAgendaRange(
    start: string,
    end: string,
    view: "month" | "week" | "day",
    date: string,
    vendedorId?: number,
    atendimentoTipoId?: number,
  ): Observable<any> {
    const params = new URLSearchParams({
      start,
      end,
      view,
      date
    });

    if (vendedorId) {
      params.set("vendedorId", String(vendedorId));
    }

    if (atendimentoTipoId) {
      params.set("atendimentoTipoId", String(atendimentoTipoId));
    }

    return this.http.get<any>(`${this.API_URL}/agenda?${params.toString()}`, { headers: this.getHeaders() });
  }

  save(data: any): Observable<any> {
    return this.http.post<any>(`${this.API_URL}/atendimentos`, data, { headers: this.getHeaders() });
  }
}
