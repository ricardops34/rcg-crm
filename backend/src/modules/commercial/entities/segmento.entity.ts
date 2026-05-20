import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('segmento')
export class Segmento {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 6 })
  codErp: string;

  @Column({ length: 200 })
  descricao: string;

  @Column({ length: 1, nullable: true })
  status: string;

  @Column({ name: 'reg_ativo', length: 1, nullable: true })
  regAtivo: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}

