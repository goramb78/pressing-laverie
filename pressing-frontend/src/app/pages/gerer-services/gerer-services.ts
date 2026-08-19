import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { ServiceCatalog } from '../../services/service-catalog';
import { ServiceItem } from '../../services/ticket';

@Component({
  selector: 'app-gerer-services',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './gerer-services.html',
  styleUrl: './gerer-services.css'
})
export class GererServices implements OnInit {
  services = signal<ServiceItem[]>([]);
  loading = signal(true);

  nouveauLibelle = '';
  nouveauPrix: number | null = null;
  nouvelleDescription = '';

  constructor(private catalogService: ServiceCatalog) {}

  ngOnInit(): void {
    this.charger();
  }

  charger(): void {
    this.loading.set(true);
    this.catalogService.getServices().subscribe({
      next: (data) => {
        this.services.set(data);
        this.loading.set(false);
      },
      error: () => this.loading.set(false)
    });
  }

  ajouter(): void {
    if (!this.nouveauLibelle || !this.nouveauPrix) {
      alert('Renseignez au moins le libellé et le prix.');
      return;
    }

    this.catalogService.createService({
      libelle: this.nouveauLibelle,
      description: this.nouvelleDescription || undefined,
      prix_unitaire: this.nouveauPrix,
      actif: true,
    }).subscribe({
      next: () => {
        this.nouveauLibelle = '';
        this.nouveauPrix = null;
        this.nouvelleDescription = '';
        this.charger();
      },
      error: () => alert('Erreur lors de la création.')
    });
  }

  archiver(service: ServiceItem): void {
    if (!confirm(`Archiver "${service.libelle}" ?`)) return;

    this.catalogService.archiveService(service.id).subscribe({
      next: () => this.charger(),
      error: () => alert('Erreur lors de l\'archivage.')
    });
  }
}