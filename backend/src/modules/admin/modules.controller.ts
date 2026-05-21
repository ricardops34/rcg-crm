import { Controller, Get, Post, Put, Delete, Body, Param, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { ModulesService } from './modules.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';

@ApiTags('Admin / Modules')
@ApiBearerAuth()
@Controller('admin/modules')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemModuleList')
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
