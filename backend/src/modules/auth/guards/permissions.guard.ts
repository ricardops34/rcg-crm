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

    // Fast-path: admin pelo login ou pelo role já resolvido no JWT strategy
    if (user.username === 'admin' || user.roles?.includes('ADMIN')) {
      return true;
    }

    const hasPermission = await this.permissionsService.hasPermission(user.userId, requiredPermission);

    if (!hasPermission) {
      throw new ForbiddenException(`Você não tem permissão para acessar o recurso: ${requiredPermission}`);
    }

    return true;
  }
}
