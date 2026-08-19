import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, Router } from '@angular/router';
import { TicketService, Ticket } from '../../services/ticket';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-mes-tickets',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './mes-tickets.html',
  styleUrl: './mes-tickets.css'
})
export class MesTickets implements OnInit {
  tickets = signal<Ticket[]>([]);
  loading = signal(true);

  constructor(
    private ticketService: TicketService,
    private authService: Auth,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.ticketService.getTickets().subscribe({
      next: (data) => {
        this.tickets.set(data);
        this.loading.set(false);
      },
      error: () => {
        this.loading.set(false);
      }
    });
  }

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }

  statutLabel(statut: string): string {
    const labels: Record<string, string> = {
      recu: 'Reçu',
      en_traitement: 'En traitement',
      pret: 'Prêt',
      recupere: 'Récupéré',
    };
    return labels[statut] || statut;
  }
}