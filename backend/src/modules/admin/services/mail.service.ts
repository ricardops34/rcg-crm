import { Injectable, Logger } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';

@Injectable()
export class MailService {
  private readonly logger = new Logger(MailService.name);

  constructor(private configService: ConfigService) {}

  async sendMail(to: string, subject: string, body: string) {
    // Placeholder: No futuro integrar com Nodemailer ou SES
    this.logger.log(`[MAIL] 📧 Enviando e-mail para: ${to}`);
    this.logger.log(`[MAIL] Assunto: ${subject}`);
    this.logger.debug(`[MAIL] Conteúdo: ${body}`);
    
    // Simulação de sucesso
    return true;
  }

  async send2FAToken(email: string, token: string) {
    const subject = 'Seu código de acesso - RCG CRM';
    const body = `Olá, seu código de verificação é: <b>${token}</b>. Ele expira em 10 minutos.`;
    return this.sendMail(email, subject, body);
  }

  async sendPasswordReset(email: string, token: string) {
    const subject = 'Recuperação de Senha - RCG CRM';
    const resetUrl = `${this.configService.get('FRONTEND_URL')}/reset-password?token=${token}`;
    const body = `Olá, você solicitou a recuperação de senha. Clique no link abaixo para redefinir:\n\n${resetUrl}`;
    return this.sendMail(email, subject, body);
  }
}
