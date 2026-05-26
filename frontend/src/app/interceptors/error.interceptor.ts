import { HttpErrorResponse, HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { PoNotificationService } from '@po-ui/ng-components';
import { catchError, throwError } from 'rxjs';

const getErrorMessage = (error: HttpErrorResponse): string => {
  switch (error.status) {
    case 0:
      return 'Erro de conexão com o servidor.';
    case 400:
      return error.error?.message || 'Requisição inválida.';
    case 401:
      return 'Sessão expirada. Faça login novamente.';
    case 403:
      return 'Você não tem permissão para esta ação.';
    case 404:
      return 'Recurso não encontrado.';
    case 500:
      return error.error?.message || 'Erro interno do servidor.';
    default:
      return error.error?.message || `Erro HTTP ${error.status}.`;
  }
};

export const errorInterceptor: HttpInterceptorFn = (req, next) => {
  const notification = inject(PoNotificationService);

  return next(req).pipe(
    catchError((error: unknown) => {
      if (error instanceof HttpErrorResponse) {
        // O authInterceptor já trata logout/redirecionamento para 401.
        if (error.status !== 401) {
          notification.error(getErrorMessage(error));
        }
      } else {
        notification.error('Erro desconhecido ao processar a solicitação.');
      }

      return throwError(() => error);
    })
  );
};
