import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
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
    return this.http.get<any>(`${this.API_URL}/dashboard?year=${y}&month=${m}`, { headers: this.getHeaders() });
  }

  getMvcData(year?: number, vendedorId?: number): Observable<any> {
    const y = year || new Date().getFullYear();
    let url = `${this.API_URL}/mvc?year=${y}`;
    if (vendedorId) {
      url += `&vendedorId=${vendedorId}`;
    }
    return this.http.get<any>(url, { headers: this.getHeaders() });
  }
}
