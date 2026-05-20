import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Pedido } from './pedido.entity';
import { Produto } from '../../catalog/entities/produto.entity';

@Entity('pedido_item')
export class PedidoItem {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'pedido_id', type: 'integer' })
  pedidoId: number;

  @ManyToOne(() => Pedido, (pedido) => pedido.itens)
  @JoinColumn({ name: 'pedido_id' })
  pedido: Pedido;

  @Column({ name: 'produto_id', type: 'integer' })
  produtoId: number;

  @ManyToOne(() => Produto)
  @JoinColumn({ name: 'produto_id' })
  produto: Produto;

  @Column({ type: 'integer', nullable: true })
  item: number;

  @Column({ type: 'float', default: 0 })
  quantidade: number;

  @Column({ name: 'vlr_unitario', type: 'float', default: 0 })
  vlrUnitario: number;

  @Column({ name: 'vlr_total', type: 'float', default: 0 })
  vlrTotal: number;

  @Column({ name: 'armazem_id', type: 'integer', nullable: true })
  armazemId: number;

  @Column({ name: 'vlr_mercadoria', type: 'float', nullable: true })
  vlrMercadoria: number;

  @Column({ name: 'vlr_ipi', type: 'float', nullable: true })
  vlrIpi: number;

  @Column({ name: 'vlr_icms', type: 'float', nullable: true })
  vlrIcms: number;

  @Column({ name: 'perc_ipi', type: 'float', nullable: true })
  percIpi: number;

  @Column({ name: 'perc_icms', type: 'float', nullable: true })
  percIcms: number;

  @Column({ name: 'perc_comissao', type: 'float', nullable: true })
  percComissao: number;

  @Column({ type: 'float', nullable: true })
  comissao: number;

  @Column({ name: 'reg_ativo', type: 'char', length: 1, nullable: true })
  regAtivo: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
