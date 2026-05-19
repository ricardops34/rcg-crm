import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class LocationService {

  private readonly API_URL = `${environment.apiUrl}/master-data`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  getEstados(): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/estados`, { headers: this.getHeaders() });
  }

  getMunicipios(estadoId?: number): Observable<any> {
    const url = estadoId ? `${this.API_URL}/municipios?estadoId=${estadoId}` : `${this.API_URL}/municipios`;
    return this.http.get<any>(url, { headers: this.getHeaders() });
  }
}
