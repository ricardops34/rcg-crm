import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Produto } from '../../entities/produto.entity';
import { Categoria } from '../../entities/categoria.entity';

@Injectable()
export class ProdutoService {
  constructor(
    @InjectRepository(Produto)
    private produtoRepository: Repository<Produto>,
    @InjectRepository(Categoria)
    private categoriaRepository: Repository<Categoria>,
  ) {}

  async findAll(query?: any): Promise<Produto[]> {
    return this.produtoRepository.find({
      relations: ['categoria', 'subCategoria', 'filial'],
      where: { status: 'A', ...query },
      take: 100 // Limite básico
    });
  }

  async findOne(id: number): Promise<Produto> {
    const produto = await this.produtoRepository.findOne({
      where: { id },
      relations: ['categoria', 'subCategoria', 'filial']
    });

    if (!produto) {
      throw new NotFoundException(`Produto com ID ${id} não encontrado`);
    }

    return produto;
  }

  async findCategorias(): Promise<Categoria[]> {
    return this.categoriaRepository.find({ where: { status: 'A' } });
  }
}
