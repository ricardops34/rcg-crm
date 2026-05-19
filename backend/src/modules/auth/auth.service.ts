import { Injectable, UnauthorizedException, Inject } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { CACHE_MANAGER } from '@nestjs/cache-manager';
import { Cache } from 'cache-manager';
import * as bcrypt from 'bcrypt';
import * as crypto from 'crypto';
import { v4 as uuidv4 } from 'uuid';
import { SystemUser } from '../admin/entities/system-user.entity';
import { PermissionsService } from '../admin/permissions.service';

interface AuthUser {
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
    @InjectRepository(SystemUser)
    private userRepository: Repository<SystemUser>,
    private jwtService: JwtService,
    private permissionsService: PermissionsService,
    @Inject(CACHE_MANAGER) private cacheManager: Cache,
  ) {}

  async validateUser(
    login: string,
    pass: string,
  ): Promise<Partial<SystemUser> | null> {
    const user = await this.userRepository.findOne({
      where: { login, active: 'Y' },
    });

    if (!user) {
      throw new UnauthorizedException('Usuário não encontrado ou inativo');
    }

    let isMatch = false;

    // Lógica ADR 001: Suporte a migração de MD5 para Bcrypt
    const isMd5 = user.password.length === 32;

    if (isMd5) {
      const passMd5 = crypto.createHash('md5').update(pass).digest('hex');
      if (passMd5 === user.password) {
        isMatch = true;
        // Migrar para Bcrypt imediatamente
        const hashedPass = await bcrypt.hash(pass, 10);
        user.password = hashedPass;
        await this.userRepository.save(user);
      }
    } else {
      isMatch = await bcrypt.compare(pass, user.password);
    }

    if (isMatch) {
      const result = { ...user };
      delete result.password;
      return result;
    }

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
      relations: ['systemUnit'],
    });

    if (!user) throw new UnauthorizedException('Usuário não encontrado');

    const programs = await this.permissionsService.getUserPrograms(userId);

    return {
      id: user.id,
      name: user.name,
      login: user.login,
      email: user.email,
      unit: user.systemUnit,
      programs: programs,
    };
  }
}
