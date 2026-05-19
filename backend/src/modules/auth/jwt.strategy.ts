import { ExtractJwt, Strategy } from 'passport-jwt';
import { PassportStrategy } from '@nestjs/passport';
import { Injectable, UnauthorizedException, Inject } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { CACHE_MANAGER } from '@nestjs/cache-manager';
import type { Cache } from 'cache-manager';

import { AuthService } from './auth.service';

interface JwtPayload {
  sub: number;
  username: string;
  scope?: string;
  sid?: string;
  unitId?: number;
}

@Injectable()
export class JwtStrategy extends PassportStrategy(Strategy) {
  constructor(
    private configService: ConfigService,
    private authService: AuthService,
    @Inject(CACHE_MANAGER) private cacheManager: Cache,
  ) {
    super({
      jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
      ignoreExpiration: false,
      secretOrKey: configService.get<string>('JWT_SECRET') || 'secret',
    });
  }

  async validate(payload: JwtPayload) {
    // Se for um token temporário (2fa, terms), apenas validar se o payload está ok
    if (payload.scope) {
      return {
        userId: payload.sub,
        username: payload.username,
        scope: payload.scope,
      };
    }

    // Validação de Sessão Única (Trava Redis)
    const activeSessionId = await this.cacheManager.get(
      `session:${payload.sub}`,
    );
    if (!activeSessionId || activeSessionId !== payload.sid) {
      throw new UnauthorizedException(
        'Sessão expirada ou iniciada em outro dispositivo',
      );
    }

    const profile = await this.authService.getProfile(payload.sub);

    return {
      userId: payload.sub,
      username: payload.username,
      unitId: payload.unitId,
      sid: payload.sid,
      vendedorId: profile.vendedorId,
      supervisorId: profile.supervisorId,
      managedVendedorIds: profile.managedVendedorIds,
      isGerente: profile.isGerente,
    };
  }
}
