import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class NegociacaoService {

  private readonly API_URL = `${environment.apiUrl}/finance/negociacoes`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  getDelinquentClients(): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/inadimplentes`, { headers: this.getHeaders() });
  }

  getOverdueTitles(clienteId: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/titulos-vencidos/${clienteId}`, { headers: this.getHeaders() });
  }

  create(data: any): Observable<any> {
    return this.http.post<any>(this.API_URL, data, { headers: this.getHeaders() });
  }

  findAll(): Observable<any> {
    return this.http.get<any>(this.API_URL, { headers: this.getHeaders() });
  }
}
