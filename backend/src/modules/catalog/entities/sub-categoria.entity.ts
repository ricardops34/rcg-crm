import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('sub_categoria')
export class SubCategoria {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'categoria_id', type: 'integer' })
  categoriaId: number;

  @Column({ name: 'cod_erp', length: 6, nullable: true })
  codErp: string;

  @Column({ length: 200, nullable: true })
  descricao: string;

  @Column({ length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
