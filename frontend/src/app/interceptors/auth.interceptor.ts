import { HttpInterceptorFn, HttpErrorResponse } from '@angular/common/http';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { catchError, throwError } from 'rxjs';
import { AuthService } from '../services/auth';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  return next(req).pipe(
    catchError((error: any) => {
      // Se a API retornar 401 Unauthorized, o token expirou ou é inválido.
      // Deslogamos o usuário e o redirecionamos de volta para a tela de login.
      if (error instanceof HttpErrorResponse && error.status === 401) {
        authService.logout();
        router.navigate(['/login'], { queryParams: { error: 'session' } });
      }
      return throwError(() => error);
    })
  );
};
