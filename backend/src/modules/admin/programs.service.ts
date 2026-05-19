import { Injectable } from '@nestjs/common';
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
}
