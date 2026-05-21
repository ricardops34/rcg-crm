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
import { PermissionsService } from '../admin/permissions.service';
import { Vendedor } from '../commercial/entities/vendedor.entity';
import { Supervisor } from '../commercial/entities/supervisor.entity';
import { SupervisorVendedor } from '../commercial/entities/supervisor-vendedor.entity';

export interface AuthUser {
  id: number;
  login: string;
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
    await this.cacheManager.set(`session:${user.id}`, sessionId, 0);

    // Atualizar no banco para persistência
    await this.userRepository.update(user.id, { currentSessionId: sessionId });

    const payload = {
      username: user.login,
      sub: user.id,
      sid: sessionId,
      unitId: user.systemUnitId,
    };

    return {
      accessToken: this.jwtService.sign(payload),
      user: await this.getProfile(user.id),
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
    const userRoles = (await this.userRepository.query(
      `SELECT sg.role FROM system_group sg 
       JOIN system_user_group sug ON sug.system_group_id = sg.id 
       WHERE sug.system_user_id = $1`,
      [userId],
    )).map((g: any) => (g.role || '').toUpperCase());

    const isGerente = userRoles.includes('GERENTE') || userRoles.includes('ADMIN');

    return {
      id: user.id,
      name: user.name,
      login: user.login,
      email: user.email,
      unit: user.systemUnit,
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

  async getMenu(userId: number) {
    return this.permissionsService.getMenuStructure(userId);
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
