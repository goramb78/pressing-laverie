import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { ServiceCatalog } from '../../services/service-catalog';
import { TicketService } from '../../services/ticket';
import { ServiceItem } from '../../services/ticket';

interface Panier {
  service: ServiceItem;
  quantite: number;
}

@Component({
  selector: 'app-nouvelle-commande',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './nouvelle-commande.html',
  styleUrl: './nouvelle-commande.css'
})
export class NouvelleCommande implements OnInit {
  services = signal<ServiceItem[]>([]);
  panier = signal<Panier[]>([]);
  loading = signal(true);
  submitting = signal(false);
  errorMessage = signal('');

  constructor(
    private catalogService: ServiceCatalog,
    private ticketService: TicketService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.catalogService.getServices().subscribe({
      next: (data) => {
        this.services.set(data);
        this.loading.set(false);
      },
      error: () => this.loading.set(false)
    });
  }

  quantitePour(service: ServiceItem): number {
    const item = this.panier().find(p => p.service.id === service.id);
    return item ? item.quantite : 0;
  }

  changerQuantite(service: ServiceItem, quantite: number): void {
    const current = this.panier().filter(p => p.service.id !== service.id);
    if (quantite > 0) {
      current.push({ service, quantite });
    }
    this.panier.set(current);
  }

  total(): number {
    return this.panier().reduce((sum, p) => sum + (parseFloat(p.service.prix_unitaire) * p.quantite), 0);
  }

  deposerCommande(): void {
    if (this.panier().length === 0) {
      this.errorMessage.set('Sélectionnez au moins un service.');
      return;
    }

    this.submitting.set(true);
    this.errorMessage.set('');

    const items = this.panier().map(p => ({ service_id: p.service.id, quantite: p.quantite }));

    this.ticketService.createTicket(items).subscribe({
      next: () => {
        this.submitting.set(false);
        this.router.navigate(['/mes-tickets']);
      },
      error: () => {
        this.submitting.set(false);
        this.errorMessage.set('Une erreur est survenue.');
      }
    });
  }
}