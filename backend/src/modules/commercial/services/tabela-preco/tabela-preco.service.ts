import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { ClsService } from 'nestjs-cls';
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
    private readonly cls: ClsService,
  ) {}

  async findAll(page = 1, limit = 100): Promise<[TabelaPreco[], number]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = {};
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.tabelaPrecoRepository.findAndCount({
      where,
      skip: (page - 1) * limit,
      take: limit,
      order: { descricao: 'ASC' },
    });
  }

  async findOne(id: number): Promise<TabelaPreco> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { id };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    const table = await this.tabelaPrecoRepository.findOne({ where });
    if (!table) {
      throw new NotFoundException(
        `Tabela de preço com ID ${id} não encontrada`,
      );
    }
    return table;
  }

  async findItems(tabelaPrecoId: number) {
    await this.findOne(tabelaPrecoId);
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId || 1;

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
       WHERE tpi.tabela_preco_id = $1 AND tpi.system_unit_id = $2
       ORDER BY tpi.item ASC, p.descricao ASC`,
      [tabelaPrecoId, systemUnitId],
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
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    if (systemUnitId && !data.systemUnitId) {
      data.systemUnitId = systemUnitId;
    }

    return this.tabelaPrecoRepository.save(data);
  }

  async remove(id: number): Promise<void> {
    await this.findOne(id);
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const deleteWhere: any = { tabelaPrecoId: id };
    if (systemUnitId) deleteWhere.systemUnitId = systemUnitId;

    await this.tabelaPrecoItemRepository.delete(deleteWhere);
    await this.tabelaPrecoRepository.delete(id);
  }
}
