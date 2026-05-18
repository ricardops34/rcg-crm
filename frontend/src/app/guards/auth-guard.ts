import { inject } from '@angular/core';
import { Router, CanActivateFn } from '@angular/router';
import { AuthService } from '../services/auth';

export const authGuard: CanActivateFn = (route, state) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  if (!authService.isAuthenticated()) {
    router.navigate(['/login']);
    return false;
  }

  // Verificar permissão se o controller estiver definido na rota
  const controller = route.data?.['controller'];
  if (controller && !authService.hasPermission(controller)) {
    router.navigate(['/dashboard']); // Ou uma página de erro/403
    return false;
  }

  return true;
};
