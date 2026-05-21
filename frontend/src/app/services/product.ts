import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class ProductService {

  private readonly API_URL = `${environment.apiUrl}/produtos`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  findAll(): Observable<any> {
    return this.http.get<any>(this.API_URL, { headers: this.getHeaders() });
  }

  findOne(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  save(data: any): Observable<any> {
    if (data.id) {
      return this.http.put<any>(`${this.API_URL}/${data.id}`, data, { headers: this.getHeaders() });
    }
    return this.http.post<any>(this.API_URL, data, { headers: this.getHeaders() });
  }

  delete(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  getCategorias(): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/categorias`, { headers: this.getHeaders() });
  }
}
