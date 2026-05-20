import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Vendedor } from './vendedor.entity';
import { Supervisor } from './supervisor.entity';

@Entity('supervisor_vendedor')
export class SupervisorVendedor {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'vendedor_id' })
  vendedorId: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor_id' })
  vendedor: Vendedor;

  @Column({ name: 'supervisor_id' })
  supervisorId: number;

  @ManyToOne(() => Supervisor)
  @JoinColumn({ name: 'supervisor_id' })
  supervisor: Supervisor;

  @Column({ type: 'char', length: 1, nullable: true })
  sistema: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}

