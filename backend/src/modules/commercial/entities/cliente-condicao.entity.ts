import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('cliente_condicao')
export class ClienteCondicao {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cliente_id', type: 'integer' })
  clienteId: number;

  @Column({ name: 'condicao_id', type: 'integer', nullable: true })
  condicaoId: number;

  @Column({ name: 'condicao_pagamento_id', type: 'integer', nullable: true })
  condicaoPagamentoId: number;

  @Column({ length: 1, nullable: true })
  padrao: string;

  @Column({ name: 'dt_inclusao', type: 'timestamp', nullable: true })
  dtInclusao: Date;

  @Column({ name: 'dt_alteracao', type: 'timestamp', nullable: true })
  dtAlteracao: Date;
}
