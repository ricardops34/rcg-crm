import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn, ManyToOne, JoinColumn } from 'typeorm';
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

  @Column({ length: 4, nullable: true })
  item: string;

  @Column({ name: 'vlr_unitario', type: 'float', nullable: true })
  vlrUnitario: number;

  @Column({ name: 'quantidade', type: 'float', nullable: true })
  quantidade: number;

  @Column({ name: 'vlr_total', type: 'float', nullable: true })
  vlrTotal: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
