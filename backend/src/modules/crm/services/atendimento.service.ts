import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Atendimento } from '../entities/atendimento.entity';
import { AtendimentoTipo } from '../entities/atendimento-tipo.entity';

@Injectable()
export class AtendimentoService {
  constructor(
    @InjectRepository(Atendimento)
    private readonly atendimentoRepository: Repository<Atendimento>,
    @InjectRepository(AtendimentoTipo)
    private readonly atendimentoTipoRepository: Repository<AtendimentoTipo>,
  ) {}

  async findAll(start?: string, end?: string, vendedorId?: number) {
    const query = this.atendimentoRepository
      .createQueryBuilder('atendimento')
      .leftJoinAndSelect('atendimento.tipo', 'tipo')
      .leftJoinAndSelect('atendimento.cliente', 'cliente')
      .leftJoinAndSelect('atendimento.vendedor', 'vendedor')
      .where('atendimento.dtDelete IS NULL')
      .orderBy('atendimento.horarioInicial', 'ASC');

    if (start) {
      query.andWhere('DATE(atendimento.horarioInicial) >= :start', { start });
    }

    if (end) {
      query.andWhere('DATE(atendimento.horarioInicial) <= :end', { end });
    }

    if (vendedorId) {
      query.andWhere('atendimento.vendedorId = :vendedorId', { vendedorId });
    }

    return query.getMany();
  }

  async getTipos() {
    return this.atendimentoTipoRepository
      .createQueryBuilder('tipo')
      .where("COALESCE(tipo.atendimento, 'N') = 'S'")
      .orWhere("COALESCE(tipo.venda, 'N') = 'S'")
      .orderBy('tipo.descricao', 'ASC')
      .getMany();
  }

  async save(
    data: Partial<Atendimento>,
    userId: number,
    vendedorId: number,
  ): Promise<Atendimento> {
    const atendimento = this.atendimentoRepository.create({
      ...data,
      vendedorId,
      status: data.status || 'A',
      userIdCreate: data.id ? data.userIdCreate : userId,
      userIdUpdate: userId,
    });

    return this.atendimentoRepository.save(atendimento);
  }
}
