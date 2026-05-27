import { Module } from '@nestjs/common';
import { JwtModule } from '@nestjs/jwt';
import { PassportModule } from '@nestjs/passport';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AuthService } from './auth.service';
import { AuthController } from './auth.controller';
import { MeController } from './me.controller';
import { SystemUser } from '../admin/entities/system-user.entity';
import { SystemUserUnit } from '../admin/entities/system-user-unit.entity';
import { JwtStrategy } from './jwt.strategy';
import { AdminModule } from '../admin/admin.module';
import { CommercialModule } from '../commercial/commercial.module';

@Module({
  imports: [
    TypeOrmModule.forFeature([SystemUser, SystemUserUnit], 'security'),
    PassportModule,
    AdminModule,
    CommercialModule,
    JwtModule.registerAsync({
      imports: [ConfigModule],
      inject: [ConfigService],
      useFactory: (configService: ConfigService) => ({
        secret: configService.get<string>('JWT_SECRET'),
        signOptions: { expiresIn: '8h' },
      }),
    }),
  ],
  providers: [AuthService, JwtStrategy],
  controllers: [AuthController, MeController],
  exports: [AuthService],
})
export class AuthModule {}
