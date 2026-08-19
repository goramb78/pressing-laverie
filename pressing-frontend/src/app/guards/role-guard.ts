import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { Auth } from '../services/auth';

export const authGuard: CanActivateFn = () => {
  const authService = inject(Auth);
  const router = inject(Router);

  if (authService.isLoggedIn()) {
    return true;
  }

  router.navigate(['/connexion']);
  return false;
};

export const gestionnaireGuard: CanActivateFn = () => {
  const authService = inject(Auth);
  const router = inject(Router);

  if (authService.isLoggedIn() && authService.isGestionnaire()) {
    return true;
  }

  router.navigate(['/mes-tickets']);
  return false;
};

export const clientGuard: CanActivateFn = () => {
  const authService = inject(Auth);
  const router = inject(Router);

  if (authService.isLoggedIn() && !authService.isGestionnaire()) {
    return true;
  }

  router.navigate(['/gestionnaire']);
  return false;
};