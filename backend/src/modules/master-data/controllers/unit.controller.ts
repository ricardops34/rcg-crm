import { Controller, Get, UseGuards } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Filial } from '../../entities/filial.entity';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('master-data/units')
@UseGuards(JwtAuthGuard)
export class UnitController {
  constructor(
    @InjectRepository(Filial)
    private readonly filialRepository: Repository<Filial>,
  ) {}

  @Get()
  async findAll() {
    return this.filialRepository.find({
      where: { status: 'A' },
      order: { nome: 'ASC' }
    });
  }
}
