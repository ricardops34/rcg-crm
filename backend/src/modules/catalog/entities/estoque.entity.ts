import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('estoque')
export class Estoque {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'armazem_id', type: 'integer' })
  armazemId: number;

  @Column({ name: 'produto_id', type: 'integer' })
  produtoId: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ type: 'float', default: 0 })
  quantidade: number;

  @Column({ type: 'float', default: 0, nullable: true })
  saldo: number;

  @Column({ type: 'float', default: 0, nullable: true })
  reserva: number;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ name: 'dt_inclusao', type: 'timestamp', nullable: true })
  dtInclusao: Date;

  @Column({ name: 'dt_alteracao', type: 'timestamp', nullable: true })
  dtAlteracao: Date;
}
