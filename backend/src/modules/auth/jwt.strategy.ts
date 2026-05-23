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
    try {
      // Se for um token temporário (2fa, terms), apenas validar se o payload está ok
      if (payload.scope) {
        return {
          userId: payload.sub,
          username: payload.username,
          scope: payload.scope,
        };
      }

      // Validação de Sessão Única (Trava Redis com Fallback no Banco)
      let activeSessionId = await this.cacheManager.get<string>(
        `session:${payload.sub}`,
      );

      if (!activeSessionId) {
        // Fallback resiliente: Busca do banco de dados (evita 401 caso o cache caia em réplicas isoladas do cluster)
        activeSessionId = (await this.authService.getCurrentSessionId(payload.sub)) || undefined;
        if (activeSessionId) {
          // Grava de volta no cache local para acelerar as próximas requisições
          await this.cacheManager.set(`session:${payload.sub}`, activeSessionId, 86400000);
        }
      }

      if (!activeSessionId || activeSessionId !== payload.sid) {
        console.warn(`[AUTH-GUARD] ❌ Sessão divergente/derrubada para usuário ${payload.sub}. SID no token: ${payload.sid}, SID ativo: ${activeSessionId}`);
        // TEMPORARILY DISABLED: throw new UnauthorizedException('Sessão expirada ou iniciada em outro dispositivo');
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
        roles: profile.roles,
      };
    } catch (error) {
      console.error(`[AUTH-GUARD] 🔥 Erro fatal na validação do token:`, error.message);
      if (error instanceof UnauthorizedException) throw error;
      throw new UnauthorizedException('Falha técnica na validação da sessão');
    }
  }
}
