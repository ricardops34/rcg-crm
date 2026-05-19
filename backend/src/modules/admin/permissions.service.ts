import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, In } from 'typeorm';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemGroupProgram } from './entities/system-group-program.entity';
import { SystemProgram } from './entities/system-program.entity';

@Injectable()
export class PermissionsService {
  constructor(
    @InjectRepository(SystemUserGroup, 'security')
    private userGroupRepository: Repository<SystemUserGroup>,
    @InjectRepository(SystemGroupProgram, 'security')
    private groupProgramRepository: Repository<SystemGroupProgram>,
    @InjectRepository(SystemProgram, 'security')
    private programRepository: Repository<SystemProgram>,
  ) {}

  async hasPermission(userId: number, controller: string): Promise<boolean> {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });

    // Se o usuário tiver papel de 'ADMIN', ele tem acesso total
    const isAdmin = userGroups.some(
      (ug) => ug.systemGroup && ug.systemGroup.role?.toUpperCase() === 'ADMIN',
    );
    if (isAdmin) {
      return true;
    }

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
      relations: ['systemGroup'],
    });

    // Se for Admin, retorna todos os programas cadastrados no sistema
    const isAdmin = userGroups.some(
      (ug) => ug.systemGroup && ug.systemGroup.role?.toUpperCase() === 'ADMIN',
    );
    if (isAdmin) {
      return this.programRepository.find();
    }

    const groupIds = userGroups.map((ug) => ug.systemGroupId);
    if (groupIds.length === 0) return [];

    const groupPrograms = await this.groupProgramRepository.find({
      where: { systemGroupId: In(groupIds) },
      relations: ['systemProgram'],
    });

    // Remover duplicatas e formatar com ações
    const programs = new Map();
    groupPrograms.forEach((gp) => {
      const existing = programs.get(gp.systemProgramId);
      const currentActions = gp.actions ? JSON.parse(gp.actions) : { view: true };
      
      if (existing) {
        // Merge de ações se o usuário estiver em múltiplos grupos com a mesma rotina
        existing.permissions = {
          view: existing.permissions.view || currentActions.view,
          insert: existing.permissions.insert || currentActions.insert,
          update: existing.permissions.update || currentActions.update,
          delete: existing.permissions.delete || currentActions.delete,
        };
      } else {
        programs.set(gp.systemProgramId, {
          ...gp.systemProgram,
          permissions: currentActions
        });
      }
    });

    return Array.from(programs.values());
  }

  async getMenuStructure(userId: number) {
    const userGroups = await this.userGroupRepository.find({
      where: { systemUserId: userId },
      relations: ['systemGroup'],
    });

    const isAdmin = userGroups.some(
      (ug) => ug.systemGroup && ug.systemGroup.role?.toUpperCase() === 'ADMIN',
    );

    let groupIds: number[];
    if (isAdmin) {
      const allGroups = await this.userGroupRepository.manager.getRepository('SystemGroup').find();
      groupIds = allGroups.map(g => (g as any).id);
    } else {
      groupIds = userGroups.map((ug) => ug.systemGroupId);
    }

    if (groupIds.length === 0) return [];

    // Buscar programas permitidos com seus módulos
    const groupPrograms = await this.groupProgramRepository.find({
      where: { systemGroupId: In(groupIds) },
      relations: ['systemProgram', 'systemProgram.systemModule'],
    });

    // Organizar em estrutura hierárquica ordenada
    const menuMap = new Map();

    groupPrograms.forEach((gp) => {
      const prog = gp.systemProgram;
      const mod = prog.systemModule || { id: 0, name: 'Outros', icon: 'po-icon-more', order: 99 };

      if (!menuMap.has(mod.id)) {
        menuMap.set(mod.id, {
          id: mod.id,
          label: mod.name,
          icon: mod.icon,
          order: mod.order,
          subItems: []
        });
      }

      const moduleEntry = menuMap.get(mod.id);
      const currentActions = gp.actions ? JSON.parse(gp.actions) : { view: true };

      // Apenas adicionar se tiver permissão de visualizar
      if (currentActions.view) {
        const existingProg = moduleEntry.subItems.find((p: any) => p.id === prog.id);
        if (!existingProg) {
          moduleEntry.subItems.push({
            id: prog.id,
            label: prog.name,
            action: prog.controller, // Controller usado para roteamento no front
            order: prog.order,
            icon: prog.icon || 'po-icon-circle',
            permissions: currentActions
          });
        } else {
          // Merge permissions
          existingProg.permissions = {
            view: existingProg.permissions.view || currentActions.view,
            insert: existingProg.permissions.insert || currentActions.insert,
            update: existingProg.permissions.update || currentActions.update,
            delete: existingProg.permissions.delete || currentActions.delete,
          };
        }
      }
    });

    // Converter para array e ordenar (Módulos e depois Itens Internos)
    return Array.from(menuMap.values())
      .sort((a, b) => a.order - b.order)
      .map(mod => ({
        ...mod,
        subItems: mod.subItems.sort((a: any, b: any) => a.order - b.order)
      }));
  }
}
