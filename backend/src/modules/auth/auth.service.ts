import { Injectable, UnauthorizedException, Inject } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { CACHE_MANAGER } from '@nestjs/cache-manager';
import type { Cache } from 'cache-manager';
import * as bcrypt from 'bcrypt';
import * as crypto from 'crypto';
import { v4 as uuidv4 } from 'uuid';
import { SystemUser } from '../admin/entities/system-user.entity';
import { SystemUserUnit } from '../admin/entities/system-user-unit.entity';
import { PermissionsService } from '../admin/permissions.service';
import { MailService } from '../admin/services/mail.service';
import { Vendedor } from '../commercial/entities/vendedor.entity';
import { Supervisor } from '../commercial/entities/supervisor.entity';
import { SupervisorVendedor } from '../commercial/entities/supervisor-vendedor.entity';

export interface AuthUser {
  id: number;
  login: string;
  email?: string;
  twoFactorEnabled?: string;
  twoFactorVerified?: boolean;
  acceptedTermPolicy?: string;
  systemUnitId?: number;
}

@Injectable()
export class AuthService {
  constructor(
    @InjectRepository(SystemUser, 'security')
    private userRepository: Repository<SystemUser>,
    @InjectRepository(Vendedor)
    private vendedorRepository: Repository<Vendedor>,
    @InjectRepository(Supervisor)
    private supervisorRepository: Repository<Supervisor>,
    @InjectRepository(SupervisorVendedor)
    private supervisorVendedorRepository: Repository<SupervisorVendedor>,
    private jwtService: JwtService,
    private permissionsService: PermissionsService,
    private mailService: MailService,
    @Inject(CACHE_MANAGER) private cacheManager: Cache,
  ) {}

  async validateUser(
    login: string,
    pass: string,
  ): Promise<Partial<SystemUser> | null> {
    const normalizedLogin = login.toLowerCase().trim();
    console.log(`[AUTH] 🔐 Tentativa de login: "${normalizedLogin}"`);
    
    // Busca insensível a maiúsculas/minúsculas e aceita 'Y' ou null (se for o caso)
    const user = await this.userRepository
      .createQueryBuilder('user')
      .where('LOWER(user.login) = :login', { login: normalizedLogin })
      .andWhere('user.active = :active', { active: 'Y' })
      .getOne();

    if (!user) {
      console.warn(`[AUTH] ❌ Usuário não encontrado ou inativo no banco: "${normalizedLogin}"`);
      // Log extra para depurar se o usuário existe mas está inativo
      const anyUser = await this.userRepository.findOne({ where: { login: normalizedLogin } });
      if (anyUser) console.log(`[AUTH] ℹ️ Usuário existe mas status é: "${anyUser.active}"`);
      return null;
    }

    let isMatch = false;
    const dbHash = user.password;
    console.log(`[AUTH] ℹ️ Hash encontrado no banco: "${dbHash.substring(0, 10)}..."`);
    
    // 1. MD5
    if (dbHash.length === 32) {
      const passMd5 = crypto.createHash('md5').update(pass).digest('hex');
      if (passMd5 === dbHash) {
        isMatch = true;
        console.log(`[AUTH] ✅ MD5 OK. Migrando...`);
        user.password = await bcrypt.hash(pass, 10);
        await this.userRepository.save(user);
      }
    } 
    // 2. Bcrypt ($2y$, $2a$, $2b$)
    else if (dbHash.startsWith('$2')) {
      const compatibleHash = dbHash.replace(/^\$2y\$/, '$2a$');
      try {
        isMatch = await bcrypt.compare(pass, compatibleHash);
      } catch (e) {
        console.error(`[AUTH] ❌ Erro de comparação: ${e.message}`);
      }
    }

    if (isMatch) {
      console.log(`[AUTH] ✅ Login autorizado: ${user.login}`);
      const { password, ...result } = user;
      return result as Partial<SystemUser>;
    }

    console.warn(`[AUTH] ❌ Senha não confere para: ${user.login}`);
    return null;
  }

