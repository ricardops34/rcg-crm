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
    // Se o usuário não tiver permissão para o próprio dashboard padrão, redireciona para o perfil para evitar loop infinito
    if (controller === 'DashboardVendedor') {
      router.navigate(['/profile']);
    } else {
      router.navigate(['/dashboard']);
    }
    return false;
  }

  return true;
};
