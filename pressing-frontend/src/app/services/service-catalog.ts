import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ServiceItem } from './ticket';

@Injectable({
  providedIn: 'root'
})
export class ServiceCatalog {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getServices(libelle?: string): Observable<ServiceItem[]> {
    const url = libelle
      ? `${this.apiUrl}/services?libelle=${encodeURIComponent(libelle)}`
      : `${this.apiUrl}/services`;
    return this.http.get<ServiceItem[]>(url);
  }

  createService(data: { libelle: string; description?: string; prix_unitaire: number; actif?: boolean }): Observable<ServiceItem> {
    return this.http.post<ServiceItem>(`${this.apiUrl}/services`, data);
  }

  updateService(id: number, data: Partial<{ libelle: string; description: string; prix_unitaire: number; actif: boolean }>): Observable<ServiceItem> {
    return this.http.put<ServiceItem>(`${this.apiUrl}/services/${id}`, data);
  }

  archiveService(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/services/${id}`);
  }
}