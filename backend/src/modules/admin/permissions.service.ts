import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, In } from 'typeorm';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemGroupProgram } from './entities/system-group-program.entity';

@Injectable()
export class PermissionsService {
  constructor(
    @InjectRepository(SystemUserGroup, 'security')
    private userGroupRepository: Repository<SystemUserGroup>,
    @InjectRepository(SystemGroupProgram, 'security')
    private groupProgramRepository: Repository<SystemGroupProgram>,
  ) {}

  async hasPermission(userId: number, controller: string): Promise<boolean> {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
    });

    const groupIds = userGroups.map((ug) => ug.systemGroupId);
    if (groupIds.length === 0) return false;

    const permissions = await this.groupProgramRepository.find({
      where: {
        systemGroupId: In(groupIds),
        systemProgram: { controller: controller },
      },
      relations: ['systemProgram'],
    });

    return permissions.length > 0;
  }

  async getUserPrograms(userId: number) {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
    });

    const groupIds = userGroups.map((ug) => ug.systemGroupId);
    if (groupIds.length === 0) return [];

    const groupPrograms = await this.groupProgramRepository.find({
      where: { systemGroupId: In(groupIds) },
      relations: ['systemProgram'],
    });

    // Remover duplicatas e formatar
    const programs = new Map();
    groupPrograms.forEach((gp) => {
      programs.set(gp.systemProgramId, gp.systemProgram);
    });

    return Array.from(programs.values());
  }
}
