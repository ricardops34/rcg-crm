import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Cliente } from './cliente.entity';
import { TipoContato } from './tipo-contato.entity';
import { Audited } from '../../admin/decorators/audited.decorator';

@Audited()
@Entity('cliente_contato')
export class ClienteContato {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cliente_id', type: 'integer' })
  clienteId: number;

  @ManyToOne(() => Cliente, (cliente) => cliente.contatos)
  @JoinColumn({ name: 'cliente_id' })
  cliente: Cliente;

  @Column({ name: 'tipo_contato_id', type: 'integer' })
  tipoContatoId: number;

  @ManyToOne(() => TipoContato)
  @JoinColumn({ name: 'tipo_contato_id' })
  tipoContato: TipoContato;

  @Column({ length: 100, nullable: true })
  nome: string;

  @Column({ type: 'char', length: 9, nullable: true })
  telefone: string;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ type: 'char', length: 1, nullable: true })
  situacao: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}

