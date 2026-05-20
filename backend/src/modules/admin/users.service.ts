import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, In } from 'typeorm';
import * as bcrypt from 'bcrypt';
import { SystemUser } from './entities/system-user.entity';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemUserUnit } from './entities/system-user-unit.entity';
import { SystemUserProgram } from './entities/system-user-program.entity';

@Injectable()
export class UsersService {
  constructor(
    @InjectRepository(SystemUser, 'security')
    private userRepository: Repository<SystemUser>,
    @InjectRepository(SystemUserGroup, 'security')
    private userGroupRepository: Repository<SystemUserGroup>,
    @InjectRepository(SystemUserUnit, 'security')
    private userUnitRepository: Repository<SystemUserUnit>,
    @InjectRepository(SystemUserProgram, 'security')
    private userProgramRepository: Repository<SystemUserProgram>,
  ) {}

  async findAll() {
    return this.userRepository.find({
      relations: ['systemUnit', 'userGroups', 'userGroups.systemGroup'],
      order: { name: 'ASC' },
    });
  }

  async findOne(id: number) {
    const user = await this.userRepository.findOne({
      where: { id },
      relations: [
        'systemUnit', 
        'userGroups', 
        'userGroups.systemGroup',
        'userUnits',
        'userUnits.systemUnit',
        'userPrograms',
        'userPrograms.systemProgram'
      ],
    });
    if (!user) throw new NotFoundException('Usuário não encontrado');
    return user;
  }

  async create(userData: any) {
    const { groups, units, programs, ...data } = userData;
    
    if (data.password) {
      data.password = await bcrypt.hash(data.password, 10);
    }

    const user = this.userRepository.create(data);
    const savedUser = (await this.userRepository.save(user)) as any;

    await this.updateRelations(savedUser.id, groups, units, programs);

    return this.findOne(savedUser.id);
    }

  async update(id: number, userData: any) {
    const { groups, units, programs, ...data } = userData;

    if (data.password) {
      data.password = await bcrypt.hash(data.password, 10);
    } else {
      delete data.password;
    }

    await this.userRepository.update(id, data);
    await this.updateRelations(id, groups, units, programs);

    return this.findOne(id);
  }

  private async updateRelations(userId: number, groups?: number[], units?: number[], programs?: number[]) {
    // Atualizar Grupos
    if (groups) {
      await this.userGroupRepository.delete({ systemUsersId: userId });
      const userGroups = groups.map(groupId => 
        this.userGroupRepository.create({ systemUsersId: userId, systemGroupId: groupId })
      );
      await this.userGroupRepository.save(userGroups);
    }

    // Atualizar Unidades
    if (units) {
      await this.userUnitRepository.delete({ systemUsersId: userId });
      const userUnits = units.map(unitId => 
        this.userUnitRepository.create({ systemUsersId: userId, systemUnitId: unitId })
      );
      await this.userUnitRepository.save(userUnits);
    }

    // Atualizar Programas Diretos
    if (programs) {
      await this.userProgramRepository.delete({ systemUsersId: userId });
      const userPrograms = programs.map(progId => 
        this.userProgramRepository.create({ systemUsersId: userId, systemProgramId: progId })
      );
      await this.userProgramRepository.save(userPrograms);
    }
  }

  async remove(id: number) {
    await this.userGroupRepository.delete({ systemUsersId: id });
    await this.userUnitRepository.delete({ systemUsersId: id });
    await this.userProgramRepository.delete({ systemUsersId: id });
    return this.userRepository.delete(id);
  }
}
