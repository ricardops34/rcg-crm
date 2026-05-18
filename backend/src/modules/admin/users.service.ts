import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import * as bcrypt from 'bcrypt';
import { SystemUser } from './entities/system-user.entity';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemUserUnit } from './entities/system-user-unit.entity';

@Injectable()
export class UsersService {
  constructor(
    @InjectRepository(SystemUser, 'security')
    private userRepository: Repository<SystemUser>,
    @InjectRepository(SystemUserGroup, 'security')
    private userGroupRepository: Repository<SystemUserGroup>,
    @InjectRepository(SystemUserUnit, 'security')
    private userUnitRepository: Repository<SystemUserUnit>,
  ) {}

  async findAll() {
    return this.userRepository.find({
      relations: ['frontpage', 'systemUnit', 'userGroups', 'userGroups.systemGroup'],
    });
  }

  async findOne(id: number) {
    const user = await this.userRepository.findOne({
      where: { id },
      relations: ['frontpage', 'systemUnit', 'userGroups', 'userGroups.systemGroup'],
    });
    if (!user) throw new NotFoundException('Usuário não encontrado');
    return user;
  }

  async findByLogin(login: string) {
    return this.userRepository.findOne({
      where: { login },
    });
  }

  async create(userData: any) {
    const { groups, ...data } = userData;
    if (data.password) {
      data.password = await bcrypt.hash(data.password, 10);
    }
    const user = this.userRepository.create(data);
    const savedUser = await this.userRepository.save(user);

    if (groups && groups.length > 0) {
      const userGroups = groups.map(groupId => 
        this.userGroupRepository.create({ systemUserId: savedUser.id, systemGroupId: groupId })
      );
      await this.userGroupRepository.save(userGroups);
    }

    return this.findOne(savedUser.id);
  }

  async update(id: number, userData: any) {
    const { groups, userGroups, frontpage, systemUnit, ...data } = userData;
    if (data.password) {
      data.password = await bcrypt.hash(data.password, 10);
    } else {
      delete data.password;
    }
    
    await this.userRepository.update(id, data);

    if (groups) {
      // Remover grupos antigos e inserir novos
      await this.userGroupRepository.delete({ systemUserId: id });
      if (groups.length > 0) {
        const newUserGroups = groups.map(groupId => 
          this.userGroupRepository.create({ systemUserId: id, systemGroupId: groupId })
        );
        await this.userGroupRepository.save(newUserGroups);
      }
    }

    return this.findOne(id);
  }

  async getUserPermissions(userId: number) {
    // Buscar grupos do usuário
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });

    const groupIds = userGroups.map(ug => ug.systemGroupId);
    if (groupIds.length === 0) return [];

    // Esta parte requeriria injetar Repository<SystemGroupProgram>
    // Vou simplificar ou adicionar o repositório necessário
    return groupIds; 
  }
}
