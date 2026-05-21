import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, DataSource } from 'typeorm';
import { Cliente } from '../../commercial/entities/cliente.entity';

@Injectable()
export class WhatsAppService {
  constructor(
    @InjectRepository(Cliente)
    private readonly clienteRepository: Repository<Cliente>,
    private dataSource: DataSource,
  ) {}

  async getFinancialPositionByPhone(phone: string) {
    // Limpar o telefone (remover caracteres não numéricos)
    const cleanPhone = phone.replace(/\D/g, '');
    
    if (cleanPhone.length < 8) {
      return { error: 'Telefone inválido' };
    }

    // Busca cliente que possua esse telefone em algum dos campos de contato
    // Usamos LIKE para pegar variações com DDD ou sem
    const cliente = await this.clienteRepository
      .createQueryBuilder('cliente')
      .where("cliente.telefone1 LIKE :phone", { phone: `%${cleanPhone}` })
      .orWhere("cliente.telefone2 LIKE :phone", { phone: `%${cleanPhone}` })
      .orWhere("cliente.celular LIKE :phone", { phone: `%${cleanPhone}` })
      .orWhere("cliente.celular2 LIKE :phone", { phone: `%${cleanPhone}` })
      .getOne();

    if (!cliente) {
      return { error: 'Cliente não encontrado para este número' };
    }

    // Busca posição financeira
    const financial = await this.dataSource.query(
      `SELECT 
        SUM(CASE WHEN venc_real < CURRENT_DATE THEN saldo ELSE 0 END) as vencido,
        SUM(CASE WHEN venc_real >= CURRENT_DATE THEN saldo ELSE 0 END) as a_vencer,
        COUNT(id) as total_titulos,
        MAX(CURRENT_DATE - venc_real) as maior_atraso
       FROM titulo_receber 
       WHERE cliente_id = $1 AND saldo > 0 AND reg_ativo = 'S'`,
      [cliente.id],
    );

    const stats = financial[0] || { vencido: 0, a_vencer: 0, total_titulos: 0, maior_atraso: 0 };

    return {
      cliente: {
        id: cliente.id,
        razao: cliente.razao,
        fantasia: cliente.fantasia,
        cod_erp: cliente.codErp,
        status: cliente.status,
      },
      financeiro: {
        vencido: parseFloat(stats.vencido || 0),
        a_vencer: parseFloat(stats.a_vencer || 0),
        total_titulos: parseInt(stats.total_titulos || 0),
        maior_atraso: parseInt(stats.maior_atraso || 0),
        situacao: parseFloat(stats.vencido || 0) > 0 ? 'INADIMPLENTE' : 'EM DIA'
      }
    };
  }
}
