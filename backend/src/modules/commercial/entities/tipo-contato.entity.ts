import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
} from 'typeorm';

@Entity('tipo_contato')
export class TipoContato {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 10, nullable: true })
  codErp: string;

  @Column({ length: 50, nullable: true })
  descricao: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}

