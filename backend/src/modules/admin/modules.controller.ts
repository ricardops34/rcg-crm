import { Controller, Get, Post, Put, Delete, Body, Param, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse } from '@nestjs/swagger';
import { AdminModuleResponseDto } from './dto/admin-shared.dto';

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
  async findOne(@Param('id') id: string) {
    return this.modulesService.findOne(+id);
  }

  @Post()
  async create(@Body() data: any) {
    return this.modulesService.save(data);
  }

  @Put(':id')
  async update(@Param('id') id: string, @Body() data: any) {
    return this.modulesService.save({ ...data, id: +id });
  }

  @Delete(':id')
  async remove(@Param('id') id: string) {
    return this.modulesService.remove(+id);
  }
}
