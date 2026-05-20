import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

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

  @Column({ length: 50, nullable: true })
  forma: string;

  @Column({ type: 'date', nullable: true })
  dt_inicio: Date;

  @Column({ type: 'date', nullable: true })
  dt_fim: Date;

  @Column({ length: 1, nullable: true })
  utiliza: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ name: 'dt_inclusao', type: 'timestamp', nullable: true })
  dtInclusao: Date;

  @Column({ name: 'dt_alteracao', type: 'timestamp', nullable: true })
  dtAlteracao: Date;
}
