import { Injectable, signal, computed } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable, tap } from "rxjs";
import { environment } from "../../environments/environment";
import {
  AuthModuleMenu,
  AuthResponse,
  AuthTerms,
  AuthUser,
  JwtPayload,
  LoginUnitOption,
  LoginPayload,
  SaveTermsPayload
} from "./models/auth.model";

@Injectable({
  providedIn: "root"
})
export class AuthService {
  private readonly DEFAULT_LOGIN_LOGO = "logo_bj.png";
  private readonly LOGIN_LOGO_STORAGE_KEY = "loginLogo";

  private readonly API_URL = `${environment.apiUrl}/auth`;

  currentUser = signal<AuthUser | null>(this.getUser());

  isAdmin = computed(() => {
    const user = this.currentUser();
    if (!user) return false;
    return user.login === 'admin' || user.roles?.includes('ADMIN');
  });

  constructor(private http: HttpClient) { }

  login(loginData: LoginPayload): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.API_URL}/login`, loginData).pipe(
      tap(res => this.handleAuthResponse(res))
    );
  }

  getUnitsByLogin(login: string): Observable<LoginUnitOption[]> {
    return this.http.get<LoginUnitOption[]>(`${this.API_URL}/units-by-login/${encodeURIComponent(login)}`);
  }

  verify2fa(code: string): Observable<AuthResponse> {
    const token = localStorage.getItem("token");
    return this.http.post<AuthResponse>(`${this.API_URL}/verify-2fa`, { code }, {
      headers: { Authorization: `Bearer ${token}` }
    }).pipe(
      tap(res => this.handleAuthResponse(res))
    );
  }

  acceptTerms(): Observable<AuthResponse> {
    const token = localStorage.getItem("token");
    return this.http.post<AuthResponse>(`${this.API_URL}/accept-terms`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    }).pipe(
      tap(res => this.handleAuthResponse(res))
    );
  }

  handleAuthResponse(res: AuthResponse): void {
    if (res.accessToken) {
      localStorage.setItem("token", res.accessToken);
      if (!res.nextStep && res.user) {
        localStorage.setItem("user", JSON.stringify(res.user));
        this.persistLoginLogo(res.user);
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
      // Decodifica a carga útil (payload) do JWT
      const payload = JSON.parse(atob(token.split('.')[1])) as JwtPayload;
      
      // Se tiver scope (2FA ou TERMS), o login ainda não foi concluído
      if (payload.scope) {
        return false;
      }

      // Verifica expiração
      if (payload.exp && Date.now() >= payload.exp * 1000) {
        this.logout();
        return false;
      }
    } catch (e) {
      this.logout();
      return false;
    }

    return true;
  }

  getUser(): AuthUser | null {
    const user = localStorage.getItem("user");
    return user ? JSON.parse(user) as AuthUser : null;
  }

  getLoginLogo(): string {
    return localStorage.getItem(this.LOGIN_LOGO_STORAGE_KEY) || this.DEFAULT_LOGIN_LOGO;
  }

  private persistLoginLogo(user: AuthUser): void {
    const logo = user.unit?.logo || this.DEFAULT_LOGIN_LOGO;
    localStorage.setItem(this.LOGIN_LOGO_STORAGE_KEY, logo);
  }

  hasPermission(controller: string): boolean {
    const user = this.getUser();
    if (!user) return false;
    if (this.isAdmin()) return true;
    if (!user.programs) return false;
    return user.programs.some((p) => p.controller === controller);
  }

  changePassword(newPassword: string): Observable<AuthResponse> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.post<AuthResponse>(`${this.API_URL}/change-password`, { newPassword }, { headers }).pipe(
      tap(res => this.handleAuthResponse(res))
    );
  }

  updateProfile(data: Partial<AuthUser>): Observable<AuthUser> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.patch<AuthUser>(`${this.API_URL}/me`, data, { headers }).pipe(
      tap(updatedUser => {
        localStorage.setItem("user", JSON.stringify(updatedUser));
        this.currentUser.set(updatedUser);
      })
    );
  }

  getMenu(): Observable<AuthModuleMenu[]> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.get<AuthModuleMenu[]>(`${this.API_URL}/me/menu`, { headers });
  }

  getTerms(): Observable<AuthTerms> {
    return this.http.get<AuthTerms>(`${this.API_URL}/terms`);
  }

  saveTerms(data: SaveTermsPayload): Observable<AuthTerms> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.post<AuthTerms>(`${environment.apiUrl}/admin/users/terms`, data, { headers });
  }

  switchUnit(unitId: number): Observable<AuthResponse> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders().set("Authorization", `Bearer ${token}`);
    return this.http.post<AuthResponse>(`${this.API_URL}/switch-unit`, { unitId }, { headers }).pipe(
      tap(res => this.handleAuthResponse(res))
    );
  }
}
