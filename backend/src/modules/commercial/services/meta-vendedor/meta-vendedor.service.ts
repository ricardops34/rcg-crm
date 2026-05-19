import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { MetaVendedorMes } from '../../entities/meta-vendedor-mes.entity';

@Injectable()
export class MetaVendedorService {
  constructor(
    @InjectRepository(MetaVendedorMes)
    private readonly metaRepository: Repository<MetaVendedorMes>,
    private dataSource: DataSource,
  ) {}

  async findAll(page = 1, limit = 10): Promise<[MetaVendedorMes[], number]> {
    return this.metaRepository.findAndCount({
      relations: ['vendedor'],
      skip: (page - 1) * limit,
      take: limit,
      order: { ano: 'DESC', mes: 'DESC' },
    });
  }

  async findOne(id: number): Promise<MetaVendedorMes | null> {
    return this.metaRepository.findOne({
      where: { id },
      relations: ['vendedor', 'categorias', 'categorias.categoria'],
    });
  }

  async save(data: any): Promise<MetaVendedorMes> {
    return this.metaRepository.save(data);
  }

  async getSuggestion(
    vendedorId: number,
    month: string,
    year: string,
  ): Promise<number> {
    const lastYear = (parseInt(year) - 1).toString();

    // Busca o realizado do ano passado na view de BI
    const result = await this.dataSource.query(
      `SELECT vlr_liquido FROM view_vendedor_venda_mes 
       WHERE vendedor_id = $1 AND ano = $2 AND mes = $3`,
      [vendedorId, lastYear, month],
    );

    const realizedLastYear =
      result.length > 0 ? parseFloat(result[0].vlr_liquido) : 0;

    // Decisão aprovada: +10% de sugestão padrão
    return realizedLastYear * 1.1;
  }
}
