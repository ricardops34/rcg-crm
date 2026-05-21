import { Injectable, ForbiddenException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, In } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Vendedor } from '../../entities/vendedor.entity';

@Injectable()
export class VendedorService {
  constructor(
    @InjectRepository(Vendedor)
    private readonly vendedorRepository: Repository<Vendedor>,
    private readonly cls: ClsService,
  ) {}

  async findAll(page = 1, limit = 10): Promise<[Vendedor[], number]> {
    const user = this.cls.get('user');
    const where: any = {};

    // Hierarquia de Acesso Baseada em Perfis (Role-Based)
    const roles = user?.roles || [];

    if (!roles.includes('ADMIN') && !roles.includes('GERENTE')) {
      if (roles.includes('SUPERVISOR') && user.managedVendedorIds?.length > 0) {
        // Supervisor vê a si mesmo e sua equipe
        const sellerIds = [...user.managedVendedorIds];
        if (user.vendedorId) sellerIds.push(user.vendedorId);
        where.id = In(sellerIds);
      } else if (roles.includes('VENDEDOR') && user.vendedorId) {
        // Vendedor vê apenas a si mesmo
        where.id = user.vendedorId;
      } else {
        return [[], 0];
      }
    }

    return this.vendedorRepository.findAndCount({
      where,
      relations: ['filial'],
      skip: (page - 1) * limit,
      take: limit,
      order: { nome: 'ASC' },
    });
  }

  async findOne(id: number): Promise<Vendedor | null> {
    const user = this.cls.get('user');

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

    return this.vendedorRepository.findOne({
      where: { id },
      relations: ['filial'],
    });
  }

  async save(data: any): Promise<Vendedor> {
    return this.vendedorRepository.save(data);
  }

  async remove(id: number): Promise<void> {
    await this.findOne(id);
    await this.vendedorRepository.delete(id);
  }
}
