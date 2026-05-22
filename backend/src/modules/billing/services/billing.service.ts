import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, Between, ILike } from 'typeorm';
import { NotaSaida } from '../entities/nota-saida.entity';
import { NotaSaidaXml } from '../entities/notasaida-xml.entity';

@Injectable()
export class BillingService {
  constructor(
    @InjectRepository(NotaSaida)
    private notaRepository: Repository<NotaSaida>,
    @InjectRepository(NotaSaidaXml)
    private xmlRepository: Repository<NotaSaidaXml>,
  ) {}

  async findAll(query: {
    clienteId?: number;
    vendedorId?: number;
    startDate?: string;
    endDate?: string;
    nota?: string;
    page?: number;
    limit?: number;
  }) {
    const { clienteId, vendedorId, startDate, endDate, nota, page = 1, limit = 50 } = query;

    const where: any = { regAtivo: 'S' };

    if (clienteId) where.clienteId = clienteId;
    if (vendedorId) where.vendedor1Id = vendedorId;
    if (nota) where.notaFiscal = ILike(`%${nota}%`);
    
    if (startDate && endDate) {
      where.dtEmissao = Between(new Date(startDate), new Date(endDate));
    }

    const [items, total] = await this.notaRepository.findAndCount({
      where,
      relations: ['cliente', 'vendedor1', 'filial'],
      order: { dtEmissao: 'DESC', notaFiscal: 'DESC' },
      skip: (page - 1) * limit,
      take: limit,
    });

    return { items, total, page, limit };
  }

  async findOne(id: number) {
    const nota = await this.notaRepository.findOne({
      where: { id },
      relations: ['cliente', 'vendedor1', 'filial', 'itens', 'itens.produto'],
    });

    if (!nota) throw new NotFoundException('Nota fiscal não encontrada');
    return nota;
  }

  async getXml(id: number) {
    const xmlRecord = await this.xmlRepository.findOne({
      where: { notaSaidaId: id },
    });

    if (!xmlRecord) throw new NotFoundException('XML da nota fiscal não localizado');
    
    // O legado armazena em xml_sig ou xml_tss
    return xmlRecord.xmlSig || xmlRecord.xmlTss;
  }

  async getLabelData(id: number) {
    const nota = await this.notaRepository.findOne({
      where: { id },
      relations: ['cliente', 'filial'],
    });

    if (!nota) throw new NotFoundException('Nota fiscal não encontrada');

    return {
      nota: nota.notaFiscal,
      cliente: nota.cliente.razao,
      endereco: nota.cliente.endereco,
      bairro: nota.cliente.bairro,
      cidade: nota.cliente.municipio, // Placeholder mapping
      uf: nota.cliente.uf,
      cep: nota.cliente.cep,
      transportadora: nota.transportadora,
      volumes: 1, // Default or fetch from somewhere
    };
  }

  async getComodatos(query: any) {
    // Busca notas que tenham vlr_comodato > 0 ou itens de comodato
    return this.notaRepository.find({
      where: { 
        regAtivo: 'S',
        vlrComodato: Between(0.01, 999999999) 
      },
      relations: ['cliente', 'vendedor1'],
      order: { dtEmissao: 'DESC' },
      take: 100
    });
  }
}
