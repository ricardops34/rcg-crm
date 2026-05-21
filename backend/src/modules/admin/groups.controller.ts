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
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { GroupsService } from './groups.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';

@ApiTags('Admin / Groups')
@ApiBearerAuth()
@Controller('admin/groups')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemGroupList')
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
