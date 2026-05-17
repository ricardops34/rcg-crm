import { Injectable, UnauthorizedException } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import * as bcrypt from 'bcrypt';
import * as crypto from 'crypto';
import { SystemUser } from '../admin/entities/system-user.entity';

@Injectable()
export class AuthService {
  constructor(
    @InjectRepository(SystemUser)
    private userRepository: Repository<SystemUser>,
    private jwtService: JwtService,
  ) {}

  async validateUser(login: string, pass: string): Promise<any> {
    const user = await this.userRepository.findOne({ where: { login, active: 'Y' } });

    if (!user) {
      throw new UnauthorizedException('Usuário não encontrado ou inativo');
    }

    let isMatch = false;

    // Lógica ADR 001: Suporte a migração de MD5 para Bcrypt
    const isMd5 = user.password.length === 32;

    if (isMd5) {
      const passMd5 = crypto.createHash('md5').update(pass).digest('hex');
      if (passMd5 === user.password) {
        isMatch = true;
        // Migrar para Bcrypt imediatamente
        const hashedPass = await bcrypt.hash(pass, 10);
        user.password = hashedPass;
        await this.userRepository.save(user);
      }
    } else {
      isMatch = await bcrypt.compare(pass, user.password);
    }

    if (isMatch) {
      const { password, ...result } = user;
      return result;
    }

    return null;
  }

  async login(user: any) {
    const payload = { username: user.login, sub: user.id };
    return {
      access_token: this.jwtService.sign(payload),
      user: {
        id: user.id,
        name: user.name,
        login: user.login,
        email: user.email
      }
    };
  }
}
