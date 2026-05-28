import { BadRequestException, ConflictException, Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { IsNull, Repository } from 'typeorm';
import { SystemParameter } from './entities/system-parameter.entity';

@Injectable()
export class ParametersService {
  constructor(
    @InjectRepository(SystemParameter, 'security')
    private readonly parameterRepository: Repository<SystemParameter>,
  ) {}

  async findAll() {
    return this.parameterRepository.find({
      relations: ['systemUnit'],
      order: { systemParameter: 'ASC', systemUnitId: 'ASC' },
    });
  }

  async findOne(id: number) {
    const parameter = await this.parameterRepository.findOne({
      where: { id },
      relations: ['systemUnit'],
    });

    if (!parameter) {
      throw new NotFoundException('Parametro nao encontrado');
    }

    return parameter;
  }

  async save(data: Partial<SystemParameter> & { id?: number }) {
    const payload = {
      ...data,
      systemParameter: data.systemParameter?.trim(),
      systemType: data.systemType?.trim().toUpperCase(),
      systemContent: data.systemContent == null ? null : String(data.systemContent),
      systemSystem: data.systemSystem?.trim().toUpperCase(),
      systemUnitId: data.systemUnitId || null,
    };

    if (!payload.systemParameter) {
      throw new BadRequestException('Nome do parametro e obrigatorio');
    }

    if (!['DATA', 'NUMERO', 'LOGICO', 'CARACTER'].includes(payload.systemType || '')) {
      throw new BadRequestException('Tipo de parametro invalido');
    }

    if (!['S', 'N'].includes(payload.systemSystem || '')) {
      throw new BadRequestException('Indicador de parametro de usuario invalido');
    }

    await this.validateDuplicate(payload);

    const entity = this.parameterRepository.create(payload);
    return this.parameterRepository.save(entity);
  }

  async remove(id: number) {
    const parameter = await this.findOne(id);

    if (parameter.systemSystem === 'N') {
      throw new BadRequestException('Parametros de sistema nao podem ser excluidos');
    }

    return this.parameterRepository.delete(id);
  }

  private async validateDuplicate(data: Partial<SystemParameter> & { id?: number }) {
    const query = this.parameterRepository
      .createQueryBuilder('parameter')
      .where('LOWER(parameter.system_parameter) = LOWER(:systemParameter)', {
        systemParameter: data.systemParameter,
      });

    if (data.systemUnitId) {
      query.andWhere('parameter.system_unit_id = :systemUnitId', {
        systemUnitId: data.systemUnitId,
      });
    } else {
      query.andWhere('parameter.system_unit_id IS NULL');
    }

    if (data.id) {
      query.andWhere('parameter.id <> :id', { id: data.id });
    }

    const existing = await query.getOne();

    if (existing) {
      throw new ConflictException('Ja existe um parametro com este nome para a unidade informada');
    }
  }
}
