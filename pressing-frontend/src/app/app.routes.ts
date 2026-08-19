import { Routes } from '@angular/router';
import { Login } from './pages/login/login';
import { Register } from './pages/register/register';
import { MesTickets } from './pages/mes-tickets/mes-tickets';
import { NouvelleCommande } from './pages/nouvelle-commande/nouvelle-commande';
import { GestionnaireComponent } from './pages/gestionnaire/gestionnaire';
import { GererServices } from './pages/gerer-services/gerer-services';
import { Statistiques } from './pages/statistiques/statistiques';
import { clientGuard, gestionnaireGuard } from './guards/role-guard';

export const routes: Routes = [
  { path: 'connexion', component: Login },
  { path: 'inscription', component: Register },
  { path: 'mes-tickets', component: MesTickets, canActivate: [clientGuard] },
  { path: 'nouvelle-commande', component: NouvelleCommande, canActivate: [clientGuard] },
  { path: 'gestionnaire', component: GestionnaireComponent, canActivate: [gestionnaireGuard] },
  { path: 'gestionnaire/services', component: GererServices, canActivate: [gestionnaireGuard] },
  { path: 'gestionnaire/statistiques', component: Statistiques, canActivate: [gestionnaireGuard] },
  { path: '', redirectTo: '/connexion', pathMatch: 'full' },
];