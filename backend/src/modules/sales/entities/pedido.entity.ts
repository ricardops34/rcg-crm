import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
  OneToMany,
} from 'typeorm';
import { Filial } from '../../master-data/entities/filial.entity';
import { Cliente } from '../../commercial/entities/cliente.entity';
import { Vendedor } from '../../commercial/entities/vendedor.entity';
import { PedidoEstado } from './pedido-estado.entity';
import { PedidoItem } from './pedido-item.entity';

@Entity('pedido')
export class Pedido {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'pedido_estado_id', type: 'integer', nullable: true })
  pedidoEstadoId: number;

  @ManyToOne(() => PedidoEstado)
  @JoinColumn({ name: 'pedido_estado_id' })
  estado: PedidoEstado;

  @Column({ name: 'cliente_id', type: 'integer' })
  clienteId: number;

  @ManyToOne(() => Cliente)
  @JoinColumn({ name: 'cliente_id' })
  cliente: Cliente;

  @Column({ name: 'vendedor1_id', type: 'integer' })
  vendedor1Id: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor1_id' })
  vendedor1: Vendedor;

  @Column({ name: 'vendedor2_id', type: 'integer', nullable: true })
  vendedor2Id: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor2_id' })
  vendedor2: Vendedor;

  @Column({ name: 'cod_erp', type: 'char', length: 6, nullable: true })
  codErp: string;

  @Column({ name: 'dt_emissao', type: 'date', nullable: true })
  dtEmissao: Date;

  @Column({ name: 'vlr_total', type: 'float', nullable: true })
  vlrTotal: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;

  @OneToMany(() => PedidoItem, (item) => item.pedido)
  itens: PedidoItem[];
}
