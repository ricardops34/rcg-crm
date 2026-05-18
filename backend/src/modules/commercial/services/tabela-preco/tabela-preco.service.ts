import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { TabelaPreco } from '../../entities/tabela-preco.entity';
import { TabelaPrecoItem } from '../../entities/tabela-preco-item.entity';

@Injectable()
export class TabelaPrecoService {
  constructor(
    @InjectRepository(TabelaPreco)
    private tabelaPrecoRepository: Repository<TabelaPreco>,
    @InjectRepository(TabelaPrecoItem)
    private tabelaPrecoItemRepository: Repository<TabelaPrecoItem>,
  ) {}

  async findAll(): Promise<TabelaPreco[]> {
    return this.tabelaPrecoRepository.find({ where: { status: 'A' } });
  }

  async findOne(id: number): Promise<TabelaPreco> {
    const table = await this.tabelaPrecoRepository.findOne({ where: { id } });
    if (!table) {
      throw new NotFoundException(`Tabela de preço com ID ${id} não encontrada`);
    }
    return table;
  }

  async getProductPrice(tabelaPrecoId: number, produtoId: number): Promise<number> {
    const item = await this.tabelaPrecoItemRepository.findOne({
      where: {
        tabelaPrecoId,
        produtoId,
        status: 'A'
      }
    });

    if (!item) {
      throw new NotFoundException('Preço não encontrado para este produto nesta tabela');
    }

    return item.preco;
  }
}
