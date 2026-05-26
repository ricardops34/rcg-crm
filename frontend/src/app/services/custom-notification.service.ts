import { Injectable, inject } from '@angular/core';
import { PoDialogService, PoNotification, PoNotificationService } from '@po-ui/ng-components';

@Injectable({
  providedIn: 'root'
})
export class CustomNotificationService extends PoNotificationService {
  private poDialog = inject(PoDialogService);

  // Inicializacao nativa da duracao padrao global para 5 segundos (5000ms)
  // Isso afeta success e information automaticamente, enquanto error e warning
  // permanecem em tela ate o fechamento manual conforme padrao do PO-UI.
  private _initDuration = (() => {
    this.setDefaultDuration(5000);
    return true;
  })();

  private translateTechnicalMessage(msg: string): string {
    if (!msg) return '';

    let translated = msg;

    // Traducoes inteligentes para ClassValidator / NestJS
    translated = translated.replace(/property\s+([a-zA-Z0-9_-]+)\s+should\s+not\s+exist/gi, "A propriedade '$1' nao e permitida ou nao deveria ser enviada");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+an\s+integer/gi, "O campo '$1' precisa ser um numero inteiro");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+number/gi, "O campo '$1' precisa ser um valor numerico valido");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+string/gi, "O campo '$1' precisa ser um texto (caracteres)");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+boolean/gi, "O campo '$1' deve ser um valor logico (verdadeiro/falso)");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+should\s+not\s+be\s+empty/gi, "O campo '$1' e obrigatorio e nao pode ser deixado em branco");
    translated = translated.replace(/([a-zA-Z0-9_-]+)\s+must\s+be\s+a\s+valid\s+email/gi, "O campo '$1' deve conter um endereco de e-mail valido");

    // Traducoes para banco de dados e erros genericos de API
    translated = translated.replace(/is\s+not\s+a\s+valid/gi, "nao e um valor aceito para este campo");
    translated = translated.replace(/Unique\s+constraint\s+violation|duplicate\s+key\s+value\s+violates\s+unique\s+constraint/gi, "Este registro ja esta cadastrado no sistema (chave duplicada)");
    translated = translated.replace(/database\s+error|query\s+error/gi, "Ocorreu um erro na consulta ou persistencia do banco de dados");
    translated = translated.replace(/Internal\s+server\s+error/gi, "Ocorreu um erro interno de processamento no servidor (Erro 500)");
    translated = translated.replace(/Forbidden/gi, "Voce nao possui permissao de acesso para realizar esta operacao");
    translated = translated.replace(/Unauthorized/gi, "Sessao expirada ou nao autorizada. Por favor, refaca o login");
    translated = translated.replace(/Bad\s+Request/gi, "A requisicao enviada ao servidor contem dados invalidos ou mal formatados");
    translated = translated.replace(/Cannot\s+GET/gi, "Nao foi possivel carregar a rota solicitada");
    translated = translated.replace(/Cannot\s+POST/gi, "Nao foi possivel enviar os dados para a rota solicitada");
    translated = translated.replace(/Cannot\s+PUT/gi, "Nao foi possivel atualizar os dados na rota solicitada");
    translated = translated.replace(/Cannot\s+DELETE/gi, "Nao foi possivel excluir o registro na rota solicitada");

    return translated;
  }

  private formatDetailsHtml(kind: 'erro' | 'aviso', messageText: string, supportText: string): string {
    const translatedMsg = this.translateTechnicalMessage(messageText);
    const supportLabel = kind === 'erro' ? 'Orientacao tecnica' : 'Orientacao de preenchimento';

    return [
      `<p><strong>Detalhes do ${kind}</strong></p>`,
      `<p>${translatedMsg}</p>`,
      `<p><strong>${supportLabel}</strong></p>`,
      `<p>${supportText}</p>`,
      `<p><strong>Suporte</strong></p>`,
      `<p>Caso necessite de ajuda, contate o suporte interno de TI do RCG CRM.</p>`
    ].join('');
  }

  private openDetails(title: string, message: string) {
    this.poDialog.alert({
      title,
      message,
      literals: { ok: 'Fechar' }
    });
  }

  override error(notification: string | PoNotification): void {
    const defaultSupport = 'Ocorreu um erro tecnico no processamento da solicitacao. Certifique-se de que a conexao com a internet esta ativa e que as informacoes foram inseridas corretamente. Caso o erro persista, entre em contato com o suporte de TI do RCG CRM.';

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

    const { supportMessage: _supportMessage, ...baseNotification } = notificationObj;
    const detailsMessage = this.formatDetailsHtml('erro', messageText, originalSupport);

    super.error({
      ...baseNotification,
      showClose: true,
      actionLabel: baseNotification.actionLabel || 'Detalhes',
      action: baseNotification.action || (() => this.openDetails('Detalhes do erro', detailsMessage))
    });
  }

  override warning(notification: string | PoNotification): void {
    const defaultSupport = 'Verifique as orientacoes de preenchimento na tela. Certifique-se de que todos os dados obrigatorios e as validacoes foram satisfeitos antes de submeter o formulario.';

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

    const { supportMessage: _supportMessage, ...baseNotification } = notificationObj;
    const detailsMessage = this.formatDetailsHtml('aviso', messageText, originalSupport);

    super.warning({
      ...baseNotification,
      showClose: true,
      actionLabel: baseNotification.actionLabel || 'Detalhes',
      action: baseNotification.action || (() => this.openDetails('Detalhes do aviso', detailsMessage))
    });
  }
}
