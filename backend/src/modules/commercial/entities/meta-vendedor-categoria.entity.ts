import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  DeleteDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { MetaVendedorMes } from './meta-vendedor-mes.entity';
import { Categoria } from '../../catalog/entities/categoria.entity';

@Entity('meta_vendedor_categoria')
export class MetaVendedorCategoria {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'meta_vendedor_mes_id', type: 'integer' })
  metaVendedorMesId: number;

  @ManyToOne(() => MetaVendedorMes, (meta) => meta.categorias)
  @JoinColumn({ name: 'meta_vendedor_mes_id' })
  metaMes: MetaVendedorMes;

  @Column({ name: 'categoria_id', type: 'integer' })
  categoriaId: number;

  @ManyToOne(() => Categoria)
  @JoinColumn({ name: 'categoria_id' })
  categoria: Categoria;

  @Column({ name: 'cod_erp', length: 6, nullable: true })
  codErp: string;

  @Column({ length: 200, nullable: true })
  descricao: string;

  @Column({ type: 'float', nullable: true })
  valor: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;

  @DeleteDateColumn({ name: 'dt_delete' })
  dtDelete: Date;
}
