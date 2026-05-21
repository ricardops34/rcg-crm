import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders, HttpParams } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class AnalyticsService {

  private readonly API_URL = `${environment.apiUrl}/analytics`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  getDashboardData(year?: number, month?: number): Observable<any> {
    const y = year || new Date().getFullYear();
    const m = month || new Date().getMonth() + 1;
    
    // Usando HttpParams para garantir o '?' e os '&' corretos
    const params = new HttpParams()
      .set('year', y.toString())
      .set('month', m.toString());

    return this.http.get<any>(`${this.API_URL}/dashboard`, { 
      headers: this.getHeaders(),
      params: params
    });
  }

  getMvcData(params: {
    year?: number;
    vendedorId?: number;
    estadoId?: number;
    municipioId?: number;
    dias?: number;
    situacao?: string;
  } = {}): Observable<any> {
    const y = params.year || new Date().getFullYear();
    let httpParams = new HttpParams().set('year', y.toString());
    
    if (params.vendedorId) httpParams = httpParams.set('vendedorId', params.vendedorId.toString());
    if (params.estadoId) httpParams = httpParams.set('estadoId', params.estadoId.toString());
    if (params.municipioId) httpParams = httpParams.set('municipioId', params.municipioId.toString());
    if (params.dias) httpParams = httpParams.set('dias', params.dias.toString());
    if (params.situacao) httpParams = httpParams.set('situacao', params.situacao);
    
    return this.http.get<any>(`${this.API_URL}/mvc`, { 
      headers: this.getHeaders(),
      params: httpParams
    });
  }
}
