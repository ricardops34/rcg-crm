import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Categoria } from './categoria.entity';

@Entity('sub_categoria')
export class SubCategoria {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'categoria_id', type: 'integer' })
  categoriaId: number;

  @ManyToOne(() => Categoria)
  @JoinColumn({ name: 'categoria_id' })
  categoria: Categoria;

  @Column({ name: 'cod_erp', length: 6 })
  codErp: string;

  @Column({ length: 200 })
  descricao: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
