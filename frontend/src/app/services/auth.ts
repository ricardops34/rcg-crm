import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable, tap } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class AuthService {

  private readonly API_URL = `${environment.apiUrl}/auth`;

  constructor(private http: HttpClient) { }

  login(loginData: any): Observable<any> {
    return this.http.post<any>(`${this.API_URL}/login`, loginData).pipe(
      tap(res => {
        if (res.access_token) {
          localStorage.setItem("token", res.access_token);
          localStorage.setItem("user", JSON.stringify(res.user));
        }
      })
    );
  }

  logout(): void {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem("token");
  }
}
