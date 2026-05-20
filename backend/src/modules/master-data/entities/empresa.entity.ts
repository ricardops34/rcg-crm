import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('empresa')
export class Empresa {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 6, nullable: true })
  codErp: string;

  @Column({ length: 100, nullable: true })
  nome: string;

  @Column({ length: 100, nullable: true })
  razao: string;

  @Column({ length: 100, nullable: true })
  fantasia: string;

  @Column({ name: 'cnpj_cpf', length: 20, nullable: true })
  cnpjCpf: string;

  @Column({ length: 1, nullable: true })
  status: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ name: 'dt_inclusao', type: 'timestamp', nullable: true })
  dtInclusao: Date;

  @Column({ name: 'dt_alteracao', type: 'timestamp', nullable: true })
  dtAlteracao: Date;
}
