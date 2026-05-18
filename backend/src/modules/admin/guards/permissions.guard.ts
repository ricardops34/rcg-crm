import { Injectable, CanActivate, ExecutionContext, ForbiddenException } from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import { PermissionsService } from './permissions.service';

@Injectable()
export class PermissionsGuard implements CanActivate {
  constructor(
    private reflector: Reflector,
    private permissionsService: PermissionsService,
  ) {}

  async canActivate(context: ExecutionContext): Promise<boolean> {
    const requiredController = this.reflector.get<string>('controller', context.getHandler()) || 
                               this.reflector.get<string>('controller', context.getClass());
    
    if (!requiredController) {
      return true; // Se não houver controller definido, permite o acesso (rotas públicas ou abertas)
    }

    const request = context.switchToHttp().getRequest();
    const user = request.user;

    if (!user) {
      return false;
    }

    const hasPermission = await this.permissionsService.hasPermission(user.sub, requiredController);
    
    if (!hasPermission) {
      throw new ForbiddenException(`Acesso negado ao programa: ${requiredController}`);
    }

    return true;
  }
}
