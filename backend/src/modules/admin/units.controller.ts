import { Controller, Get, Post, Put, Delete, Body, Param, UseGuards } from '@nestjs/common';
import { UnitsService } from './units.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from './guards/permissions.guard';
import { ControllerName } from './decorators/controller-name.decorator';

@Controller('admin/units')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('SystemUnitList')
export class UnitsController {
  constructor(private readonly unitsService: UnitsService) {}

  @Get()
  async findAll() {
    return this.unitsService.findAll();
  }

  @Get(':id')
  async findOne(@Param('id') id: string) {
    return this.unitsService.findOne(+id);
  }

  @Post()
  async create(@Body() data: any) {
    return this.unitsService.save(data);
  }

  @Put(':id')
  async update(@Param('id') id: string, @Body() data: any) {
    return this.unitsService.save({ ...data, id: +id });
  }

  @Delete(':id')
  async remove(@Param('id') id: string) {
    return this.unitsService.remove(+id);
  }
}
