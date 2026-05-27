import {
  Controller,
  Get,
  Post,
  Body,
  UnauthorizedException,
  HttpCode,
  HttpStatus,
  UseGuards,
  Request,
} from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { AuthService, AuthUser } from './auth.service';
import { UsersService } from '../admin/users.service';
import { JwtAuthGuard } from './guards/jwt-auth.guard';
import { LoginDto, Verify2faDto, LoginResponseDto } from './dto/auth.dto';

interface AuthenticatedRequest extends Request {
  user: {
    userId: number;
    username: string;
    scope?: string;
  };
}

@ApiTags('Auth')
@Controller('auth')
export class AuthController {
  constructor(
    private readonly authService: AuthService,
    private readonly usersService: UsersService,
  ) {}

  @Get('terms')
  @ApiOperation({ summary: 'Obtém a versão atual dos termos de uso' })
  async getTerms() {
    return this.usersService.getTerms();
  }

  @Post('login')
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Realiza o login básico (usuário/senha)' })
  @ApiBody({ type: LoginDto })
  @ApiResponse({ status: 200, type: LoginResponseDto, description: 'Login bem-sucedido ou redirecionamento para 2FA/Terms' })
  async login(@Body() body: LoginDto) {
    const user = await this.authService.validateUser(
      body.login,
      body.password || '',
    );
    if (!user) {
      throw new UnauthorizedException('Credenciais inválidas');
    }
    return this.authService.login(user as AuthUser);
  }

  @ApiBearerAuth()
  @UseGuards(JwtAuthGuard)
  @Post('verify-2fa')
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Valida o código de segundo fator (2FA)' })
  @ApiBody({ type: Verify2faDto })
  @ApiResponse({ status: 200, type: LoginResponseDto })
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
    return this.authService.login({
      ...user,
      twoFactorVerified: true,
    });
  }

  @ApiBearerAuth()
  @UseGuards(JwtAuthGuard)
  @Post('accept-terms')
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Aceita os termos de uso e libera o acesso total' })
  @ApiResponse({ status: 200, type: LoginResponseDto })
  async acceptTerms(@Request() req: AuthenticatedRequest) {
    if (req.user.scope !== 'TERMS') {
      throw new UnauthorizedException('Token inválido para esta operação');
    }

    return this.authService.acceptTerms(req.user.userId);
  }

  @ApiBearerAuth()
  @UseGuards(JwtAuthGuard)
  @Post('switch-unit')
  @HttpCode(HttpStatus.OK)
  @ApiOperation({ summary: 'Chaveia a unidade ativa do usuário gerando um novo token JWT' })
  async switchUnit(
    @Request() req: AuthenticatedRequest,
    @Body() body: { unitId: number }
  ) {
    return this.authService.switchUnit(req.user.userId, body.unitId);
  }
}
