import { Controller, Get, UseGuards } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Filial } from '../entities/filial.entity';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';

@Controller('master-data/units')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemUnitList')
export class UnitController {
  constructor(
    @InjectRepository(Filial)
    private readonly filialRepository: Repository<Filial>,
  ) {}

  @Get()
  async findAll() {
    return this.filialRepository.find({
      where: { status: 'A' },
      order: { nome: 'ASC' },
    });
  }
}
