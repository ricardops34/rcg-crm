import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
} from 'typeorm';

@Entity('fabricante')
export class Fabricante {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', type: 'char', length: 6 })
  codErp: string;

  @Column({ length: 200 })
  descricao: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}

