import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Vendedor } from '../../entities/vendedor.entity';

@Injectable()
export class VendedorService {
  constructor(
    @InjectRepository(Vendedor)
    private readonly vendedorRepository: Repository<Vendedor>,
  ) {}

  async findAll(page = 1, limit = 10): Promise<[Vendedor[], number]> {
    return this.vendedorRepository.findAndCount({
      relations: ['filial'],
      skip: (page - 1) * limit,
      take: limit,
      order: { nome: 'ASC' },
    });
  }

  async findOne(id: number): Promise<Vendedor> {
    return this.vendedorRepository.findOne({ 
      where: { id },
      relations: ['filial']
    });
  }

  async save(data: any): Promise<Vendedor> {
    return this.vendedorRepository.save(data);
  }
}
