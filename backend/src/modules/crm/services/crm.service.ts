import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, DataSource } from 'typeorm';
import { Atendimento } from '../entities/atendimento.entity';
import { AtendimentoTipo } from '../entities/atendimento-tipo.entity';

@Injectable()
export class CrmService {
  constructor(
    @InjectRepository(Atendimento)
    private atendimentoRepository: Repository<Atendimento>,
    @InjectRepository(AtendimentoTipo)
    private tipoRepository: Repository<AtendimentoTipo>,
    private dataSource: DataSource,
  ) {}

  async findAll() {
    return this.atendimentoRepository.find({
      relations: ['tipo', 'vendedor', 'cliente'],
      order: { horarioInicial: 'DESC' },
      take: 100,
    });
  }

  async getTipos() {
    return this.tipoRepository.find({ order: { descricao: 'ASC' } });
  }

  async save(data: any) {
    const atendimento = this.atendimentoRepository.create({
      ...data,
      horarioInicial: data.horarioInicial || new Date(),
      horarioFinal: data.horarioFinal || new Date(),
    });
    return this.atendimentoRepository.save(atendimento);
  }
}
