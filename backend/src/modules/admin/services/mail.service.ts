import { Injectable, Logger, BadRequestException } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { InjectRepository } from '@nestjs/typeorm';
import { In, Repository } from 'typeorm';
import { SystemParameter } from '../entities/system-parameter.entity';
import * as nodemailer from 'nodemailer';

@Injectable()
export class MailService {
  private readonly logger = new Logger(MailService.name);

  constructor(
    private readonly configService: ConfigService,
    @InjectRepository(SystemParameter, 'security')
    private readonly parameterRepository: Repository<SystemParameter>,
  ) {}

  async sendMail(to: string, subject: string, body: string): Promise<boolean> {
    // 1. Buscar parâmetros SMTP do banco de dados de forma dinâmica
    const params = await this.parameterRepository.find({
      where: {
        parameter: In([
          'sys_smtp_host',
          'sys_smtp_port',
          'sys_smtp_user',
          'sys_smtp_pass',
          'sys_smtp_from',
          'sys_smtp_secure',
        ]),
      },
    });

    const configMap = new Map<string, string>();
    params.forEach((p) => {
      configMap.set(p.parameter.toLowerCase(), p.content || '');
    });

    const host = configMap.get('sys_smtp_host')?.trim() || '';
    const portStr = configMap.get('sys_smtp_port')?.trim();
    const port = portStr ? Number(portStr) : 587;
    const user = configMap.get('sys_smtp_user')?.trim() || '';
    const pass = configMap.get('sys_smtp_pass')?.trim() || '';
    const from = configMap.get('sys_smtp_from')?.trim() || '';
    const secureType = (configMap.get('sys_smtp_secure')?.trim() || 'NONE').toUpperCase();

    // 2. Se não houver configuração básica de SMTP cadastrada, simula em log e retorna sucesso
    if (!host) {
      this.logger.warn(`[MAIL] ⚠️ SMTP não configurado. E-mail simulado para: ${to}`);
      this.logger.log(`[MAIL] Simulador -> Assunto: ${subject}`);
      this.logger.debug(`[MAIL] Simulador -> Conteúdo: ${body}`);
      return true;
    }

    const isSecure = secureType === 'SSL';

    // 3. Criar Transporter dinâmico do Nodemailer
    const transporter = nodemailer.createTransport({
      host,
      port,
      secure: isSecure,
      auth: user ? {
        user,
        pass,
      } : undefined,
      tls: {
        rejectUnauthorized: false, // Permite certificados autoassinados comuns em SMTP locais
      },
    });

    const mailOptions = {
      from: from || user,
      to,
      subject,
      html: body,
    };

    try {
      const info = await transporter.sendMail(mailOptions);
      this.logger.log(`[MAIL] 📧 E-mail enviado com sucesso para ${to}. MessageId: ${info.messageId}`);
      return true;
    } catch (error) {
      this.logger.error(`[MAIL] ❌ Falha ao enviar e-mail para ${to}: ${error.message}`);
      throw new BadRequestException(`Erro no servidor de e-mail: ${error.message}`);
    }
  }

  async testSmtpConnection(data: {
    host: string;
    port: number;
    user: string;
    pass: string;
    from: string;
    secure: string;
    to: string;
  }) {
    const isSecure = data.secure === 'SSL';
    const transporter = nodemailer.createTransport({
      host: data.host,
      port: Number(data.port),
      secure: isSecure,
      auth: data.user ? {
        user: data.user,
        pass: data.pass,
      } : undefined,
      tls: {
        rejectUnauthorized: false,
      },
    });

    const subject = 'Teste de Conexão SMTP - RCG CRM';
    const body = `
      <h3>Conexão SMTP Estabelecida!</h3>
      <p>Este é um e-mail de teste enviado pela página de configurações do <b>RCG CRM</b>.</p>
      <p>Se você recebeu esta mensagem, suas configurações SMTP foram validadas com sucesso!</p>
      <hr>
      <p><b>Servidor:</b> ${data.host}:${data.port}</p>
      <p><b>Usuário:</b> ${data.user || 'Não informado'}</p>
      <p><b>Segurança:</b> ${data.secure}</p>
      <p><b>Data/Hora do Teste:</b> ${new Date().toLocaleString()}</p>
    `;

    const mailOptions = {
      from: data.from || data.user,
      to: data.to,
      subject,
      html: body,
    };

    try {
      const info = await transporter.sendMail(mailOptions);
      return {
        success: true,
        messageId: info.messageId,
      };
    } catch (error) {
      this.logger.error(`[MAIL-TEST] ❌ Falha na conexão de teste SMTP: ${error.message}`);
      throw new BadRequestException(`Erro na conexão de teste SMTP: ${error.message}`);
    }
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
