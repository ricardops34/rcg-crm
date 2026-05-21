import { Controller, Get, UseGuards } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Filial } from '../entities/filial.entity';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';

import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse } from '@nestjs/swagger';

@ApiTags('Master Data / Unidades')
@ApiBearerAuth()
@Controller('master-data/units')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemUnitList')
export class UnitController {
  constructor(
    @InjectRepository(Filial)
    private readonly filialRepository: Repository<Filial>,
  ) {}

  @Get()
  @ApiOperation({ summary: 'Lista todas as unidades (filiais) ativas' })
  @ApiResponse({ status: 200, description: 'Lista de unidades retornada com sucesso' })
  async findAll() {
    return this.filialRepository.find({
      where: { status: 'A' },
      order: { nome: 'ASC' },
    });
  }
}