  async login(user: AuthUser) {
    // Verificar se precisa de 2FA ou Aceite de Termos antes do login final
    if (user.twoFactorEnabled === 'Y' && !user.twoFactorVerified) {
      // Gerar código de 6 dígitos
      const code = Math.floor(100000 + Math.random() * 900000).toString();
      
      // Salvar no cache por 10 minutos
      await this.cacheManager.set(`2fa:${user.id}`, code, 600000);
      
      // Enviar e-mail (se o usuário tiver e-mail)
      if (user.email) {
        await this.mailService.send2FAToken(user.email, code);
      } else {
        console.warn(`[AUTH] ⚠️ Usuário ${user.id} sem e-mail cadastrado para 2FA.`);
      }

      const payload = { sub: user.id, username: user.login, scope: '2fa' };
      return {
        nextStep: '2FA',
        accessToken: this.jwtService.sign(payload, { expiresIn: '5m' }),
      };
    }

    if (user.acceptedTermPolicy !== 'Y') {
      const payload = { sub: user.id, username: user.login, scope: 'TERMS' };
      return {
        nextStep: 'TERMS',
        accessToken: this.jwtService.sign(payload, { expiresIn: '5m' }),
      };
    }

    // Gerar ID de sessão única
    const sessionId = uuidv4();
    console.log(`[AUTH] 🔑 Gerando nova sessão para ${user.id}: ${sessionId}`);
    
    // TTL de 24 horas para evitar expiração imediata em alguns stores
    await this.cacheManager.set(`session:${user.id}`, sessionId, 86400000); 

    // Atualizar no banco para persistência
    await this.userRepository.update(user.id, { currentSessionId: sessionId });

    const profile = await this.getProfile(user.id);
    const activeUnitId = profile.unit?.id || user.systemUnitId;

    const payload = {
      username: user.login,
      sub: user.id,
      sid: sessionId,
      unitId: activeUnitId,
    };

    return {
      accessToken: this.jwtService.sign(payload),
      user: profile,
    };
  }

  async getProfile(userId: number) {
    const user = await this.userRepository.findOne({
      where: { id: userId },
      relations: ['systemUnit', 'frontpage'],
    });

    if (!user) throw new UnauthorizedException('Usuário não encontrado');

    // 1. Verificar se é Vendedor
    const vendedor = await this.vendedorRepository.findOne({
      where: { systemUsersId: userId },
    });

    // 2. Verificar se é Supervisor
    const supervisor = await this.supervisorRepository.findOne({
      where: { systemUsersId: userId },
    });

    const managedVendedorIds: number[] = [];
    if (supervisor) {
      const relationships = await this.supervisorVendedorRepository.find({
        where: { supervisorId: supervisor.id },
      });
      managedVendedorIds.push(...relationships.map((r) => r.vendedorId));
    }

    const programs = await this.permissionsService.getUserPrograms(userId);
    const userRoles = await this.permissionsService.getUserRoles(userId);

    const isGerente = userRoles.includes('GERENTE') || userRoles.includes('ADMIN');

    // Buscar todas as unidades permitidas para o usuário na tabela associativa
    const userUnits = await this.userRepository.manager
      .getRepository(SystemUserUnit)
      .find({
        where: { systemUserId: userId },
        relations: ['systemUnit'],
      });

    const rawAllowedUnits = [
      user.systemUnit,
      ...userUnits.map((uu) => uu.systemUnit),
    ].filter((unit) => !!unit);

    const allowedUnits = rawAllowedUnits.filter(
      (unit, idx, self) => self.findIndex((u) => u.id === unit.id) === idx,
    );

    return {
      id: user.id,
      name: user.name,
      login: user.login,
      email: user.email,
      unit: user.systemUnit || (allowedUnits.length > 0 ? allowedUnits[0] : null),
      allowedUnits,
      vendedorId: vendedor?.id || null,
      supervisorId: supervisor?.id || null,
      managedVendedorIds,
      isGerente,
      roles: userRoles,
      frontpage: user.frontpage ? {
        id: user.frontpage.id,
        name: user.frontpage.name,
        controller: user.frontpage.controller
      } : null,
      programs: programs,
    };
  }

  async switchUnit(userId: number, targetUnitId: number) {
    const profile = await this.getProfile(userId);
    
    // Validar se targetUnitId está entre as unidades permitidas
    const isAllowed = profile.allowedUnits.some((u) => u && u.id === targetUnitId);
    if (!isAllowed) {
      throw new UnauthorizedException('Acesso negado à unidade selecionada');
    }

    // Buscar a unidade ativa selecionada
    const activeUnit = profile.allowedUnits.find((u) => u && u.id === targetUnitId);

    // Gerar novo ID de sessão única
    const sessionId = uuidv4();
    await this.cacheManager.set(`session:${userId}`, sessionId, 86400000); 
    await this.userRepository.update(userId, { currentSessionId: sessionId });

    const payload = {
      username: profile.login,
      sub: userId,
      sid: sessionId,
      unitId: targetUnitId,
    };

    // Sobrescrever a unidade ativa no perfil para o retorno
    profile.unit = activeUnit as any;

    return {
      accessToken: this.jwtService.sign(payload),
      user: profile,
    };
  }

  async getMenu(userId: number) {
    return this.permissionsService.getMenuStructure(userId);
  }

  async getCurrentSessionId(userId: number): Promise<string | null> {
    const user = await this.userRepository.findOne({
      where: { id: userId },
      select: ['currentSessionId'],
    });
    return user?.currentSessionId || null;
  }

  async acceptTerms(userId: number) {
    await this.userRepository.update(userId, {
      acceptedTermPolicy: 'Y',
      acceptedTermPolicyAt: new Date().toISOString(),
    });

    const user = await this.userRepository.findOne({ where: { id: userId } });
    return this.login(user as any);
  }
}
