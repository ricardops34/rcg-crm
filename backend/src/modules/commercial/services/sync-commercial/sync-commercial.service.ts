import { Injectable, Logger } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { Cliente } from '../../entities/cliente.entity';
import { Vendedor } from '../../entities/vendedor.entity';
import { ErpTranslationService } from '../../../master-data/services/erp-translation/erp-translation.service';

@Injectable()
export class SyncCommercialService {
  private readonly logger = new Logger(SyncCommercialService.name);

  constructor(
    private dataSource: DataSource,
    private erpTranslation: ErpTranslationService,
    @InjectRepository(Cliente)
    private clienteRepository: Repository<Cliente>,
    @InjectRepository(Vendedor)
    private vendedorRepository: Repository<Vendedor>,
  ) {}

  async syncClientes(conteudo: any[]) {
    const results = [];
    const relations = {
      vendedor: 'vendedor',
      filial: 'filial',
      condicao_pagamento: 'condicao_pagamento',
      tabela_preco: 'tabela_preco',
    };

    for (const item of conteudo) {
      const queryRunner = this.dataSource.createQueryRunner();
      await queryRunner.connect();
      await queryRunner.startTransaction();

      try {
        const resolvedData = await this.erpTranslation.resolveRelationIds(item, relations);
        resolvedData.sinc = 'S';

        // Busca se já existe pelo cod_erp para fazer Upsert manual
        const existing = await this.clienteRepository.findOne({ 
          where: { codErp: resolvedData.cod_erp } 
        });

        let saved;
        if (existing) {
          saved = await queryRunner.manager.save(Cliente, { ...existing, ...resolvedData });
        } else {
          saved = await queryRunner.manager.save(Cliente, resolvedData);
        }

        await queryRunner.commitTransaction();
        results.push({ status: 'OK', cod_erp: item.cod_erp, id: saved.id });
      } catch (err) {
        await queryRunner.rollbackTransaction();
        this.logger.error(`Erro ao sincronizar cliente ${item.cod_erp}: ${err.message}`);
        results.push({ status: 'error', cod_erp: item.cod_erp, message: err.message });
      } finally {
        await queryRunner.release();
      }
    }

    return results;
  }
}
