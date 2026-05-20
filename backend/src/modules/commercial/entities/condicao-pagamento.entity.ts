import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('condicao_pagamento')
export class CondicaoPagamento {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ name: 'cod_erp', length: 3, nullable: true })
  codErp: string;

  @Column({ length: 50, nullable: true })
  descricao: string;

  @Column({ length: 1, nullable: true })
  status: string;

  @Column({ type: 'date', nullable: true })
  dt_inicio: Date;

  @Column({ type: 'date', nullable: true })
  dt_fim: Date;

  @Column({ length: 1, nullable: true })
  utiliza: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
