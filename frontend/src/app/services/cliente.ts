import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ClienteService {

  private readonly API_URL = 'http://localhost:3000/commercial/clientes';

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem('token');
    return new HttpHeaders().set('Authorization', `Bearer ${token}`);
  }

  findAll(page: number = 1, limit: number = 10): Observable<any> {
    return this.http.get<any>(`${this.API_URL}?page=${page}&limit=${limit}`, { headers: this.getHeaders() });
  }

  findOne(id: number): Observable<any> {
    return this.http.get<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
