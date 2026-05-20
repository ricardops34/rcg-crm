import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('estoque')
export class Estoque {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'armazem_id', type: 'integer' })
  armazemId: number;

  @Column({ name: 'produto_id', type: 'integer' })
  produtoId: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ type: 'float', default: 0 })
  quantidade: number;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
