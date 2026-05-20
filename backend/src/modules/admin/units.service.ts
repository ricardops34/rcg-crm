import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { SystemUnit } from './entities/system-unit.entity';

@Injectable()
export class UnitsService {
  constructor(
    @InjectRepository(SystemUnit, 'security')
    private unitRepository: Repository<SystemUnit>,
  ) {}

  async findAll() {
    return this.unitRepository.find({ order: { name: 'ASC' } });
  }

  async findOne(id: number) {
    const unit = await this.unitRepository.findOne({ where: { id } });
    if (!unit) throw new NotFoundException('Unidade não encontrada');
    return unit;
  }

  async save(data: any) {
    const unit = this.unitRepository.create(data);
    return this.unitRepository.save(unit);
  }

  async remove(id: number) {
    return this.unitRepository.delete(id);
  }
}
