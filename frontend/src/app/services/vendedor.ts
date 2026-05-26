import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { Vendedor, VendedorFilters, VendedorListResponse } from './models/vendedor.model';

@Injectable({
  providedIn: 'root'
})
export class VendedorService {
  private readonly API_URL = `${environment.apiUrl}/commercial/vendedores`;

  constructor(private http: HttpClient) { }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders().set('Authorization', `Bearer ${token}`);
  }

  findAll(page: number = 1, limit: number = 10, extraParams: VendedorFilters = {}): Observable<VendedorListResponse> {
    let params = new HttpParams()
      .set('page', page.toString())
      .set('limit', limit.toString());

    Object.entries(extraParams).forEach(([key, value]) => {
      if (value !== undefined && value !== null) {
        params = params.set(key, String(value));
      }
    });

    return this.http.get<VendedorListResponse>(this.API_URL, { headers: this.getHeaders(), params });
  }

  findOne(id: number): Observable<Vendedor> {
    return this.http.get<Vendedor>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  save(data: Vendedor): Observable<Vendedor> {
    if (data.id) {
      return this.http.put<Vendedor>(`${this.API_URL}/${data.id}`, data, { headers: this.getHeaders() });
    }
    return this.http.post<Vendedor>(this.API_URL, data, { headers: this.getHeaders() });
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
