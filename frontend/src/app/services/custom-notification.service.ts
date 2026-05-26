import { Injectable } from '@angular/core';
import { PoNotificationService, PoNotification } from '@po-ui/ng-components';

@Injectable({
  providedIn: 'root'
})
export class CustomNotificationService extends PoNotificationService {
  // Inicialização nativa da duração padrão global para 5 segundos (5000ms)
  // Isso afeta success e information automaticamente, enquanto error e warning
  // permanecem em tela até o fechamento manual conforme padrão do PO-UI.
  private _initDuration = (() => {
    this.setDefaultDuration(5000);
    return true;
  })();

  private translateTechnicalMessage(msg: string): string {
    if (!msg) return '';

    let translated = msg;

    // Traduções inteligentes para ClassValidator / NestJS
    translated = translated.replace(/property\s+([a-zA-Z0-9_-]+)\s+should\s+not\s+exist/gi, "A propriedade '$1' não é permitida ou não deveria ser enviada");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+an\s+integer/gi, "O campo '$1' precisa ser um número inteiro");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+number/gi, "O campo '$1' precisa ser um valor numérico válido");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+string/gi, "O campo '$1' precisa ser um texto (caracteres)");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+boolean/gi, "O campo '$1' deve ser um valor lógico (verdadeiro/falso)");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+should\s+not\s+be\s+empty/gi, "O campo '$1' é obrigatório e não pode ser deixado em branco");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+valid\s+email/gi, "O campo '$1' deve conter um endereço de e-mail válido");
    
    // Traduções para banco de dados e erros genéricos de API
    translated = translated.replace(/is\s+not\s+a\s+valid/gi, "não é um valor aceito para este campo");
    translated = translated.replace(/Unique\s+constraint\s+violation|duplicate\s+key\s+value\s+violates\s+unique\s+constraint/gi, "Este registro já está cadastrado no sistema (chave duplicada)");
    translated = translated.replace(/database\s+error|query\s+error/gi, "Ocorreu um erro na consulta ou persistência do banco de dados");
    translated = translated.replace(/Internal\s+server\s+error/gi, "Ocorreu um erro interno de processamento no servidor (Erro 500)");
    translated = translated.replace(/Forbidden/gi, "Você não possui permissão de acesso para realizar esta operação");
    translated = translated.replace(/Unauthorized/gi, "Sessão expirada ou não autorizada. Por favor, refaça o login");
    translated = translated.replace(/Bad\s+Request/gi, "A requisição enviada ao servidor contém dados inválidos ou mal formatados");
    translated = translated.replace(/Cannot\s+GET/gi, "Não foi possível carregar a rota solicitada");
    translated = translated.replace(/Cannot\s+POST/gi, "Não foi possível enviar os dados para a rota solicitada");
    translated = translated.replace(/Cannot\s+PUT/gi, "Não foi possível atualizar os dados na rota solicitada");
    translated = translated.replace(/Cannot\s+DELETE/gi, "Não foi possível excluir o registro na rota solicitada");

    return translated;
  }

  override error(notification: string | PoNotification): void {
    const defaultSupport = 'Ocorreu um erro técnico no processamento da solicitação. Certifique-se de que a conexão com a internet está ativa e que as informações foram inseridas corretamente. Caso o erro persista, entre em contato com o suporte de TI do RCG CRM.';
    
    let messageText = '';
    let originalSupport = '';
    let notificationObj: PoNotification;

    if (typeof notification === 'string') {
      messageText = notification;
      originalSupport = defaultSupport;
      notificationObj = { message: notification };
    } else {
      messageText = notification.message || '';
      originalSupport = notification.supportMessage || defaultSupport;
      notificationObj = { ...notification };
    }

    // Tradução e enriquecimento estruturado do supportMessage
    const translatedMsg = this.translateTechnicalMessage(messageText);
    const structuredSupport = `📋 DETALHES DO PROBLEMA:\n👉 ${translatedMsg}\n\n🛠️ ORIENTAÇÃO TÉCNICA:\n💡 ${originalSupport}\n\n📞 SUPORTE:\nCaso necessite de ajuda, contate o suporte interno de TI do RCG CRM.`;

    super.error({
      ...notificationObj,
      supportMessage: structuredSupport
    });
  }

  override warning(notification: string | PoNotification): void {
    const defaultSupport = 'Verifique as orientações de preenchimento na tela. Certifique-se de que todos os dados obrigatórios e as validações foram satisfeitos antes de submeter o formulário.';
    
    let messageText = '';
    let originalSupport = '';
    let notificationObj: PoNotification;

    if (typeof notification === 'string') {
      messageText = notification;
      originalSupport = defaultSupport;
      notificationObj = { message: notification };
    } else {
      messageText = notification.message || '';
      originalSupport = notification.supportMessage || defaultSupport;
      notificationObj = { ...notification };
    }

    // Tradução e enriquecimento estruturado do supportMessage
    const translatedMsg = this.translateTechnicalMessage(messageText);
    const structuredSupport = `⚠️ DETALHES DO AVISO:\n👉 ${translatedMsg}\n\n🛠️ ORIENTAÇÃO DE PREENCHIMENTO:\n💡 ${originalSupport}\n\n📌 Lembrete: Certifique-se de que todos os campos obrigatórios estão preenchidos antes de continuar.`;

    super.warning({
      ...notificationObj,
      supportMessage: structuredSupport
    });
  }
}
