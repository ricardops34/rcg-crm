import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  DeleteDateColumn,
  ManyToOne,
  OneToMany,
  JoinColumn,
} from 'typeorm';
import { Vendedor } from './vendedor.entity';
import { MetaVendedorCategoria } from './meta-vendedor-categoria.entity';

@Entity('meta_vendedor_mes')
export class MetaVendedorMes {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'vendedor_id', type: 'integer' })
  vendedorId: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor_id' })
  vendedor: Vendedor;

  @Column({ length: 2 })
  mes: string;

  @Column({ length: 4 })
  ano: string;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ type: 'float' })
  valor: number;

  @Column({ name: 'numero_cliente', type: 'float', nullable: true })
  numeroCliente: number;

  @Column({ name: 'novo_cliente', type: 'float', nullable: true })
  novoCliente: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;

  @DeleteDateColumn({ name: 'dt_delete' })
  dtDelete: Date;

  @OneToMany(() => MetaVendedorCategoria, (categoria) => categoria.metaMes)
  categorias: MetaVendedorCategoria[];
}
