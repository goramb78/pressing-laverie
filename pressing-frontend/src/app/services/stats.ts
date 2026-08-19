import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface DailyStats {
  tickets_created_today: number;
  tickets_completed_today: number;
  revenue_today: number;
}

export interface TicketsPerMonth {
  month: string;
  total: number;
}

export interface RevenuePerService {
  service: string;
  month: string;
  total: string;
}

@Injectable({
  providedIn: 'root'
})
export class Stats {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getDaily(): Observable<DailyStats> {
    return this.http.get<DailyStats>(`${this.apiUrl}/stats/daily`);
  }

  getTicketsPerMonth(): Observable<TicketsPerMonth[]> {
    return this.http.get<TicketsPerMonth[]>(`${this.apiUrl}/stats/tickets-per-month`);
  }

  getRevenuePerService(): Observable<RevenuePerService[]> {
    return this.http.get<RevenuePerService[]>(`${this.apiUrl}/stats/revenue-per-service`);
  }
}