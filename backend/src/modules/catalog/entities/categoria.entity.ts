import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('categoria')
export class Categoria {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ name: 'cod_erp', length: 6 })
  codErp: string;

  @Column({ length: 200 })
  descricao: string;

  @Column({ type: 'char', length: 1, nullable: true })
  usado: string;

  @Column({ type: 'char', length: 1, nullable: true })
  site: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
