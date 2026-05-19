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
import { GroupsService } from './groups.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from './guards/permissions.guard';
import { ControllerName } from './decorators/controller-name.decorator';

@Controller('admin/groups')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('SystemGroupList')
export class GroupsController {
  constructor(private readonly groupsService: GroupsService) {}

  @Get()
  async findAll() {
    return this.groupsService.findAll();
  }

  @Get(':id')
  async findOne(@Param('id') id: string) {
    return this.groupsService.findOne(+id);
  }

  @Post()
  async create(@Body() groupData: any) {
    return this.groupsService.create(groupData);
  }

  @Put(':id')
  async update(@Param('id') id: string, @Body() groupData: any) {
    return this.groupsService.update(+id, groupData);
  }

  @Delete(':id')
  async remove(@Param('id') id: string) {
    return this.groupsService.remove(+id);
  }
}
