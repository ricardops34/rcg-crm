import { Controller, Get, Post, Put, Delete, Body, Param, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { UnitsService } from './units.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';
import { CreateUnitDto, UpdateUnitDto } from './dto/admin-forms.dto';

@ApiTags('Admin / Units')
@ApiBearerAuth()
@Controller('admin/units')
@UseGuards(JwtAuthGuard, PermissionsGuard)
export class UnitsController {
  constructor(private readonly unitsService: UnitsService) {}

  @Get()
  @ApiOperation({ summary: 'Lista todas as unidades' })
  async findAll() {
    return this.unitsService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Obtém detalhes de uma unidade' })
  async findOne(@Param('id') id: string) {
    return this.unitsService.findOne(+id);
  }

  @Post()
  @RequirePermission('SystemUnitList')
  @ApiOperation({ summary: 'Cadastra uma nova unidade' })
  @ApiBody({ type: CreateUnitDto })
  @ApiResponse({ status: 201, description: 'Unidade criada com sucesso' })
  async create(@Body() data: CreateUnitDto) {
    return this.unitsService.save(data);
  }

  @Put(':id')
  @RequirePermission('SystemUnitList')
  @ApiOperation({ summary: 'Atualiza dados de uma unidade' })
  @ApiBody({ type: UpdateUnitDto })
  @ApiResponse({ status: 200, description: 'Unidade atualizada com sucesso' })
  async update(@Param('id') id: string, @Body() data: UpdateUnitDto) {
    return this.unitsService.save({ ...data, id: +id });
  }

  @Delete(':id')
  @RequirePermission('SystemUnitList')
  @ApiOperation({ summary: 'Remove uma unidade' })
  @ApiResponse({ status: 200, description: 'Unidade removida com sucesso' })
  async remove(@Param('id') id: string) {
    return this.unitsService.remove(+id);
  }
}
