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
import { CreateGroupDto, UpdateGroupDto } from './dto/groups.dto';

@ApiTags('Admin / Groups')
@ApiBearerAuth()
@Controller('admin/groups')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemGroupList')
export class GroupsController {
  constructor(private readonly groupsService: GroupsService) {}

  @Get()
  @ApiOperation({ summary: 'Lista todos os grupos de permissão' })
  async findAll() {
    return this.groupsService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Obtém detalhes de um grupo de permissão específico com seus programas vinculados' })
  async findOne(@Param('id') id: string) {
    return this.groupsService.findOne(+id);
  }

  @Post()
  @ApiOperation({ summary: 'Cria um novo grupo de permissão' })
  async create(@Body() groupData: CreateGroupDto) {
    return this.groupsService.create(groupData);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um grupo de permissão' })
  async update(@Param('id') id: string, @Body() groupData: UpdateGroupDto) {
    return this.groupsService.update(+id, groupData);
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Remove um grupo de permissão' })
  async remove(@Param('id') id: string) {
    return this.groupsService.remove(+id);
  }
}
