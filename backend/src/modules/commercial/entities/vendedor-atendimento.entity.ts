import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Vendedor } from '../../commercial/entities/vendedor.entity';

@Entity('vendedor_atendimento')
export class VendedorAtendimento {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'vendedor_id', type: 'integer' })
  vendedorId: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor_id' })
  vendedor: Vendedor;

  @Column({ type: 'time', nullable: true })
  inicial: string;

  @Column({ type: 'time', nullable: true })
  final: string;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ name: 'dias_semana', length: 20, nullable: true })
  diasSemana: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
