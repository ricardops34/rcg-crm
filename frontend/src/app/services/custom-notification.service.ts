import { Injectable } from '@angular/core';
import { PoNotificationService, PoNotification } from '@po-ui/ng-components';

@Injectable({
  providedIn: 'root'
})
export class CustomNotificationService extends PoNotificationService {
  override success(notification: string | PoNotification): void {
    if (typeof notification === 'string') {
      super.success({ message: notification, duration: 5000 });
    } else {
      super.success({ duration: 5000, ...notification });
    }
  }

  override information(notification: string | PoNotification): void {
    if (typeof notification === 'string') {
      super.information({ message: notification, duration: 5000 });
    } else {
      super.information({ duration: 5000, ...notification });
    }
  }

  override error(notification: string | PoNotification): void {
    const defaultSupport = 'Ocorreu um erro técnico no processamento da solicitação. Certifique-se de que a conexão com a internet está ativa e que as informações foram inseridas corretamente. Caso o erro persista, entre em contato com o suporte de TI do RCG CRM.';
    if (typeof notification === 'string') {
      super.error({ 
        message: notification, 
        supportMessage: defaultSupport 
      });
    } else {
      super.error({ 
        supportMessage: notification.supportMessage || defaultSupport,
        ...notification 
      });
    }
  }

  override warning(notification: string | PoNotification): void {
    const defaultSupport = 'Verifique as orientações de preenchimento na tela. Certifique-se de que todos os dados obrigatórios e as validações foram satisfeitos antes de submeter o formulário.';
    if (typeof notification === 'string') {
      super.warning({ 
        message: notification, 
        supportMessage: defaultSupport 
      });
    } else {
      super.warning({ 
        supportMessage: notification.supportMessage || defaultSupport,
        ...notification 
      });
    }
  }
}
