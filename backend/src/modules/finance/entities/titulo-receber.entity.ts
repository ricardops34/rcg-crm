import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Filial } from '../../master-data/entities/filial.entity';
import { Cliente } from '../../commercial/entities/cliente.entity';
import { Vendedor } from '../../commercial/entities/vendedor.entity';

@Entity('titulo_receber')
export class TituloReceber {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

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

  @Column({ type: 'date' })
  emissao: Date;

  @Column({ type: 'char', length: 9 })
  numero: string;

  @Column({ type: 'char', length: 3 })
  parcela: string;

  @Column({ type: 'char', length: 3 })
  prefixo: string;

  @Column({ type: 'float', nullable: true })
  saldo: number;

  @Column({ type: 'float', nullable: true })
  valor: number;

  @Column({ type: 'date' })
  vencimento: Date;

  @Column({ name: 'venc_real', type: 'date' })
  vencReal: Date;

  @Column({ name: 'lin_digitavel', length: 50, nullable: true })
  linDigitavel: string;

  @Column({ type: 'char', length: 1, nullable: true })
  situacao: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
