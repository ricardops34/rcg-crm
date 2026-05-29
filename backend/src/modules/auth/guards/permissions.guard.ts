import { Injectable, CanActivate, ExecutionContext, ForbiddenException } from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import { PermissionsService } from '../../admin/permissions.service';
import { PERMISSION_KEY } from '../decorators/permissions.decorator';

@Injectable()
export class PermissionsGuard implements CanActivate {
  constructor(
    private reflector: Reflector,
    private permissionsService: PermissionsService,
  ) {}

  async canActivate(context: ExecutionContext): Promise<boolean> {
    const requiredPermission = this.reflector.getAllAndOverride<string>(PERMISSION_KEY, [
      context.getHandler(),
      context.getClass(),
    ]);

    if (!requiredPermission) {
      return true;
    }

    const { user } = context.switchToHttp().getRequest();

    if (!user || !user.userId) {
      return false;
    }

    // Fast-path 1: role ADMIN já resolvida pelo JWT strategy (evita query extra)
    if (Array.isArray(user.roles) && user.roles.includes('ADMIN')) {
      return true;
    }

    // Fast-path 2: verifica ADMIN via banco (cobre tokens antigos sem roles populados)
    const isAdmin = await this.permissionsService.isAdminUser(user.userId);
    if (isAdmin) {
      return true;
    }

    const hasPermission = await this.permissionsService.hasPermission(user.userId, requiredPermission);

    if (!hasPermission) {
      throw new ForbiddenException(`Você não tem permissão para acessar o recurso: ${requiredPermission}`);
    }

    return true;
  }
}
