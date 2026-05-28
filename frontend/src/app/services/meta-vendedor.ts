import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class MetaVendedorService {
  private readonly API_URL = `${environment.apiUrl}/commercial/metas`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem('token');
    return new HttpHeaders().set('Authorization', `Bearer ${token}`);
  }

  findAll(
    page: number = 1,
    limit: number = 10,
    extraParams: { ano?: string; mes?: string; vendedorId?: number; order?: string } = {}
  ): Observable<any> {
    const query = new URLSearchParams({
      page: String(page),
      limit: String(limit)
    });

    Object.entries(extraParams).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== "") {
        query.set(key, String(value));
      }
    });

    return this.http.get<any>(`${this.API_URL}?${query.toString()}`, { headers: this.getHeaders() });
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

  getSuggestion(vendedorId: number, month: string, year: string): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/suggestion?vendedorId=${vendedorId}&month=${month}&year=${year}`, { headers: this.getHeaders() });
  }

  delete(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
