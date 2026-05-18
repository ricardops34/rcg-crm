import { Controller, Get, UseGuards } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { SystemGroup } from './entities/system-group.entity';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';

@Controller('admin/groups')
@UseGuards(JwtAuthGuard)
export class GroupsController {
  constructor(
    @InjectRepository(SystemGroup, 'security')
    private readonly groupRepository: Repository<SystemGroup>,
  ) {}

  @Get()
  async findAll() {
    return this.groupRepository.find({
      order: { name: 'ASC' }
    });
  }
}
