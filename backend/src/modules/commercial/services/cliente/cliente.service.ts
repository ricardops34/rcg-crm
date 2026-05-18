import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Cliente } from '../../entities/cliente.entity';

@Injectable()
export class ClienteService {
  constructor(
    @InjectRepository(Cliente)
    private readonly clienteRepository: Repository<Cliente>,
  ) {}

  async findAll(page = 1, limit = 10): Promise<[Cliente[], number]> {
    return this.clienteRepository.findAndCount({
      relations: ['vendedor', 'filial'],
      skip: (page - 1) * limit,
      take: limit,
      order: { razao: 'ASC' },
    });
  }

  async findOne(id: number): Promise<Cliente | null> {
    return this.clienteRepository.findOne({ 
      where: { id },
      relations: ['vendedor', 'filial', 'condicaoPagamento', 'tabelaPreco', 'contatos', 'contatos.tipoContato']
    });
  }

  async create(data: Partial<Cliente>): Promise<Cliente> {
    const cliente = this.clienteRepository.create(data);
    return this.clienteRepository.save(cliente);
  }

  async update(id: number, data: Partial<Cliente>): Promise<Cliente | null> {
    await this.clienteRepository.update(id, data);
    return this.findOne(id);
  }

  async remove(id: number): Promise<void> {
    await this.clienteRepository.delete(id);
  }
}
