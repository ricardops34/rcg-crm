import { Controller, Get, UseGuards, Param, Post, Body, Put, Delete } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { ProgramsService } from './programs.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';
import { CreateProgramDto, UpdateProgramDto } from './dto/admin-forms.dto';

@ApiTags('Admin / Programs')
@ApiBearerAuth()
@Controller('admin/programs')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemProgramList')
export class ProgramsController {
  constructor(private readonly programsService: ProgramsService) {}

  @Get()
  @ApiOperation({ summary: 'Lista todos os programas/telas' })
  async findAll() {
    return this.programsService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Obtém detalhes de um programa' })
  async findOne(@Param('id') id: string) {
    return this.programsService.findOne(+id);
  }

  @Post()
  @ApiOperation({ summary: 'Cadastra um novo programa' })
  @ApiBody({ type: CreateProgramDto })
  @ApiResponse({ status: 201, description: 'Programa criado com sucesso' })
  async create(@Body() data: CreateProgramDto) {
    return this.programsService.create(data);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um programa' })
  @ApiBody({ type: UpdateProgramDto })
  @ApiResponse({ status: 200, description: 'Programa atualizado com sucesso' })
  async update(@Param('id') id: string, @Body() data: UpdateProgramDto) {
    return this.programsService.update(+id, data);
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Remove um programa' })
  @ApiResponse({ status: 200, description: 'Programa removido com sucesso' })
  async remove(@Param('id') id: string) {
    return this.programsService.remove(+id);
  }

}
