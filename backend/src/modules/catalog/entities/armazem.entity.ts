import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('armazem')
export class Armazem {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', type: 'char', length: 6 })
  codErp: string;

  @Column({ length: 50 })
  descricao: string;

  @Column({ length: 1, nullable: true })
  status: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
