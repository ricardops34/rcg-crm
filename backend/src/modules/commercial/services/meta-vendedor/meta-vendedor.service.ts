import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { MetaVendedorMes } from '../../entities/meta-vendedor-mes.entity';

@Injectable()
export class MetaVendedorService {
  constructor(
    @InjectRepository(MetaVendedorMes)
    private readonly metaRepository: Repository<MetaVendedorMes>,
    private dataSource: DataSource,
    private readonly cls: ClsService,
  ) {}

  async findAll(page = 1, limit = 10): Promise<[MetaVendedorMes[], number]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = {};
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.metaRepository.findAndCount({
      where,
      relations: ['vendedor'],
      skip: (page - 1) * limit,
      take: limit,
      order: { ano: 'DESC', mes: 'DESC' },
    });
  }

  async findOne(id: number): Promise<MetaVendedorMes | null> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { id };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.metaRepository.findOne({
      where,
      relations: ['vendedor', 'categorias', 'categorias.categoria'],
    });
  }

  async save(data: any): Promise<MetaVendedorMes> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    if (systemUnitId && !data.systemUnitId) {
      data.systemUnitId = systemUnitId;
    }

    return this.metaRepository.save(data);
  }

  async remove(id: number): Promise<void> {
    await this.findOne(id);
    await this.metaRepository.softDelete(id);
  }

  async getSuggestion(
    vendedorId: number,
    month: string,
    year: string,
  ): Promise<number> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId || 1;
    const lastYear = (parseInt(year) - 1).toString();

    // Busca o realizado do ano passado na view de BI
    const result = await this.dataSource.query(
      `SELECT vlr_liquido FROM view_vendedor_venda_mes 
       WHERE vendedor_id = $1 AND ano = $2 AND mes = $3 AND system_unit_id = $4`,
      [vendedorId, lastYear, month, systemUnitId],
    );

    const realizedLastYear =
      result.length > 0 ? parseFloat(result[0].vlr_liquido) : 0;

    // Decisão aprovada: +10% de sugestão padrão
    return realizedLastYear * 1.1;
  }
}
