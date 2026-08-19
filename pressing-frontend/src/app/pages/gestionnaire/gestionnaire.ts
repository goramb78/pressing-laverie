import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, Router } from '@angular/router';
import { TicketService, Ticket } from '../../services/ticket';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-gestionnaire',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './gestionnaire.html',
  styleUrl: './gestionnaire.css'
})
export class GestionnaireComponent implements OnInit {
  tickets = signal<Ticket[]>([]);
  loading = signal(true);
  montants: Record<number, number> = {};

  constructor(
    private ticketService: TicketService,
    private authService: Auth,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.chargerTickets();
  }

  chargerTickets(): void {
    this.loading.set(true);
    this.ticketService.getTickets().subscribe({
      next: (data) => {
        this.tickets.set(data);
        this.loading.set(false);
      },
      error: () => this.loading.set(false)
    });
  }

  changerStatut(ticket: Ticket, statut: string): void {
    this.ticketService.updateStatus(ticket.id, statut).subscribe({
      next: (updated) => {
        this.tickets.update(list => list.map(t => t.id === updated.id ? updated : t));
      },
      error: (err) => alert(err.error?.message || 'Erreur lors du changement de statut.')
    });
  }

  payer(ticket: Ticket): void {
    const montant = this.montants[ticket.id];
    if (!montant || montant <= 0) {
      alert('Entrez un montant valide.');
      return;
    }

    this.ticketService.registerPayment(ticket.id, montant).subscribe({
      next: () => this.chargerTickets(),
      error: (err) => alert(err.error?.message || 'Erreur lors du paiement.')
    });
  }

  annuler(ticket: Ticket): void {
    if (!confirm('Annuler cette commande ?')) return;

    this.ticketService.cancelTicket(ticket.id).subscribe({
      next: () => this.tickets.update(list => list.filter(t => t.id !== ticket.id)),
      error: (err) => alert(err.error?.message || 'Erreur lors de l\'annulation.')
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