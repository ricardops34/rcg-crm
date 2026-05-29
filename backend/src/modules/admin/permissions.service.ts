import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, In, Not, IsNull } from 'typeorm';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemGroupProgram } from './entities/system-group-program.entity';
import { SystemProgram } from './entities/system-program.entity';
import { SystemUserProgram } from './entities/system-user-program.entity';
import { SystemUser } from './entities/system-user.entity';

@Injectable()
export class PermissionsService {
  constructor(
    @InjectRepository(SystemUserGroup, 'security')
    private userGroupRepository: Repository<SystemUserGroup>,
    @InjectRepository(SystemGroupProgram, 'security')
    private groupProgramRepository: Repository<SystemGroupProgram>,
    @InjectRepository(SystemProgram, 'security')
    private programRepository: Repository<SystemProgram>,
    @InjectRepository(SystemUserProgram, 'security')
    private userProgramRepository: Repository<SystemUserProgram>,
    @InjectRepository(SystemUser, 'security')
    private systemUserRepository: Repository<SystemUser>,
  ) {}

  private async isSuperAdmin(userId: number): Promise<boolean> {
    const user = await this.systemUserRepository.findOne({ where: { id: userId } });
    return user?.login?.toLowerCase() === 'admin';
  }

  async isAdminUser(userId: number): Promise<boolean> {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });
    const adminByGroup = userGroups.some(
      (ug) =>
        ug.systemGroup &&
        (ug.systemGroup.role?.toUpperCase() === 'ADMIN' ||
          ug.systemGroup.name?.toUpperCase().includes('ADMIN')),
    );
    if (adminByGroup) return true;
    return this.isSuperAdmin(userId);
  }

  async hasPermission(userId: number, controller: string): Promise<boolean> {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });

    const isAdmin =
      userGroups.some(
        (ug) =>
          ug.systemGroup &&
          (ug.systemGroup.role?.toUpperCase() === 'ADMIN' ||
            ug.systemGroup.name?.toUpperCase().includes('ADMIN')),
      ) || (await this.isSuperAdmin(userId));
    if (isAdmin) return true;

    // Verificar Permissões Diretas do Usuário
    const userPermission = await this.userProgramRepository.findOne({
      where: {
        systemUserId: userId,
        systemProgram: { controller: controller },
      },
      relations: ['systemProgram'],
    });
    if (userPermission) return true;

    // Verificar Permissões do Grupo
    const groupIds = userGroups.map((ug) => ug.systemGroupId);
    if (groupIds.length === 0) return false;

    const groupPermission = await this.groupProgramRepository.findOne({
      where: {
        systemGroupId: In(groupIds),
        systemProgram: { controller: controller },
      },
      relations: ['systemProgram'],
    });

    return !!groupPermission;
  }

  async getUserPrograms(userId: number) {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });

    const isAdmin =
      userGroups.some(
        (ug) =>
          ug.systemGroup &&
          (ug.systemGroup.role?.toUpperCase() === 'ADMIN' ||
            ug.systemGroup.name?.toUpperCase().includes('ADMIN')),
      ) || (await this.isSuperAdmin(userId));

    if (isAdmin) {
      // ADMIN: Retorna apenas programas vinculados a um módulo (exclui legados sem módulo)
      const allImplemented = await this.programRepository.find({
        where: { systemModuleId: Not(IsNull()) },
        relations: ['systemModule'],
      });
      return allImplemented.map(p => ({
          ...p,
          permissions: { view: true, insert: true, update: true, delete: true }
      }));
    }

    const groupIds = userGroups.map((ug) => ug.systemGroupId);
    
    // Buscar Programas via Grupo
    const groupPrograms = await this.groupProgramRepository.find({
      where: { systemGroupId: In(groupIds.length ? groupIds : [-1]) },
      relations: ['systemProgram'],
    });

    // Buscar Programas via Usuário Direto
    const userPrograms = await this.userProgramRepository.find({
      where: { systemUserId: userId },
      relations: ['systemProgram'],
    });

    const programsMap = new Map();

    const processProgram = (p: any, actionsRaw: string | null) => {
      if (!p) return;
      const currentActions = actionsRaw ? JSON.parse(actionsRaw) : { view: true, insert: false, update: false, delete: false };
      const existing = programsMap.get(p.id);
      if (existing) {
        existing.permissions = {
          view: existing.permissions.view || currentActions.view,
          insert: existing.permissions.insert || currentActions.insert,
          update: existing.permissions.update || currentActions.update,
          delete: existing.permissions.delete || currentActions.delete,
        };
      } else {
        programsMap.set(p.id, { ...p, permissions: currentActions });
      }
    };

    groupPrograms.forEach(gp => processProgram(gp.systemProgram, gp.actions));
    userPrograms.forEach(up => processProgram(up.systemProgram, null));

    return Array.from(programsMap.values());
  }

  async getUserRoles(userId: number): Promise<string[]> {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });

    const roles = userGroups
      .map((ug) => {
        const roleStr = (ug.systemGroup?.role || '').toUpperCase();
        if (!roleStr && ug.systemGroup?.name?.toUpperCase().includes('ADMIN')) {
          return 'ADMIN';
        }
        return roleStr;
      })
      .filter((r) => r);

    if (roles.length === 0 && await this.isSuperAdmin(userId)) {
      return ['ADMIN'];
    }

    return roles;
  }

  async getMenuStructure(userId: number) {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });

    const isAdmin =
      userGroups.some(
        (ug) =>
          ug.systemGroup &&
          (ug.systemGroup.role?.toUpperCase() === 'ADMIN' ||
            ug.systemGroup.name?.toUpperCase().includes('ADMIN')),
      ) || (await this.isSuperAdmin(userId));

    const menuMap = new Map();

    if (isAdmin) {
      // ADMIN: Acesso Total apenas a rotinas vinculadas a um módulo (exclui legados sem módulo)
      const allImplemented = await this.programRepository.find({
        where: { systemModuleId: Not(IsNull()) },
        relations: ['systemModule'],
      });

      allImplemented.forEach((prog: any) => {
        // Pula programas legados sem módulo atribuído (ex: rotinas do sistema antigo)
        if (!prog.systemModule) return;

        const mod = prog.systemModule;
        if (!menuMap.has(mod.id)) {
          menuMap.set(mod.id, { id: mod.id, label: mod.name, icon: mod.icon || 'po-icon-more', order: mod.order, subItems: [] });
        }
        menuMap.get(mod.id).subItems.push({
          id: prog.id,
          label: prog.name,
          action: prog.controller,
          order: prog.order,
          icon: prog.icon || 'po-icon-circle',
          permissions: { view: true, insert: true, update: true, delete: true }
        });
      });
    } else {
      // Usuário Comum: Soma de Grupos + Direto no Usuário
      const programs = await this.getUserPrograms(userId);
      
      for (const prog of programs) {
        if (!prog.permissions?.view) continue;

        const fullProg = await this.programRepository.findOne({ 
          where: { id: prog.id }, 
          relations: ['systemModule'] 
        });

        const mod = fullProg?.systemModule;
        if (!mod) continue; // Ignora programas legados sem módulo no novo menu
        
        if (!menuMap.has(mod.id)) {
          menuMap.set(mod.id, { id: mod.id, label: mod.name, icon: mod.icon || 'po-icon-more', order: mod.order, subItems: [] });
        }
        
        menuMap.get(mod.id).subItems.push({
          id: prog.id,
          label: prog.name,
          action: prog.controller,
          order: prog.order,
          icon: prog.icon || 'po-icon-circle',
          permissions: prog.permissions
        });
      }
    }

    return Array.from(menuMap.values())
      .sort((a, b) => a.order - b.order)
      .map(mod => ({
        ...mod,
        subItems: mod.subItems.sort((a: any, b: any) => a.order - b.order)
      }));
  }
}
