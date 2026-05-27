import { Injectable, ForbiddenException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, In, DeepPartial } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Cliente } from '../../entities/cliente.entity';

export type CreateClienteInput = Omit<Partial<Cliente>, 'nascimento'> & {
  nascimento?: string | Date;
};

@Injectable()
export class ClienteService {
  constructor(
    @InjectRepository(Cliente)
    private readonly clienteRepository: Repository<Cliente>,
    private readonly cls: ClsService,
  ) {}

  async findAll(page = 1, limit = 10): Promise<[Cliente[], number]> {
    const user = this.cls.get('user');
    const where: any = {};

    // 1. Filtro de Multitenancy (system_unit_id) obrigatório para todas as buscas
    if (user?.unitId) {
      where.systemUnitId = user.unitId;
    }

    // 2. Hierarquia de Acesso Baseada em Perfis (Role-Based)
    const roles = user?.roles || [];
    
    if (!roles.includes('ADMIN') && !roles.includes('GERENTE')) {
      if (roles.includes('SUPERVISOR') && user.managedVendedorIds?.length > 0) {
        // Supervisor vê os seus próprios e os dos seus vendedores
        const sellerIds = [...user.managedVendedorIds];
        if (user.vendedorId) sellerIds.push(user.vendedorId);
        where.vendedorId = In(sellerIds);
      } else if (roles.includes('VENDEDOR') && user.vendedorId) {
        // Vendedor vê apenas os seus
        where.vendedorId = user.vendedorId;
      } else {
        // Papel desconhecido ou sem vínculo: não vê nada por segurança
        return [[], 0];
      }
    }

    return this.clienteRepository.findAndCount({
      where,
      relations: ['vendedor', 'filial'],
      skip: (page - 1) * limit,
      take: limit,
      order: { razao: 'ASC' },
    });
  }

  async findOne(id: number): Promise<Cliente | null> {
    const user = this.cls.get('user');
    const where: any = { id };

    // Filtro de Multitenancy (system_unit_id) obrigatório
    if (user?.unitId) {
      where.systemUnitId = user.unitId;
    }

    const cliente = await this.clienteRepository.findOne({
      where,
      relations: [
        'vendedor',
        'filial',
        'condicaoPagamento',
        'tabelaPreco',
        'contatos',
        'contatos.tipoContato',
      ],
    });

    if (!cliente) return null;

    // Validar hierarquia no acesso individual (Role-Based)
    const roles = user?.roles || [];

    if (!roles.includes('ADMIN') && !roles.includes('GERENTE')) {
      if (roles.includes('SUPERVISOR')) {
        const sellerIds = [...(user.managedVendedorIds || [])];
        if (user.vendedorId) sellerIds.push(user.vendedorId);
        if (!sellerIds.includes(cliente.vendedorId)) {
          throw new ForbiddenException('Acesso negado a este cliente (fora da sua equipe)');
        }
      } else if (roles.includes('VENDEDOR') && cliente.vendedorId !== user.vendedorId) {
        throw new ForbiddenException('Você não tem permissão para acessar este cliente');
      }
    }

    return cliente;
  }

  async create(data: CreateClienteInput): Promise<Cliente> {
    const user = this.cls.get('user');
    
    // Injetar o system_unit_id automaticamente na criação do cliente
    const clienteData = {
      ...data,
      systemUnitId: user?.unitId || 1,
    };

    const cliente = this.clienteRepository.create(clienteData as DeepPartial<Cliente>);
    return this.clienteRepository.save(cliente);
  }

  async update(id: number, data: CreateClienteInput): Promise<Cliente | null> {
    const cliente = await this.findOne(id);
    if (!cliente) {
      throw new ForbiddenException('Cliente não encontrado ou acesso negado para esta unidade');
    }

    // Impede a troca forçada de tenant no payload de update
    const { systemUnitId, ...updatePayload } = data as any;

    await this.clienteRepository.update(id, updatePayload);
    return this.findOne(id);
  }

  async remove(id: number): Promise<void> {
    const cliente = await this.findOne(id);
    if (!cliente) {
      throw new ForbiddenException('Cliente não encontrado ou acesso negado para esta unidade');
    }
    
    await this.clienteRepository.delete(id);
  }
}
