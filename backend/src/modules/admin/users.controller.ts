import {
  Controller,
  Get,
  Post,
  Put,
  Delete,
  Body,
  Param,
  UseGuards,
} from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiBody, ApiResponse } from '@nestjs/swagger';
import { UsersService } from './users.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';
import { SaveTermsDto, CreateUserDto, UpdateUserDto } from './dto/users.dto';

import { UserResponseDto } from './dto/user.dto';

@ApiTags('Admin / Users')
@ApiBearerAuth()
@Controller('admin/users')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemUserList')
export class UsersController {
  constructor(private readonly usersService: UsersService) {}

  @Get()
  @ApiOperation({ summary: 'Lista todos os usuários' })
  @ApiResponse({ status: 200, type: [UserResponseDto] })
  async findAll() {
    return this.usersService.findAll();
  }

  @Get('terms')
  @ApiOperation({ summary: 'Obtém o conteúdo dos termos de uso' })
  async getTerms() {
    return this.usersService.getTerms();
  }

  @Post('terms')
  @ApiOperation({ summary: 'Atualiza o texto e versão dos termos de uso' })
  async saveTerms(@Body() data: { text: string, version: string }) {
    return this.usersService.saveTerms(data);
  }

  @Get(':id')
  @ApiOperation({ summary: 'Obtém detalhes de um usuário específico' })
  @ApiResponse({ status: 200, type: UserResponseDto })
  findOne(@Param('id') id: string) {
    return this.usersService.findOne(+id);
  }


  @Post()
  @ApiOperation({ summary: 'Cria um novo usuário' })
  @ApiBody({ type: CreateUserDto })
  @ApiResponse({ status: 201, type: UserResponseDto, description: 'Usuário criado com sucesso' })
  create(@Body() userData: CreateUserDto) {
    return this.usersService.create(userData);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um usuário' })
  @ApiBody({ type: UpdateUserDto })
  @ApiResponse({ status: 200, type: UserResponseDto, description: 'Usuário atualizado com sucesso' })
  update(@Param('id') id: string, @Body() userData: UpdateUserDto) {
    return this.usersService.update(+id, userData);
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Remove um usuário do sistema' })
  @ApiResponse({ status: 200, description: 'Usuário removido com sucesso' })
  remove(@Param('id') id: string) {
    return this.usersService.remove(+id);
  }
}
