import { Controller, Get, Query, UseGuards } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Estado } from '../entities/estado.entity';
import { Municipio } from '../entities/municipio.entity';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';

@Controller('master-data')
@UseGuards(JwtAuthGuard)
export class LocationController {
  constructor(
    @InjectRepository(Estado)
    private readonly estadoRepository: Repository<Estado>,
    @InjectRepository(Municipio)
    private readonly municipioRepository: Repository<Municipio>,
  ) {}

  @Get('estados')
  async getEstados() {
    return this.estadoRepository.find({ order: { sigla: 'ASC' } });
  }

  @Get('municipios')
  async getMunicipios(@Query('estadoId') estadoId?: string) {
    const where = estadoId ? { estadoId: parseInt(estadoId) } : {};
    return this.municipioRepository.find({
      where,
      order: { descricao: 'ASC' },
    });
  }
}
