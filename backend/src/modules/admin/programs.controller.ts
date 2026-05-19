import { Controller, Get, UseGuards, Param, Post, Body, Put, Delete } from '@nestjs/common';
import { ProgramsService } from './programs.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from './guards/permissions.guard';
import { ControllerName } from './decorators/controller-name.decorator';

@Controller('admin/programs')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('SystemProgramList')
export class ProgramsController {
  constructor(private readonly programsService: ProgramsService) {}

  @Get()
  async findAll() {
    return this.programsService.findAll();
  }

  @Get(':id')
  async findOne(@Param('id') id: string) {
    return this.programsService.findOne(+id);
  }

  @Post()
  async create(@Body() data: any) {
    return this.programsService.create(data);
  }

  @Put(':id')
  async update(@Param('id') id: string, @Body() data: any) {
    return this.programsService.update(+id, data);
  }

  @Delete(':id')
  async remove(@Param('id') id: string) {
    return this.programsService.remove(+id);
  }
}
