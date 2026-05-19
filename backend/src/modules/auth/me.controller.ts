import { Controller, Get, UseGuards, Request } from '@nestjs/common';
import { JwtAuthGuard } from './guards/jwt-auth.guard';
import { AuthService } from './auth.service';

interface AuthenticatedRequest extends Request {
  user: {
    userId: number;
    username: string;
  };
}

@Controller('auth/me')
@UseGuards(JwtAuthGuard)
export class MeController {
  constructor(private readonly authService: AuthService) {}

  @Get()
  async getMe(@Request() req: AuthenticatedRequest) {
    return this.authService.getProfile(req.user.userId);
  }
}
