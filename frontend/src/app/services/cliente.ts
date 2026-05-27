import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders, HttpParams } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";
import {
  Cliente,
  ClienteAtendimentoItem,
  ClienteComodatoItem,
  ClienteEstoqueEstimadoItem,
  ClienteFinanceiroItem,
  ClienteListResponse,
  ClienteMixItem,
  ClienteNotaItem,
} from "./models/cliente.model";

@Injectable({
  providedIn: "root"
})
export class ClienteService {

  private readonly API_URL = `${environment.apiUrl}/commercial/clientes`;

  constructor(private http: HttpClient) { }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  findAll(page: number = 1, limit: number = 10): Observable<ClienteListResponse> {
    const params = new HttpParams()
      .set("page", page.toString())
      .set("limit", limit.toString());

    return this.http.get<ClienteListResponse>(this.API_URL, { headers: this.getHeaders(), params });
  }

  findOne(id: number): Observable<Cliente> {
    return this.http.get<Cliente>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  create(data: Cliente): Observable<Cliente> {
    return this.http.post<Cliente>(this.API_URL, data, { headers: this.getHeaders() });
  }

  update(id: number, data: Cliente): Observable<Cliente> {
    return this.http.patch<Cliente>(`${this.API_URL}/${id}`, data, { headers: this.getHeaders() });
  }

  getComodato(id: number): Observable<ClienteComodatoItem[]> {
    return this.http.get<ClienteComodatoItem[]>(`${this.API_URL}/${id}/comodato`, { headers: this.getHeaders() });
  }

  getMix(id: number): Observable<ClienteMixItem[]> {
    return this.http.get<ClienteMixItem[]>(`${this.API_URL}/${id}/mix`, { headers: this.getHeaders() });
  }

  getFinanceiro(id: number): Observable<ClienteFinanceiroItem[]> {
    return this.http.get<ClienteFinanceiroItem[]>(`${this.API_URL}/${id}/financeiro`, { headers: this.getHeaders() });
  }

  getNotas(id: number, monthsOffset: number = 0, monthsWindow: number = 12): Observable<any> {
    const params = new HttpParams()
      .set("monthsOffset", monthsOffset.toString())
      .set("monthsWindow", monthsWindow.toString());

    return this.http.get<any>(`${this.API_URL}/${id}/notas`, { headers: this.getHeaders(), params });
  }

  getAtendimentos(id: number): Observable<ClienteAtendimentoItem[]> {
    return this.http.get<ClienteAtendimentoItem[]>(`${this.API_URL}/${id}/atendimentos`, { headers: this.getHeaders() });
  }

  getEstoqueEstimado(id: number): Observable<ClienteEstoqueEstimadoItem[]> {
    return this.http.get<ClienteEstoqueEstimadoItem[]>(`${this.API_URL}/${id}/estoque-estimado`, { headers: this.getHeaders() });
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
