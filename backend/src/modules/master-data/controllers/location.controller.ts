import { Controller, Get, Query, UseGuards } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Estado } from '../entities/estado.entity';
import { Municipio } from '../entities/municipio.entity';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';

import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse } from '@nestjs/swagger';
import { EstadoResponseDto, MunicipioResponseDto } from '../dto/location.dto';

@ApiTags('Master Data / Localização')
@ApiBearerAuth()
@Controller('master-data')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('MunicipioList')
export class LocationController {
  constructor(
    @InjectRepository(Estado)
    private readonly estadoRepository: Repository<Estado>,
    @InjectRepository(Municipio)
    private readonly municipioRepository: Repository<Municipio>,
  ) {}

  @Get('estados')
  @ApiOperation({ summary: 'Lista todas as Unidades Federativas (Estados)' })
  @ApiResponse({ status: 200, type: [EstadoResponseDto] })
  async getEstados() {
    return this.estadoRepository.find({ order: { sigla: 'ASC' } });
  }

  @Get('municipios')
  @ApiOperation({ summary: 'Lista municípios, opcionalmente filtrados por estado' })
  @ApiResponse({ status: 200, type: [MunicipioResponseDto] })
  async getMunicipios(@Query('estadoId') estadoId?: string) {
    const where = estadoId ? { estadoId: parseInt(estadoId) } : {};
    return this.municipioRepository.find({
      where,
      order: { descricao: 'ASC' },
    });
  }
}
