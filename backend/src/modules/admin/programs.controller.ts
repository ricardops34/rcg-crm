import { Controller, Get, UseGuards } from '@nestjs/common';
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
}
