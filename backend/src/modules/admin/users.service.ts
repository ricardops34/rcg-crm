import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, In } from 'typeorm';
import * as bcrypt from 'bcrypt';
import { SystemUser } from './entities/system-user.entity';
import { SystemGroup } from './entities/system-group.entity';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemUserUnit } from './entities/system-user-unit.entity';
import { SystemUserProgram } from './entities/system-user-program.entity';
import { SystemPreference } from './entities/system-preference.entity';

@Injectable()
export class UsersService {
  constructor(
    @InjectRepository(SystemUser, 'security')
    private userRepository: Repository<SystemUser>,
    @InjectRepository(SystemGroup, 'security')
    private groupRepository: Repository<SystemGroup>,
    @InjectRepository(SystemUserGroup, 'security')
    private userGroupRepository: Repository<SystemUserGroup>,
    @InjectRepository(SystemUserUnit, 'security')
    private userUnitRepository: Repository<SystemUserUnit>,
    @InjectRepository(SystemUserProgram, 'security')
    private userProgramRepository: Repository<SystemUserProgram>,
    @InjectRepository(SystemPreference, 'security')
    private preferenceRepository: Repository<SystemPreference>,
  ) {}

  async getTerms() {
    const terms = await this.preferenceRepository.findOne({ where: { id: 'system_terms_text' } });
    const version = await this.preferenceRepository.findOne({ where: { id: 'system_terms_version' } });
    return {
      text: terms?.preference || 'Texto padrão dos termos...',
      version: version?.preference || '1.0'
    };
  }

  async saveTerms(data: { text: string, version: string }) {
    await this.preferenceRepository.save([
      { id: 'system_terms_text', preference: data.text },
      { id: 'system_terms_version', preference: data.version }
    ]);
    return { success: true };
  }

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
      await this.userGroupRepository.delete({ systemUserId: userId });
      const userGroups = groups.map(groupId => 
        this.userGroupRepository.create({ systemUserId: userId, systemGroupId: groupId })
      );
      await this.userGroupRepository.save(userGroups);
    }

    // Atualizar Unidades
    if (units) {
      await this.userUnitRepository.delete({ systemUserId: userId });
      const userUnits = units.map(unitId => 
        this.userUnitRepository.create({ systemUserId: userId, systemUnitId: unitId })
      );
      await this.userUnitRepository.save(userUnits);
    }

    // Atualizar Programas Diretos
    if (programs) {
      await this.userProgramRepository.delete({ systemUserId: userId });
      const userPrograms = programs.map(progId => 
        this.userProgramRepository.create({ systemUserId: userId, systemProgramId: progId })
      );
      await this.userProgramRepository.save(userPrograms);
    }
  }

  async findByLogin(login: string): Promise<SystemUser | null> {
    return this.userRepository.findOne({ where: { login } });
  }

  async assignGroupByRole(userId: number, role: string): Promise<void> {
    const group = await this.groupRepository.findOne({ where: { role } });
    if (!group) return;
    const userGroup = this.userGroupRepository.create({
      systemUserId: userId,
      systemGroupId: group.id,
    });
    await this.userGroupRepository.save(userGroup);
  }

  async findUniqueLogin(baseLogin: string): Promise<string> {
    let login = baseLogin;
    let counter = 2;
    while (await this.userRepository.findOne({ where: { login } })) {
      login = `${baseLogin}${counter}`;
      counter++;
    }
    return login;
  }

  async createMinimal(data: {
    login: string;
    name: string;
    email: string;
    password: string;
    systemUnitId?: number;
    active?: string;
    birthday?: string;
    forcePasswordChange?: string;
    acceptedTermPolicy?: string;
    failedLoginAttempts?: number;
  }): Promise<SystemUser> {
    const hashedPassword = await bcrypt.hash(data.password, 10);
    const user = this.userRepository.create({
      ...data,
      password: hashedPassword,
    });
    return this.userRepository.save(user) as Promise<SystemUser>;
  }

  async setTemporaryPassword(userId: number, tempPassword: string): Promise<string> {
    const hashed = await bcrypt.hash(tempPassword, 10);
    await this.userRepository.update(userId, {
      password: hashed,
      forcePasswordChange: 'Y',
      failedLoginAttempts: 0,
      lockedUntil: null,
    });
    const user = await this.userRepository.findOne({ where: { id: userId }, select: ['login'] });
    return user?.login || '';
  }

  async syncFromVendedor(userId: number, data: {
    nomeReduzido?: string;
    email?: string;
    status?: string;
    systemUnitId?: number;
    dtNascimento?: Date | string | null;
  }) {
    const update: Partial<SystemUser> = {};

    if (data.nomeReduzido !== undefined) update.name = data.nomeReduzido;
    if (data.email !== undefined) update.email = data.email;
    if (data.systemUnitId !== undefined) update.systemUnitId = data.systemUnitId;
    if (data.status !== undefined) update.active = data.status === 'A' ? 'Y' : 'N';
    if (data.dtNascimento !== undefined) {
      update.birthday = data.dtNascimento
        ? new Date(data.dtNascimento).toISOString().split('T')[0]
        : null;
    }

    if (Object.keys(update).length > 0) {
      await this.userRepository.update(userId, update);
    }
  }

  async remove(id: number) {
    await this.userGroupRepository.delete({ systemUserId: id });
    await this.userUnitRepository.delete({ systemUserId: id });
    await this.userProgramRepository.delete({ systemUserId: id });
    return this.userRepository.delete(id);
  }
}
