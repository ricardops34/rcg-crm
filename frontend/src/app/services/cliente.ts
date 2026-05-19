import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class ClienteService {

  private readonly API_URL = `${environment.apiUrl}/commercial/clientes`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  findAll(page: number = 1, limit: number = 10): Observable<any> {
    return this.http.get<any>(`${this.API_URL}?page=${page}&limit=${limit}`, { headers: this.getHeaders() });
  }

  findOne(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  create(data: any): Observable<any> {
    return this.http.post<any>(this.API_URL, data, { headers: this.getHeaders() });
  }

  update(id: number, data: any): Observable<any> {
    return this.http.patch<any>(`${this.API_URL}/${id}`, data, { headers: this.getHeaders() });
  }

  getComodato(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}/comodato`, { headers: this.getHeaders() });
  }

  getMix(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}/mix`, { headers: this.getHeaders() });
  }

  getFinanceiro(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}/financeiro`, { headers: this.getHeaders() });
  }

  getNotas(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}/notas`, { headers: this.getHeaders() });
  }

  getAtendimentos(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}/atendimentos`, { headers: this.getHeaders() });
  }

  delete(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
