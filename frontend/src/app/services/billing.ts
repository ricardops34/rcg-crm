import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders, HttpParams } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class BillingService {

  private readonly API_URL = `${environment.apiUrl}/billing/notas`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  findAll(params: any = {}): Observable<any> {
    let httpParams = new HttpParams();
    Object.keys(params).forEach(key => {
      if (params[key]) httpParams = httpParams.set(key, params[key]);
    });

    return this.http.get<any>(this.API_URL, { 
      headers: this.getHeaders(),
      params: httpParams
    });
  }

  findOne(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  getComodatos(): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/comodatos`, { headers: this.getHeaders() });
  }

  downloadXml(id: number) {
    const url = `${this.API_URL}/${id}/xml`;
    window.open(url, "_blank");
  }

  viewDanfe(id: number) {
    const url = `${this.API_URL}/${id}/danfe`;
    window.open(url, "_blank");
  }
}
