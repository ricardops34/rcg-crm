import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('empresa')
export class Empresa {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 6, nullable: true })
  codErp: string;

  @Column({ length: 100 })
  razao: string;

  @Column({ length: 100, nullable: true })
  fantasia: string;

  @Column({ name: 'cnpj_cpf', length: 20, nullable: true })
  cnpjCpf: string;

  @Column({ length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
