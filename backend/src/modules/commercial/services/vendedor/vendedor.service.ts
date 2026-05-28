import { Injectable, ForbiddenException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Vendedor } from '../../entities/vendedor.entity';

@Injectable()
export class VendedorService {
  constructor(
    @InjectRepository(Vendedor)
    private readonly vendedorRepository: Repository<Vendedor>,
    private readonly cls: ClsService,
  ) {}

  async findAll(
    page = 1,
    limit = 10,
    filters?: { status?: string; dashboard?: string; supervisor?: string; order?: string },
  ): Promise<[Vendedor[], number]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;
    const query = this.vendedorRepository
      .createQueryBuilder('vendedor')
      .leftJoinAndSelect('vendedor.filial', 'filial');

    if (systemUnitId) {
      query.andWhere('vendedor.systemUnitId = :systemUnitId', { systemUnitId });
    }

    if (filters?.status) {
      query.andWhere('vendedor.status = :status', { status: filters.status });
    }

    if (filters?.dashboard) {
      query.andWhere('vendedor.dashboard = :dashboard', { dashboard: filters.dashboard });
    }

    if (filters?.supervisor) {
      query.andWhere('vendedor.supervisor = :supervisor', { supervisor: filters.supervisor });
    }

    // Hierarquia de Acesso Baseada em Perfis (Role-Based)
    const roles = user?.roles || [];

    if (!roles.includes('ADMIN') && !roles.includes('GERENTE')) {
      if (roles.includes('SUPERVISOR') && user.managedVendedorIds?.length > 0) {
        const sellerIds = [...user.managedVendedorIds];
        if (user.vendedorId) sellerIds.push(user.vendedorId);
        query.andWhere('vendedor.id IN (:...sellerIds)', { sellerIds: [...new Set(sellerIds)] });
      } else if (roles.includes('VENDEDOR') && user.vendedorId) {
        query.andWhere('vendedor.id = :vendedorId', { vendedorId: user.vendedorId });
      } else {
        return [[], 0];
      }
    }

    const sortMap: Record<string, string> = {
      id: 'vendedor.id',
      codErp: 'vendedor.codErp',
      nome: 'vendedor.nome',
      email: 'vendedor.email',
      filialRazao: 'filial.razao',
      status: 'vendedor.status',
      celular: 'vendedor.celular',
      supervisor: 'vendedor.supervisor',
    };

    const orderValue = filters?.order || 'nome';
    const isDescending = orderValue.startsWith('-');
    const orderKey = isDescending ? orderValue.slice(1) : orderValue;
    const orderColumn = sortMap[orderKey] || 'vendedor.nome';

    query
      .orderBy(orderColumn, isDescending ? 'DESC' : 'ASC')
      .skip((page - 1) * limit)
      .take(limit);

    return query.getManyAndCount();
  }

  async findOne(id: number): Promise<Vendedor | null> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    // Validar hierarquia no acesso individual (Role-Based)
    const roles = user?.roles || [];

    if (!roles.includes('ADMIN') && !roles.includes('GERENTE')) {
      if (roles.includes('SUPERVISOR')) {
        const sellerIds = [...(user.managedVendedorIds || [])];
        if (user.vendedorId) sellerIds.push(user.vendedorId);
        if (!sellerIds.includes(id)) {
          throw new ForbiddenException('Acesso negado a este vendedor (fora da sua equipe)');
        }
      } else if (roles.includes('VENDEDOR') && user.vendedorId !== id) {
        throw new ForbiddenException('Acesso negado ao perfil de outro vendedor');
      }
    }

    const where: any = { id };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.vendedorRepository.findOne({
      where,
      relations: ['filial'],
    });
  }

  async save(data: any): Promise<Vendedor> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    if (systemUnitId && !data.systemUnitId) {
      data.systemUnitId = systemUnitId;
    }

    return this.vendedorRepository.save(data);
  }

  async remove(id: number): Promise<void> {
    await this.findOne(id);
    await this.vendedorRepository.delete(id);
  }
}
