import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
  DeleteDateColumn,
} from 'typeorm';
import { AtendimentoTipo } from './atendimento-tipo.entity';
import { Vendedor } from '../../commercial/entities/vendedor.entity';
import { Cliente } from '../../commercial/entities/cliente.entity';

@Entity('atendimento')
export class Atendimento {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'atendimento_tipo_id', type: 'integer' })
  atendimentoTipoId: number;

  @ManyToOne(() => AtendimentoTipo)
  @JoinColumn({ name: 'atendimento_tipo_id' })
  tipo: AtendimentoTipo;

  @Column({ name: 'vendedor_id', type: 'integer' })
  vendedorId: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor_id' })
  vendedor: Vendedor;

  @Column({ name: 'cliente_id', type: 'integer', nullable: true })
  clienteId: number;

  @ManyToOne(() => Cliente)
  @JoinColumn({ name: 'cliente_id' })
  cliente: Cliente;

  @Column({ name: 'horario_inicial', type: 'timestamp' })
  horarioInicial: Date;

  @Column({ name: 'horario_final', type: 'timestamp' })
  horarioFinal: Date;

  @Column({ type: 'text', nullable: true })
  titulo: string;

  @Column({ type: 'text', nullable: true })
  observacao: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;

  @DeleteDateColumn({ name: 'dt_delete' })
  dtDelete: Date;
}
