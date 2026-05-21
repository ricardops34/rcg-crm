import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { TabelaPreco } from '../../entities/tabela-preco.entity';
import { TabelaPrecoItem } from '../../entities/tabela-preco-item.entity';

@Injectable()
export class TabelaPrecoService {
  constructor(
    @InjectRepository(TabelaPreco)
    private tabelaPrecoRepository: Repository<TabelaPreco>,
    @InjectRepository(TabelaPrecoItem)
    private tabelaPrecoItemRepository: Repository<TabelaPrecoItem>,
    private dataSource: DataSource,
  ) {}

  async findAll(page = 1, limit = 100): Promise<[TabelaPreco[], number]> {
    return this.tabelaPrecoRepository.findAndCount({
      skip: (page - 1) * limit,
      take: limit,
      order: { descricao: 'ASC' },
    });
  }

  async findOne(id: number): Promise<TabelaPreco> {
    const table = await this.tabelaPrecoRepository.findOne({ where: { id } });
    if (!table) {
      throw new NotFoundException(
        `Tabela de preço com ID ${id} não encontrada`,
      );
    }
    return table;
  }

  async findItems(tabelaPrecoId: number) {
    await this.findOne(tabelaPrecoId);

    return this.dataSource.query(
      `SELECT
        tpi.id,
        tpi.item,
        tpi.tabela_preco_id as "tabelaPrecoId",
        tpi.produto_id as "produtoId",
        p.descricao as produto_nome,
        tpi.preco,
        tpi.status
       FROM tabela_preco_item tpi
       LEFT JOIN produto p ON p.id = tpi.produto_id
       WHERE tpi.tabela_preco_id = $1
       ORDER BY tpi.item ASC, p.descricao ASC`,
      [tabelaPrecoId],
    );
  }

  async getProductPrice(
    tabelaPrecoId: number,
    produtoId: number,
  ): Promise<number> {
    const item = await this.tabelaPrecoItemRepository.findOne({
      where: {
        tabelaPrecoId,
        produtoId,
        status: 'A',
      },
    });

    if (!item) {
      throw new NotFoundException(
        'Preço não encontrado para este produto nesta tabela',
      );
    }

    return item.preco;
  }

  async save(data: Partial<TabelaPreco>): Promise<TabelaPreco> {
    return this.tabelaPrecoRepository.save(data);
  }

  async remove(id: number): Promise<void> {
    await this.findOne(id);
    await this.tabelaPrecoItemRepository.delete({ tabelaPrecoId: id });
    await this.tabelaPrecoRepository.delete(id);
  }
}
