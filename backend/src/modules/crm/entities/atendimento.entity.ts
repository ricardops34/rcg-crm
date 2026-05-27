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

  @Column({ name: 'codigo_cliente', length: 10, nullable: true })
  codigoCliente: string;

  @Column({ name: 'horario_inicial', type: 'timestamp' })
  horarioInicial: Date;

  @Column({ name: 'horario_final', type: 'timestamp' })
  horarioFinal: Date;

  @Column({ type: 'text', nullable: true })
  titulo: string;

  @Column({ type: 'text', nullable: true })
  cor: string;

  @Column({ type: 'timestamp', nullable: true })
  retorno: Date;

  @Column({ type: 'text', nullable: true })
  observacao: string;

  @Column({ name: 'user_id_create', type: 'integer', nullable: true })
  userIdCreate: number;

  @Column({ name: 'user_id_update', type: 'integer', nullable: true })
  userIdUpdate: number;

  @Column({ name: 'user_id_delete', type: 'integer', nullable: true })
  userIdDelete: number;

  @Column({ length: 100, nullable: true })
  nome: string;

  @Column({ length: 50, nullable: true })
  telefone: string;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ name: 'orcamento_id', type: 'integer', nullable: true })
  orcamentoId: number;

  @Column({ name: 'nota_saida_id', type: 'integer', nullable: true })
  notaSaidaId: number;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ length: 255, nullable: true })
  anexo: string;

  @DeleteDateColumn({ name: 'dt_delete' })
  dtDelete: Date;
}
