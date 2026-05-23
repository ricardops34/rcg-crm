import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class VendedorService {
  private readonly API_URL = `${environment.apiUrl}/commercial/vendedores`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem('token');
    return new HttpHeaders().set('Authorization', `Bearer ${token}`);
  }

  findAll(page: number = 1, limit: number = 10, extraParams: any = {}): Observable<any> {
    let url = `${this.API_URL}?page=${page}&limit=${limit}`;
    Object.keys(extraParams).forEach(key => {
      if (extraParams[key] !== undefined && extraParams[key] !== null) {
        url += `&${key}=${extraParams[key]}`;
      }
    });
    return this.http.get<any>(url, { headers: this.getHeaders() });
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
}
