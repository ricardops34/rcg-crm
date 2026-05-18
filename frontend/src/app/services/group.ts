import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Observable } from "rxjs";
import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class GroupService {

  private readonly API_URL = `${environment.apiUrl}/admin/groups`;

  constructor(private http: HttpClient) { }

  private getHeaders() {
    const token = localStorage.getItem("token");
    return new HttpHeaders().set("Authorization", `Bearer ${token}`);
  }

  findAll(): Observable<any> {
    return this.http.get<any>(this.API_URL, { headers: this.getHeaders() });
  }
}
