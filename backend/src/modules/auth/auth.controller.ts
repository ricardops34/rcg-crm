import {
  Controller,
  Post,
  Body,
  UnauthorizedException,
  HttpCode,
  HttpStatus,
  UseGuards,
  Request,
} from '@nestjs/common';
import { AuthService } from './auth.service';
import { JwtAuthGuard } from './guards/jwt-auth.guard';

interface LoginDto {
  login: string;
  password?: string;
}

interface Verify2faDto {
  code: string;
}

interface AuthenticatedRequest extends Request {
  user: {
    userId: number;
    username: string;
    scope?: string;
  };
}

@Controller('auth')
export class AuthController {
  constructor(private authService: AuthService) {}

  @Post('login')
  @HttpCode(HttpStatus.OK)
  async login(@Body() body: LoginDto) {
    const user = await this.authService.validateUser(
      body.login,
      body.password || '',
    );
    if (!user) {
      throw new UnauthorizedException('Credenciais inválidas');
    }
    return this.authService.login(user as any);
  }

  @UseGuards(JwtAuthGuard)
  @Post('verify-2fa')
  @HttpCode(HttpStatus.OK)
  async verify2fa(
    @Request() req: AuthenticatedRequest,
    @Body() body: Verify2faDto,
  ) {
    if (req.user.scope !== '2fa') {
      throw new UnauthorizedException('Token inválido para esta operação');
    }

    // TODO: Implementar lógica real de TOTP (ex: otplib)
    // Simulação: Aceitando '123456'
    if (body.code !== '123456') {
      throw new UnauthorizedException('Código 2FA incorreto');
    }

    const user = await this.authService.getProfile(req.user.userId);
    return this.authService.login({ ...user, twoFactorVerified: true });
  }

  @UseGuards(JwtAuthGuard)
  @Post('accept-terms')
  @HttpCode(HttpStatus.OK)
  async acceptTerms(@Request() req: AuthenticatedRequest) {
    if (req.user.scope !== 'TERMS') {
      throw new UnauthorizedException('Token inválido para esta operação');
    }

    // TODO: Gravar aceite no banco
    const user = await this.authService.getProfile(req.user.userId);
    return this.authService.login({ ...user, acceptedTermPolicy: 'Y' });
  }
}
