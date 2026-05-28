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

  private normalizePayload(user: any): any {
    const payload = {
      name: user.name,
      login: user.login,
      password: user.password || undefined,
      email: user.email,
      frontpageId: user.frontpageId ?? undefined,
      systemUnitId: user.systemUnitId ? Number(user.systemUnitId) : undefined,
      active: user.active ?? "Y",
      twoFactorEnabled: user.twoFactorEnabled ?? undefined,
      twoFactorType: user.twoFactorType ?? undefined,
      twoFactorSecret: user.twoFactorSecret ?? undefined,
      avatar: user.avatar ?? undefined,
      groups: Array.isArray(user.groups) ? user.groups.map((id: any) => Number(id)) : [],
      units: Array.isArray(user.units) ? user.units.map((id: any) => Number(id)) : [],
      programs: Array.isArray(user.programs) ? user.programs.map((id: any) => Number(id)) : []
    };

    return Object.fromEntries(
      Object.entries(payload).filter(([, value]) => value !== undefined)
    );
  }

  save(user: any): Observable<any> {
    const payload = this.normalizePayload(user);

    if (user.id) {
      return this.http.put<any>(`${this.API_URL}/${user.id}`, payload, { headers: this.getHeaders() });
    } else {
      return this.http.post<any>(this.API_URL, payload, { headers: this.getHeaders() });
    }
  }

  delete(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }
}
