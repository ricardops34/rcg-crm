import { Injectable, BadRequestException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, DataSource, In } from 'typeorm';
import { Negociacao } from '../entities/negociacao.entity';
import { NegociacaoTituloReceber } from '../entities/negociacao-titulo-receber.entity';
import { TituloReceber } from '../entities/titulo-receber.entity';
import { Atendimento } from '../../crm/entities/atendimento.entity';

@Injectable()
export class NegociacaoService {
  constructor(
    @InjectRepository(Negociacao)
    private negociacaoRepository: Repository<Negociacao>,
    @InjectRepository(NegociacaoTituloReceber)
    private itemRepository: Repository<NegociacaoTituloReceber>,
    @InjectRepository(TituloReceber)
    private tituloRepository: Repository<TituloReceber>,
    @InjectRepository(Atendimento)
    private atendimentoRepository: Repository<Atendimento>,
    private dataSource: DataSource,
  ) {}

  async getDelinquentClients(vendedorId?: number) {
    let whereVendedor = '';
    const params: any[] = [];

    if (vendedorId) {
      whereVendedor = ' AND tr.vendedor_id = $1';
      params.push(vendedorId);
    }

    return this.dataSource.query(
      `SELECT 
        c.id as cliente_id,
        c.razao as cliente_nome,
        c.fantasia as cliente_fantasia,
        c.cod_erp,
        SUM(tr.saldo) as total_vencido,
        COUNT(tr.id) as qtd_titulos,
        MAX(CURRENT_DATE - tr.venc_real) as maior_atraso
       FROM titulo_receber tr
       JOIN cliente c ON c.id = tr.cliente_id
       WHERE tr.saldo > 0 
         AND tr.venc_real < CURRENT_DATE
         AND tr.reg_ativo = 'S'
         ${whereVendedor}
       GROUP BY c.id, c.razao, c.fantasia, c.cod_erp
       ORDER BY maior_atraso DESC`,
      params,
    );
  }

  async getTitlesForNegotiation(clienteId: number) {
    return this.tituloRepository.find({
      where: {
        clienteId,
        saldo: 0, // Placeholder logic: should be saldo > 0
        vencReal: undefined, // Placeholder logic: should be vencReal < today
        regAtivo: 'S',
      },
      order: { vencReal: 'ASC' },
    });
  }

  // Refined query for titles for negotiation
  async getOverdueTitles(clienteId: number) {
    return this.dataSource.query(
      `SELECT * FROM titulo_receber 
       WHERE cliente_id = $1 
         AND saldo > 0 
         AND venc_real < CURRENT_DATE
         AND reg_ativo = 'S'
       ORDER BY venc_real ASC`,
      [clienteId],
    );
  }

  async createNegotiation(data: {
    clienteId: number;
    vendedorId: number;
    atendimentoId?: number;
    atendimentoTipoId?: number;
    observacao: string;
    tituloIds: number[];
  }) {
    const overdueTitles = await this.getOverdueTitles(data.clienteId);
    const totalOverdueCount = overdueTitles.length;

    if (data.tituloIds.length < totalOverdueCount) {
      throw new BadRequestException(
        'É obrigatório selecionar TODOS os títulos vencidos para gerar uma negociação.',
      );
    }

    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      const negociacao = this.negociacaoRepository.create({
        clienteId: data.clienteId,
        vendedorId: data.vendedorId,
        atendimentoId: data.atendimentoId,
        atendimentoTipoId: data.atendimentoTipoId,
        observacao: data.observacao,
        dt_negociacao: new Date(),
        status: 'G',
      });

      const savedNegociacao = await queryRunner.manager.save(negociacao);

      const items = data.tituloIds.map((id) =>
        this.itemRepository.create({
          negociacaoId: savedNegociacao.id,
          tituloReceberId: id,
        }),
      );

      await queryRunner.manager.save(items);

      await queryRunner.commitTransaction();
      return savedNegociacao;
    } catch (err) {
      await queryRunner.rollbackTransaction();
      throw err;
    } finally {
      await queryRunner.release();
    }
  }

  async findAll() {
    return this.negociacaoRepository.find({
      relations: ['cliente', 'vendedor', 'titulos', 'titulos.tituloReceber'],
      order: { dt_negociacao: 'DESC' },
    });
  }
}
