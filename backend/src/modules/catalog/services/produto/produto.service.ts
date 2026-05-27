import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Produto } from '../../entities/produto.entity';
import { Categoria } from '../../entities/categoria.entity';

@Injectable()
export class ProdutoService {
  constructor(
    @InjectRepository(Produto)
    private produtoRepository: Repository<Produto>,
    @InjectRepository(Categoria)
    private categoriaRepository: Repository<Categoria>,
    private readonly cls: ClsService,
  ) {}

  async findAll(query?: any): Promise<[Produto[], number]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { status: 'A', ...query };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.produtoRepository.findAndCount({
      relations: ['categoria', 'subCategoria', 'filial'],
      where,
      take: 100,
    });
  }

  async findOne(id: number): Promise<Produto> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { id };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    const produto = await this.produtoRepository.findOne({
      where,
      relations: ['categoria', 'subCategoria', 'filial'],
    });

    if (!produto) {
      throw new NotFoundException(`Produto com ID ${id} não encontrado`);
    }

    return produto;
  }

  async findCategorias(): Promise<Categoria[]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { status: 'A' };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.categoriaRepository.find({ where });
  }

  async save(data: Partial<Produto>): Promise<Produto> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    if (systemUnitId && !data.systemUnitId) {
      data.systemUnitId = systemUnitId;
    }

    const produto = this.produtoRepository.create(data);
    return this.produtoRepository.save(produto);
  }

  async remove(id: number): Promise<void> {
    await this.findOne(id);
    await this.produtoRepository.delete(id);
  }
}
