import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { SystemGroup } from './entities/system-group.entity';
import { SystemGroupProgram } from './entities/system-group-program.entity';

@Injectable()
export class GroupsService {
  constructor(
    @InjectRepository(SystemGroup, 'security')
    private groupRepository: Repository<SystemGroup>,
    @InjectRepository(SystemGroupProgram, 'security')
    private groupProgramRepository: Repository<SystemGroupProgram>,
  ) {}

  async findAll() {
    return this.groupRepository.find({
      order: { name: 'ASC' }
    });
  }

  async findOne(id: number) {
    const group = await this.groupRepository.findOne({
      where: { id },
    });
    if (!group) throw new NotFoundException('Grupo não encontrado');

    // Buscar programas vinculados
    const groupPrograms = await this.groupProgramRepository.find({
      where: { systemGroupId: id },
      relations: ['systemProgram'],
    });

    return {
      ...group,
      programs: groupPrograms.map(gp => gp.systemProgramId)
    };
  }

  async create(groupData: any) {
    const { programs, ...data } = groupData;
    const group = this.groupRepository.create(groupData);
    const savedGroup = await this.groupRepository.save(group) as unknown as SystemGroup;

    if (programs && programs.length > 0) {
      const groupPrograms = programs.map(programId => 
        this.groupProgramRepository.create({ systemGroupId: savedGroup.id, systemProgramId: programId })
      );
      await this.groupProgramRepository.save(groupPrograms);
    }

    return this.findOne(savedGroup.id);
  }

  async update(id: number, groupData: any) {
    const { programs, ...data } = groupData;
    await this.groupRepository.update(id, data);

    if (programs) {
      await this.groupProgramRepository.delete({ systemGroupId: id });
      if (programs.length > 0) {
        const newGroupPrograms = programs.map(programId => 
          this.groupProgramRepository.create({ systemGroupId: id, systemProgramId: programId })
        );
        await this.groupProgramRepository.save(newGroupPrograms);
      }
    }

    return this.findOne(id);
  }

  async remove(id: number) {
    await this.groupProgramRepository.delete({ systemGroupId: id });
    return this.groupRepository.delete(id);
  }
}
