import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Filial } from './filial.entity';

@Entity('tipo_entrada_saida')
export class TipoEntradaSaida {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'empresa_id', type: 'integer', nullable: true })
  empresaId: number;

  @Column({ name: 'cod_erp', length: 10 })
  codErp: string;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ length: 100 })
  descricao: string;

  @Column({ length: 100, nullable: true })
  finalidade: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ type: 'char', length: 4, nullable: true })
  cfop: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
