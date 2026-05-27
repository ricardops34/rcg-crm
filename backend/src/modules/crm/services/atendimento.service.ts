import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, DeepPartial } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Atendimento } from '../entities/atendimento.entity';
import { AtendimentoTipo } from '../entities/atendimento-tipo.entity';
import { UploadService, MulterFile } from '../../../admin/services/upload.service';

export interface SaveAtendimentoInput {
  id?: number;
  atendimentoTipoId: number;
  vendedorId?: number;
  clienteId?: number;
  codigoCliente?: string;
  horarioInicial: string | Date;
  horarioFinal: string | Date;
  titulo: string;
  cor?: string;
  observacao?: string;
  status?: string;
  userIdCreate?: number;
}

@Injectable()
export class AtendimentoService {
  constructor(
    @InjectRepository(Atendimento)
    private readonly atendimentoRepository: Repository<Atendimento>,
    @InjectRepository(AtendimentoTipo)
    private readonly atendimentoTipoRepository: Repository<AtendimentoTipo>,
    private readonly uploadService: UploadService,
    private readonly cls: ClsService,
  ) {}

  async findAll(start?: string, end?: string, vendedorId?: number) {
    const user = this.cls.get('user');
    
    const query = this.atendimentoRepository
      .createQueryBuilder('atendimento')
      .leftJoinAndSelect('atendimento.tipo', 'tipo')
      .leftJoinAndSelect('atendimento.cliente', 'cliente')
      .leftJoinAndSelect('atendimento.vendedor', 'vendedor')
      .where('atendimento.dtDelete IS NULL')
      .orderBy('atendimento.horarioInicial', 'ASC');

    // Filtro de Multitenancy (system_unit_id) obrigatório
    if (user?.unitId) {
      query.andWhere('atendimento.systemUnitId = :unitId', { unitId: user.unitId });
    }

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
    const user = this.cls.get('user');

    const query = this.atendimentoTipoRepository
      .createQueryBuilder('tipo')
      .where("(COALESCE(tipo.atendimento, 'N') = 'S' OR COALESCE(tipo.venda, 'N') = 'S')")
      .orderBy('tipo.descricao', 'ASC');

    // Filtro de Multitenancy (system_unit_id) obrigatório
    if (user?.unitId) {
      query.andWhere('tipo.systemUnitId = :unitId', { unitId: user.unitId });
    }

    return query.getMany();
  }

  async save(
    data: SaveAtendimentoInput,
    userId: number,
    vendedorId: number,
  ): Promise<Atendimento> {
    const user = this.cls.get('user');
    const unitId = user?.unitId || 1;

    const atendimento: Atendimento = this.atendimentoRepository.create({
      ...data,
      vendedorId,
      status: data.status || 'A',
      userIdCreate: data.id ? data.userIdCreate : userId,
      userIdUpdate: userId,
      systemUnitId: unitId,
    } as DeepPartial<Atendimento>);

    return this.atendimentoRepository.save(atendimento);
  }

  async findOne(id: number): Promise<Atendimento> {
    const user = this.cls.get('user');
    const unitId = user?.unitId;

    const where: any = { id };
    if (unitId) {
      where.systemUnitId = unitId;
    }

    const atendimento = await this.atendimentoRepository.findOne({
      where,
      relations: ['tipo', 'vendedor', 'cliente'],
    });

    if (!atendimento) {
      throw new NotFoundException(`Atendimento com ID ${id} não encontrado`);
    }

    return atendimento;
  }

  async adicionarAnexo(id: number, file: MulterFile): Promise<Atendimento> {
    const atendimento = await this.findOne(id);
    const unitId = atendimento.systemUnitId || 1;

    // 1. Se já existia um anexo, remove fisicamente o anterior para não acumular resíduos
    if (atendimento.anexo) {
      this.uploadService.removerArquivoFisico(atendimento.anexo);
    }

    // 2. Salvar novo arquivo físico com validação estrita de cota
    const caminho = await this.uploadService.verificarCotaESalvar(
      unitId,
      file,
      `atendimentos/atendimento_${id}`,
    );

    // 3. Gravar caminho relativo no banco
    atendimento.anexo = caminho;
    return this.atendimentoRepository.save(atendimento);
  }

  async removerAnexo(id: number): Promise<Atendimento> {
    const atendimento = await this.findOne(id);

    // 1. Remove o arquivo físico do disco
    if (atendimento.anexo) {
      this.uploadService.removerArquivoFisico(atendimento.anexo);
    }

    // 2. Limpar a referência no banco de dados
    atendimento.anexo = null;
    return this.atendimentoRepository.save(atendimento);
  }

  async obterTodosCaminhosAnexos(systemUnitId: number): Promise<string[]> {
    const atendimentos = await this.atendimentoRepository.find({
      select: ['anexo'],
      where: { systemUnitId },
    });
    
    return atendimentos
      .map((a) => a.anexo)
      .filter((anexo): anexo is string => typeof anexo === 'string' && anexo.length > 0);
  }
}
