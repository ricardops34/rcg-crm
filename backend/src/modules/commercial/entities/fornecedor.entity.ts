import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('fornecedor')
export class Fornecedor {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ name: 'cod_erp', length: 10, nullable: true })
  codErp: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ length: 100 })
  razao: string;

  @Column({ type: 'integer', nullable: true })
  tipo: number;

  @Column({ length: 50, nullable: true })
  fantasia: string;

  @Column({ length: 100, nullable: true })
  endereco: string;

  @Column({ length: 50, nullable: true })
  complemento: string;

  @Column({ length: 50, nullable: true })
  bairro: string;

  @Column({ name: 'municipio_id', type: 'integer', nullable: true })
  municipioId: number;

  @Column({ type: 'char', length: 2, nullable: true })
  uf: string;

  @Column({ length: 8, nullable: true })
  cep: string;

  @Column({ name: 'cnpj_cpf', length: 20, nullable: true })
  cnpjCpf: string;

  @Column({ length: 20, nullable: true })
  ie: string;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
