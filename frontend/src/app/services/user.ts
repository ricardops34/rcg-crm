import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class UserService {

  private readonly API_URL = `${environment.apiUrl}/admin/users`;

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

  save(user: any): Observable<any> {
    if (user.id) {
      const { id, ...rest } = user;
      return this.http.put<any>(`${this.API_URL}/${id}`, rest, { headers: this.getHeaders() });
    } else {
      return this.http.post<any>(this.API_URL, user, { headers: this.getHeaders() });
    }
  }

  delete(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
