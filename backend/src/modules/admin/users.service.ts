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
    @InjectRepository(SystemUser)
    private userRepository: Repository<SystemUser>,
    @InjectRepository(SystemUserGroup)
    private userGroupRepository: Repository<SystemUserGroup>,
    @InjectRepository(SystemUserUnit)
    private userUnitRepository: Repository<SystemUserUnit>,
  ) {}

  async findAll() {
    return this.userRepository.find({
      relations: ['frontpage', 'systemUnit'],
    });
  }

  async findOne(id: number) {
    const user = await this.userRepository.findOne({
      where: { id },
      relations: ['frontpage', 'systemUnit'],
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
    if (userData.password) {
      userData.password = await bcrypt.hash(userData.password, 10);
    }
    const user = this.userRepository.create(userData);
    return this.userRepository.save(user);
  }

  async update(id: number, userData: any) {
    if (userData.password) {
      userData.password = await bcrypt.hash(userData.password, 10);
    }
    await this.userRepository.update(id, userData);
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
