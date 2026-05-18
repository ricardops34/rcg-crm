import { Injectable, ExecutionContext } from '@nestjs/common';
import { AuthGuard } from '@nestjs/passport';
import { ClsService } from 'nestjs-cls';

@Injectable()
export class JwtAuthGuard extends AuthGuard('jwt') {
  constructor(private readonly cls: ClsService) {
    super();
  }

  handleRequest(err, user, info, context: ExecutionContext) {
    if (user) {
      this.cls.set('user', user);
    }
    return super.handleRequest(err, user, info, context);
  }
}
