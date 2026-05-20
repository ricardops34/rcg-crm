import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('motivo_bloqueio')
export class MotivoBloqueio {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 10, nullable: true })
  codErp: string;

  @Column({ length: 40, nullable: true, type: 'char' })
  descricao: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
