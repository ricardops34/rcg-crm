import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { SystemModule } from './entities/system-module.entity';

@Injectable()
export class ModulesService {
  constructor(
    @InjectRepository(SystemModule, 'security')
    private moduleRepository: Repository<SystemModule>,
  ) {}

  async findAll() {
    return this.moduleRepository.find({ order: { order: 'ASC' } });
  }

  async findOne(id: number) {
    const module = await this.moduleRepository.findOne({ where: { id } });
    if (!module) throw new NotFoundException('Módulo não encontrado');
    return module;
  }

  async save(data: any) {
    const module = this.moduleRepository.create(data);
    return this.moduleRepository.save(module);
  }

  async remove(id: number) {
    return this.moduleRepository.delete(id);
  }
}
