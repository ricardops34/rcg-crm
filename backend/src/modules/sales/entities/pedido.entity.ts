import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
  OneToMany,
} from 'typeorm';
import { Filial } from '../../master-data/entities/filial.entity';
import { Cliente } from '../../commercial/entities/cliente.entity';
import { Vendedor } from '../../commercial/entities/vendedor.entity';
import { PedidoEstado } from './pedido-estado.entity';
import { PedidoItem } from './pedido-item.entity';

@Entity('pedido')
export class Pedido {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'pedido_estado_id', type: 'integer', nullable: true })
  pedidoEstadoId: number;

  @ManyToOne(() => PedidoEstado)
  @JoinColumn({ name: 'pedido_estado_id' })
  estado: PedidoEstado;

  @Column({ name: 'cliente_id', type: 'integer' })
  clienteId: number;

  @ManyToOne(() => Cliente)
  @JoinColumn({ name: 'cliente_id' })
  cliente: Cliente;

  @Column({ name: 'cliente_entrega_id', type: 'integer', nullable: true })
  clienteEntregaId: number;

  @Column({ name: 'vendedor1_id', type: 'integer' })
  vendedor1Id: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor1_id' })
  vendedor1: Vendedor;

  @Column({ name: 'vendedor2_id', type: 'integer', nullable: true })
  vendedor2Id: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor2_id' })
  vendedor2: Vendedor;

  @Column({ name: 'cod_erp', type: 'char', length: 6, nullable: true })
  codErp: string;

  @Column({ name: 'dt_emissao', type: 'date', nullable: true })
  dtEmissao: Date;

  @Column({ name: 'transportadora_id', type: 'integer', nullable: true })
  transportadoraId: number;

  @Column({ name: 'tabela_id', type: 'integer', nullable: true })
  tabelaId: number;

  @Column({ name: 'condicao_pagamento_id', type: 'integer', nullable: true })
  condicaoPagamentoId: number;

  @Column({ type: 'char', length: 1, nullable: true })
  sinc: string;

  @Column({ length: 2, nullable: true })
  mes: string;

  @Column({ length: 4, nullable: true })
  ano: string;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ name: 'nota_fiscal', length: 9, nullable: true })
  notaFiscal: string;

  @Column({ length: 3, nullable: true })
  serie: string;

  @Column({ name: 'mensagem_nf', length: 100, nullable: true })
  mensagemNf: string;

  @Column({ name: 'tp_frete', type: 'char', length: 1, nullable: true })
  tpFrete: string;

  @Column({ name: 'vlr_frete', type: 'float', nullable: true })
  vlrFrete: number;

  @Column({ name: 'vlr_total', type: 'float', nullable: true })
  vlrTotal: number;

  @Column({ name: 'vlr_comodato', type: 'float', nullable: true })
  vlrComodato: number;

  @Column({ type: 'char', length: 1, nullable: true })
  presencial: string;

  @Column({ name: 'pedido_origem', type: 'char', length: 1, nullable: true })
  pedidoOrigem: string;

  @Column({ name: 'log_int', type: 'text', nullable: true })
  logInt: string;

  @Column({ name: 'user_id', type: 'integer', nullable: true })
  userId: number;

  @Column({ name: 'intermediador_id', type: 'integer', nullable: true })
  intermediadorId: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;

  @Column({ name: 'orcamento_id', type: 'integer', nullable: true })
  orcamentoId: number;

  @Column({ name: 'nota_saida_id', type: 'integer', nullable: true })
  notaSaidaId: number;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @OneToMany(() => PedidoItem, (item) => item.pedido)
  itens: PedidoItem[];
}
