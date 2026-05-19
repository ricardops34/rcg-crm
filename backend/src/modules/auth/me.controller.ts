import { Controller, Get, Patch, Body, UseGuards, Request } from '@nestjs/common';
import { JwtAuthGuard } from './guards/jwt-auth.guard';
import { AuthService } from './auth.service';
import { UsersService } from '../admin/users.service';

interface AuthenticatedRequest extends Request {
  user: {
    userId: number;
    username: string;
  };
}

@Controller('auth/me')
@UseGuards(JwtAuthGuard)
export class MeController {
  constructor(
    private readonly authService: AuthService,
    private readonly usersService: UsersService,
  ) {}

  @Get()
  async getMe(@Request() req: AuthenticatedRequest) {
    return this.authService.getProfile(req.user.userId);
  }

  @Patch()
  async updateProfile(@Request() req: AuthenticatedRequest, @Body() data: any) {
    // Apenas permitir atualização de campos seguros pelo próprio usuário
    const safeData: any = {
      name: data.name,
      email: data.email,
      avatar: data.avatar,
    };
    if (data.password) {
      safeData.password = data.password;
    }
    return this.usersService.update(req.user.userId, safeData);
  }
}
