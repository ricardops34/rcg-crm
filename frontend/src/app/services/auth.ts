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
      tap(res => this.handleAuthResponse(res))
    );
  }

  verify2fa(code: string): Observable<any> {
    const token = localStorage.getItem("token");
    return this.http.post<any>(`${this.API_URL}/verify-2fa`, { code }, {
      headers: { Authorization: `Bearer ${token}` }
    }).pipe(
      tap(res => this.handleAuthResponse(res))
    );
  }

  acceptTerms(): Observable<any> {
    const token = localStorage.getItem("token");
    return this.http.post<any>(`${this.API_URL}/accept-terms`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    }).pipe(
      tap(res => this.handleAuthResponse(res))
    );
  }

  private handleAuthResponse(res: any) {
    if (res.accessToken) {
      localStorage.setItem("token", res.accessToken);
      if (!res.nextStep) {
        localStorage.setItem("user", JSON.stringify(res.user));
      }
    }
  }

  logout(): void {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem("token");
  }

  getUser(): any {
    const user = localStorage.getItem("user");
    return user ? JSON.parse(user) : null;
  }

  hasPermission(controller: string): boolean {
    const user = this.getUser();
    if (!user || !user.programs) return false;
    return user.programs.some((p: any) => p.controller === controller);
  }
}
