import { Injectable, Logger } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { DataSource, Repository } from 'typeorm';
import { Cliente } from '../../entities/cliente.entity';
import { Vendedor } from '../../entities/vendedor.entity';
import { ErpTranslationService } from '../../../master-data/services/erp-translation/erp-translation.service';
import { AnalyticsRefreshService } from '../../../analytics/services/analytics-refresh.service';

@Injectable()
export class SyncCommercialService {
  private readonly logger = new Logger(SyncCommercialService.name);

  constructor(
    private dataSource: DataSource,
    private erpTranslation: ErpTranslationService,
    private analyticsRefresh: AnalyticsRefreshService,
    @InjectRepository(Cliente)
    private clienteRepository: Repository<Cliente>,
    @InjectRepository(Vendedor)
    private vendedorRepository: Repository<Vendedor>,
  ) {}

  async syncClientes(conteudo: any[]) {
    const results: any[] = [];
    const relations = {
      vendedor: 'vendedor',
      filial: 'filial',
      tabela_preco: 'tabela_preco',
      cond_pgto: 'condicao_pagamento',
      seguimento: 'segmento',
      regiao: 'regiao_cliente',
      motivo_bloqueio: 'motivo_bloqueio',
    };

    for (const item of conteudo) {
      const queryRunner = this.dataSource.createQueryRunner();
      await queryRunner.connect();
      await queryRunner.startTransaction();

      try {
        // 1. Resolução de Chaves Estrangeiras do Legado
        const resolvedData = await this.erpTranslation.resolveRelationIds(
          item,
          relations,
        );

        // 2. Mapeamento de nomes de campos (Legado -> Moderno)
        const mappedData: any = {
          codErp: resolvedData.cod_erp,
          razao: resolvedData.razao,
          fantasia: resolvedData.fantasia,
          cnpjCpf: resolvedData.cnpj_cpf,
          status: resolvedData.status || 'A',
          tipo: resolvedData.tipo || 'J',
          email: resolvedData.email,
          telefone1: resolvedData.telefone1,
          celular: resolvedData.celular,
          limite: resolvedData.limite,
          risco: resolvedData.risco,
          logInt: resolvedData.origem,
          sinc: 'S',
          // FKs resolvidas pelo erpTranslation
          vendedorId: resolvedData.vendedor_id,
          filialId: resolvedData.filial_id,
          tabelaPrecoId: resolvedData.tabela_preco_id,
          condicaoPagamentoId: resolvedData.cond_pgto_id,
          segmentoId: resolvedData.seguimento_id,
          regiaoClienteId: resolvedData.regiao_id,
          motivoBloqueioId: resolvedData.motivo_bloqueio_id,
        };

        // 3. Tratamento de Situação Cadastral (Especial no legado)
        if (resolvedData.situacao_cadastral) {
          const situacao = await queryRunner.manager.query(
            `SELECT id FROM situacao_cadastral WHERE descricao = $1 LIMIT 1`,
            [resolvedData.situacao_cadastral],
          );
          if (situacao.length > 0) {
            mappedData.situacaoCadastralId = situacao[0].id;
          }
        }

        // 4. Upsert (Busca por cod_erp)
        const existing = await this.clienteRepository.findOne({
          where: { codErp: mappedData.codErp },
        });

        let saved;
        if (existing) {
          saved = await queryRunner.manager.save(Cliente, {
            ...existing,
            ...mappedData,
          });
        } else {
          saved = await queryRunner.manager.save(Cliente, mappedData);
        }

        await queryRunner.commitTransaction();
        results.push({ status: 'OK', cod_erp: item.cod_erp, id: saved.id });
      } catch (err) {
        await queryRunner.rollbackTransaction();
        this.logger.error(
          `Erro ao sincronizar cliente ${item.cod_erp}: ${err.message}`,
        );
        results.push({
          status: 'error',
          cod_erp: item.cod_erp,
          message: err.message,
        });
      } finally {
        await queryRunner.release();
      }
    }

    // Refresh materialized views in the background
    await this.analyticsRefresh.refreshViews();

    return results;
  }
}
