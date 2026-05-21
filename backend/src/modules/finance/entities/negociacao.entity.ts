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
import { Cliente } from '../../commercial/entities/cliente.entity';
import { Vendedor } from '../../commercial/entities/vendedor.entity';
import { Atendimento } from '../../crm/entities/atendimento.entity';
import { AtendimentoTipo } from '../../crm/entities/atendimento-tipo.entity';
import { NegociacaoTituloReceber } from './negociacao-titulo-receber.entity';

@Entity('negociacao')
export class Negociacao {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cliente_id', type: 'integer' })
  clienteId: number;

  @ManyToOne(() => Cliente)
  @JoinColumn({ name: 'cliente_id' })
  cliente: Cliente;

  @Column({ name: 'vendedor_id', type: 'integer', nullable: true })
  vendedorId: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor_id' })
  vendedor: Vendedor;

  @Column({ name: 'atendimento_id', type: 'integer', nullable: true })
  atendimentoId: number;

  @ManyToOne(() => Atendimento)
  @JoinColumn({ name: 'atendimento_id' })
  atendimento: Atendimento;

  @Column({ name: 'atendimento_tipo_id', type: 'integer', nullable: true })
  atendimentoTipoId: number;

  @ManyToOne(() => AtendimentoTipo)
  @JoinColumn({ name: 'atendimento_tipo_id' })
  atendimentoTipo: AtendimentoTipo;

  @Column({ type: 'timestamp', nullable: true })
  dt_negociacao: Date;

  @Column({ length: 1, default: 'G' })
  status: string;

  @Column({ type: 'text', nullable: true })
  observacao: string;

  @Column({ name: 'dt_inclusao', type: 'timestamp', nullable: true })
  dtInclusao: Date;

  @Column({ name: 'dt_alteracao', type: 'timestamp', nullable: true })
  dtAlteracao: Date;

  @OneToMany(() => NegociacaoTituloReceber, (item) => item.negociacao)
  titulos: NegociacaoTituloReceber[];
}
