import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
} from 'typeorm';

@Entity('condicao_pagamento')
export class CondicaoPagamento {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ name: 'cod_erp', length: 3 })
  codErp: string;

  @Column({ length: 100 })
  descricao: string;

  @Column({ type: 'char', length: 3, nullable: true })
  forma: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
