import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { SystemProgram } from './entities/system-program.entity';

@Injectable()
export class ProgramsService {
  constructor(
    @InjectRepository(SystemProgram, 'security')
    private programRepository: Repository<SystemProgram>,
  ) {}

  async findAll() {
    return this.programRepository.find({
      order: { name: 'ASC' },
    });
  }

  async findOne(id: number) {
    const program = await this.programRepository.findOne({ where: { id } });
    if (!program) throw new NotFoundException('Rotina não encontrada');
    return program;
  }

  async create(data: any) {
    const program = this.programRepository.create(data);
    return this.programRepository.save(program);
  }

  async update(id: number, data: any) {
    await this.programRepository.update(id, data);
    return this.findOne(id);
  }

  async remove(id: number) {
    return this.programRepository.delete(id);
  }
}
