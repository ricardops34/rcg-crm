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
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { UsersService } from './users.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';

@ApiTags('Admin / Users')
@ApiBearerAuth()
@Controller('admin/users')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemUserList')
export class UsersController {
  constructor(private readonly usersService: UsersService) {}

  @Get()
  @ApiOperation({ summary: 'Lista todos os usuários' })
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
  findOne(@Param('id') id: string) {
    return this.usersService.findOne(+id);
  }

  @Post()
  @ApiOperation({ summary: 'Cria um novo usuário' })
  create(@Body() userData: any) {
    return this.usersService.create(userData);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um usuário' })
  update(@Param('id') id: string, @Body() userData: any) {
    return this.usersService.update(+id, userData);
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Remove um usuário' })
  remove(@Param('id') id: string) {
    return this.usersService.remove(+id);
  }
}
