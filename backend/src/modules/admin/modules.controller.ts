import { Controller, Get, Post, Put, Delete, Body, Param, UseGuards } from '@nestjs/common';
import { ModulesService } from './modules.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from './guards/permissions.guard';
import { ControllerName } from './decorators/controller-name.decorator';

@Controller('admin/modules')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('SystemModuleList')
export class ModulesController {
  constructor(private readonly modulesService: ModulesService) {}

  @Get()
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
