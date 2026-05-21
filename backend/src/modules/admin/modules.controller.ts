import { Controller, Get, Post, Put, Delete, Body, Param, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { AdminModuleResponseDto } from './dto/admin-shared.dto';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';
import { ModulesService } from './modules.service';
import { CreateModuleDto, UpdateModuleDto } from './dto/admin-forms.dto';

@ApiTags('Admin / Modules')
@ApiBearerAuth()
@Controller('admin/modules')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemModuleList')
export class ModulesController {
  constructor(private readonly modulesService: ModulesService) {}

  @Get()
  @ApiOperation({ summary: 'Lista todos os módulos do sistema' })
  @ApiResponse({ status: 200, type: [AdminModuleResponseDto] })
  async findAll() {
    return this.modulesService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Obtém detalhes de um módulo' })
  async findOne(@Param('id') id: string) {
    return this.modulesService.findOne(+id);
  }

  @Post()
  @ApiOperation({ summary: 'Cadastra um novo módulo' })
  @ApiBody({ type: CreateModuleDto })
  @ApiResponse({ status: 201, description: 'Módulo criado com sucesso' })
  async create(@Body() data: CreateModuleDto) {
    return this.modulesService.save(data);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um módulo' })
  @ApiBody({ type: UpdateModuleDto })
  @ApiResponse({ status: 200, description: 'Módulo atualizado com sucesso' })
  async update(@Param('id') id: string, @Body() data: UpdateModuleDto) {
    return this.modulesService.save({ ...data, id: +id });
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Remove um módulo' })
  @ApiResponse({ status: 200, description: 'Módulo removido com sucesso' })
  async remove(@Param('id') id: string) {
    return this.modulesService.remove(+id);
  }
}
