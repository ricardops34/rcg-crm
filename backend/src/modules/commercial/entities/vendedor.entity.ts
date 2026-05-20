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

@Entity('vendedor')
export class Vendedor {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'cod_erp', length: 6, nullable: true, unique: true })
  codErp: string;

  @Column({ name: 'system_users_id', type: 'integer', nullable: true })
  systemUsersId: number;

  @Column({ length: 100 })
  nome: string;

  @Column({ name: 'nome_reduzido', length: 50, nullable: true })
  nomeReduzido: string;

  @Column({ length: 3, nullable: true })
  ddd: string;

  @Column({ length: 15, nullable: true })
  telefone: string;

  @Column({ length: 15, nullable: true })
  celular: string;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ length: 1, nullable: true })
  vendedor: string;

  @Column({ type: 'char', length: 1, nullable: true })
  dashboard: string;

  @Column({ name: 'dt_nascmento', type: 'date', nullable: true })
  dtNascimento: Date;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ type: 'char', length: 1, nullable: true })
  supervisor: string;

  @Column({ name: 'supervisor_id', type: 'integer', nullable: true })
  supervisorId: number;

  @Column({ type: 'char', length: 1, nullable: true })
  desligado: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
