import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
} from 'typeorm';

@Entity('natureza')
export class Natureza {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 20, nullable: true })
  codErp: string;

  @Column({ type: 'text', nullable: true })
  descricao: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
