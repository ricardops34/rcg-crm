import { Injectable, signal } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable, tap } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class AuthService {

  private readonly API_URL = `${environment.apiUrl}/auth`;

  currentUser = signal<any>(this.getUser());

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

  handleAuthResponse(res: any) {
    if (res.accessToken) {
      localStorage.setItem("token", res.accessToken);
      if (!res.nextStep) {
        localStorage.setItem("user", JSON.stringify(res.user));
        this.currentUser.set(res.user);
      }
    }
  }

  logout(): void {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    this.currentUser.set(null);
  }

  isAuthenticated(): boolean {
    const token = localStorage.getItem("token");
    if (!token) return false;

    try {
      // Decodifica a carga útil (payload) do JWT para verificar a data de expiração (exp)
      const payload = JSON.parse(atob(token.split('.')[1]));
      if (payload.exp && Date.now() >= payload.exp * 1000) {
        this.logout(); // Limpa o token expirado do localStorage
        return false;
      }
    } catch (e) {
      this.logout(); // Limpa em caso de token corrompido
      return false;
    }

    return true;
  }

  getUser(): any {
    const user = localStorage.getItem("user");
    return user ? JSON.parse(user) : null;
  }

  hasPermission(controller: string): boolean {
    const user = this.getUser();
    if (!user) return false;
    if (user.login === 'admin' || user.roles?.includes('ADMIN')) return true;
    if (!user.programs) return false;
    return user.programs.some((p: any) => p.controller === controller);
  }

  updateProfile(data: any): Observable<any> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.patch<any>(`${this.API_URL}/me`, data, { headers }).pipe(
      tap(updatedUser => {
        localStorage.setItem("user", JSON.stringify(updatedUser));
        this.currentUser.set(updatedUser);
      })
    );
  }

  getMenu(): Observable<any> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.get<any>(`${this.API_URL}/me/menu`, { headers });
  }

  getTerms(): Observable<any> {
    return this.http.get<any>(`${environment.apiUrl}/admin/users/terms`);
  }

  saveTerms(data: any): Observable<any> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.post<any>(`${environment.apiUrl}/admin/users/terms`, data, { headers });
  }
}
