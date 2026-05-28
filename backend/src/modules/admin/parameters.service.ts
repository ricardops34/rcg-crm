import { BadRequestException, ConflictException, Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { IsNull, Repository } from 'typeorm';
import { SystemParameter } from './entities/system-parameter.entity';
import { SystemUnit } from './entities/system-unit.entity';

@Injectable()
export class ParametersService {
  constructor(
    @InjectRepository(SystemParameter, 'security')
    private readonly parameterRepository: Repository<SystemParameter>,
    @InjectRepository(SystemUnit, 'security')
    private readonly unitRepository: Repository<SystemUnit>,
  ) {}

  async findAll() {
    return this.parameterRepository.find({
      relations: ['systemUnit'],
      order: { parameter: 'ASC', systemUnitId: 'ASC' },
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
      parameter: data.parameter?.trim(),
      type: data.type?.trim().toUpperCase(),
      content: data.content == null ? null : String(data.content),
      system: data.system?.trim().toUpperCase(),
      systemUnitId: data.systemUnitId || null,
      description: data.description == null ? null : String(data.description),
    };

    if (!payload.parameter) {
      throw new BadRequestException('Nome do parametro e obrigatorio');
    }

    if (!['DATA', 'NUMERO', 'LOGICO', 'CARACTER'].includes(payload.type || '')) {
      throw new BadRequestException('Tipo de parametro invalido');
    }

    if (!['S', 'N'].includes(payload.system || '')) {
      throw new BadRequestException('Indicador de parametro de usuario invalido');
    }

    // Comportamento UPSERT: Se nao forneceu ID, verifica se ja existe por nome + unidade
    if (!payload.id) {
      const query = this.parameterRepository
        .createQueryBuilder('parameter')
        .where('LOWER(parameter.parameter) = LOWER(:parameter)', {
          parameter: payload.parameter,
        });

      if (payload.systemUnitId) {
        query.andWhere('parameter.system_unit_id = :systemUnitId', {
          systemUnitId: payload.systemUnitId,
        });
      } else {
        query.andWhere('parameter.system_unit_id IS NULL');
      }

      const existing = await query.getOne();
      if (existing) {
        payload.id = existing.id;
        // Preserva os dados originais caso nao tenham sido preenchidos no novo payload
        payload.type = payload.type || existing.type;
        payload.system = payload.system || existing.system;
        payload.description = payload.description || existing.description;
      }
    } else {
      // Se forneceu ID, valida duplicidade garantindo que nao colida com outro registro
      await this.validateDuplicate(payload);
    }

    const entity = this.parameterRepository.create(payload);
    return this.parameterRepository.save(entity);
  }

  async remove(id: number) {
    const parameter = await this.findOne(id);

    if (parameter.system === 'N') {
      throw new BadRequestException('Parametros de sistema nao podem ser excluidos');
    }

    return this.parameterRepository.delete(id);
  }

  async splitByUnit(id: number) {
    const parameter = await this.parameterRepository.findOne({
      where: { id },
    });

    if (!parameter) {
      throw new NotFoundException('Parametro nao encontrado');
    }

    if (parameter.systemUnitId !== null) {
      throw new BadRequestException('Este parametro ja esta definido para uma unidade especifica');
    }

    const units = await this.unitRepository.find();
    if (units.length === 0) {
      throw new BadRequestException('Nenhuma unidade cadastrada no sistema para criar as copias');
    }

    await this.parameterRepository.manager.transaction(async (transactionalEntityManager) => {
      for (const unit of units) {
        const copy = transactionalEntityManager.create(SystemParameter, {
          parameter: parameter.parameter,
          type: parameter.type,
          content: parameter.content,
          system: parameter.system,
          description: parameter.description,
          systemUnitId: unit.id,
        });
        await transactionalEntityManager.save(SystemParameter, copy);
      }

      await transactionalEntityManager.delete(SystemParameter, id);
    });

    return { success: true };
  }

  private async validateDuplicate(data: Partial<SystemParameter> & { id?: number }) {
    const query = this.parameterRepository
      .createQueryBuilder('parameter')
      .where('LOWER(parameter.parameter) = LOWER(:parameter)', {
        parameter: data.parameter,
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
