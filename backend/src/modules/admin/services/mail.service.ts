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

  async sendMail(to: string, subject: string, body: string, systemUnitId?: number): Promise<boolean> {
    // 1. Buscar parâmetros SMTP do banco de dados (da unidade ou globais)
    const query = this.parameterRepository
      .createQueryBuilder('parameter')
      .where('parameter.parameter IN (:...names)', {
        names: [
          'sys_smtp_host',
          'sys_smtp_port',
          'sys_smtp_user',
          'sys_smtp_pass',
          'sys_smtp_from',
          'sys_smtp_secure',
        ],
      });

    if (systemUnitId) {
      query.andWhere(
        '(parameter.system_unit_id = :systemUnitId OR parameter.system_unit_id IS NULL)',
        { systemUnitId },
      );
    } else {
      query.andWhere('parameter.system_unit_id IS NULL');
    }

    const params = await query.getMany();

    const configMap = new Map<string, string>();
    
    // Alimenta primeiro com os globais
    params
      .filter((p) => p.systemUnitId === null)
      .forEach((p) => configMap.set(p.parameter.toLowerCase(), p.content || ''));

    // Sobrepõe com os da unidade (se houver correspondência específica)
    if (systemUnitId) {
      params
        .filter((p) => p.systemUnitId === systemUnitId)
        .forEach((p) => configMap.set(p.parameter.toLowerCase(), p.content || ''));
    }

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

  async sendVendedorTempPassword(
    email: string,
    nome: string,
    login: string,
    tempPassword: string,
    systemUnitId?: number,
  ) {
    const frontUrl = this.configService.get('FRONTEND_URL') || 'http://localhost:4200';
    const subject = 'Senha Temporária de Acesso - RCG CRM';
    const body = `
      <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #4097CC;">RCG CRM — Senha de Acesso</h2>
        <p>Olá, <strong>${nome}</strong>!</p>
        <p>Uma senha temporária foi gerada para o seu acesso ao sistema.</p>

        <div style="background: #f4f8fb; border-left: 4px solid #4097CC; padding: 16px 20px; border-radius: 4px; margin: 20px 0;">
          <p style="margin: 4px 0;"><strong>Login:</strong> ${login}</p>
          <p style="margin: 4px 0;"><strong>Senha temporária:</strong>
            <span style="font-size: 20px; font-weight: bold; color: #17242A; letter-spacing: 2px;">${tempPassword}</span>
          </p>
        </div>

        <p><strong>Como acessar:</strong></p>
        <ol style="line-height: 2;">
          <li>Acesse: <a href="${frontUrl}" style="color: #4097CC;">${frontUrl}</a></li>
          <li>Informe o login e a senha temporária acima</li>
          <li>O sistema solicitará que você defina uma nova senha pessoal</li>
          <li>Guarde sua nova senha em local seguro</li>
        </ol>

        <p style="background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 4px; color: #856404;">
          ⚠️ <strong>Atenção:</strong> Esta senha é temporária e expira após o primeiro acesso.
          Você deverá criar uma nova senha imediatamente após entrar no sistema.
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 24px 0;"/>
        <p style="color: #999; font-size: 12px;">
          Este é um e-mail automático gerado pelo RCG CRM. Não responda a esta mensagem.
        </p>
      </div>
    `;
    return this.sendMail(email, subject, body, systemUnitId);
  }

  async send2FAToken(email: string, token: string, systemUnitId?: number) {
    const subject = 'Seu código de acesso - RCG CRM';
    const body = `Olá, seu código de verificação é: <b>${token}</b>. Ele expira em 10 minutos.`;
    return this.sendMail(email, subject, body, systemUnitId);
  }

  async sendPasswordReset(email: string, token: string) {
    const subject = 'Recuperação de Senha - RCG CRM';
    const resetUrl = `${this.configService.get('FRONTEND_URL')}/reset-password?token=${token}`;
    const body = `Olá, você solicitou a recuperação de senha. Clique no link abaixo para redefinir:\n\n${resetUrl}`;
    return this.sendMail(email, subject, body);
  }
}
