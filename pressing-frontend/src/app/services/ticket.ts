import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface ServiceItem {
  id: number;
  libelle: string;
  description: string | null;
  prix_unitaire: string;
  actif: boolean;
}

export interface TicketItem {
  id: number;
  service_id: number;
  quantite: number;
  prix_unitaire: string;
  sous_total: string;
  service: ServiceItem;
}

export interface Payment {
  id: number;
  montant: string;
  date_paiement: string;
}

export interface Ticket {
  id: number;
  user_id: number;
  statut: 'recu' | 'en_traitement' | 'pret' | 'recupere';
  montant_total: string;
  date_recu: string;
  date_en_traitement: string | null;
  date_pret: string | null;
  date_recupere: string | null;
  items: TicketItem[];
  payment: Payment | null;
  user?: { id: number; name: string; email: string };
}

@Injectable({
  providedIn: 'root'
})
export class TicketService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getTickets(): Observable<Ticket[]> {
    return this.http.get<Ticket[]>(`${this.apiUrl}/tickets`);
  }

  getTicket(id: number): Observable<Ticket> {
    return this.http.get<Ticket>(`${this.apiUrl}/tickets/${id}`);
  }

  createTicket(items: { service_id: number; quantite: number }[]): Observable<Ticket> {
    return this.http.post<Ticket>(`${this.apiUrl}/tickets`, { items });
  }

  updateStatus(id: number, statut: string): Observable<Ticket> {
    return this.http.patch<Ticket>(`${this.apiUrl}/tickets/${id}/status`, { statut });
  }

  cancelTicket(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/tickets/${id}`);
  }

  registerPayment(id: number, montant: number): Observable<Payment> {
    return this.http.post<Payment>(`${this.apiUrl}/tickets/${id}/payment`, { montant });
  }
}