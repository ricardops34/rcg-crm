import { Injectable, signal } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class ParameterService {
  private readonly API_URL = `${environment.apiUrl}/admin/parameters`;

  // Cache reativo em Signals para os parâmetros principais de sistema
  readonly queryLimit = signal<number>(20);
  readonly systemName = signal<string>("CRM");

  constructor(private http: HttpClient) {}

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

  save(data: any): Observable<any> {
    if (data.id) {
      const { id, ...rest } = data;
      return this.http.put<any>(`${this.API_URL}/${id}`, rest, { headers: this.getHeaders() });
    }

    return this.http.post<any>(this.API_URL, data, { headers: this.getHeaders() });
  }

  delete(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/${id}`, { headers: this.getHeaders() });
  }

  testSmtp(data: any): Observable<any> {
    return this.http.post<any>(`${this.API_URL}/test-smtp`, data, { headers: this.getHeaders() });
  }

  splitByUnit(id: number): Observable<any> {
    return this.http.post<any>(`${this.API_URL}/${id}/split-by-unit`, {}, { headers: this.getHeaders() });
  }

  /**
   * Inicializa o cache reativo de parâmetros de sistema a partir do banco de dados
   */
  loadDefaultParameters() {
    const userJson = localStorage.getItem("user");
    const user = userJson ? JSON.parse(userJson) : null;
    const unitId = user?.unit?.id || null;

    this.findAll().subscribe({
      next: (res: any[]) => {
        const items = res || [];
        
        // Localiza dando preferência para a filial ativa e faz fallback para o global (NULL)
        const getParam = (name: string) => {
          const nameLower = name.toLowerCase();
          const unitParam = items.find(i => i.parameter?.toLowerCase() === nameLower && i.systemUnitId === unitId);
          if (unitParam) return unitParam;
          return items.find(i => i.parameter?.toLowerCase() === nameLower && i.systemUnitId === null);
        };

        const limitParam = getParam('sys_query_limit');
        const nameParam = getParam('sys_system_name');

        if (limitParam && limitParam.content) {
          const val = Number(limitParam.content);
          if (!isNaN(val)) {
            this.queryLimit.set(val);
          }
        }

        if (nameParam && nameParam.content) {
          this.systemName.set(nameParam.content);
        }
      },
      error: (err: any) => console.warn("Erro ao inicializar cache de parâmetros:", err)
    });
  }
}
