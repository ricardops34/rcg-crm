import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('filial')
export class Filial {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_emp', type: 'char', length: 6, nullable: true })
  codEmp: string;

  @Column({ name: 'cod_erp', type: 'char', length: 6 })
  codErp: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ length: 50, nullable: true })
  apelido: string;

  @Column({ type: 'char', length: 1, nullable: true })
  matriz: string;

  @Column({ length: 100 })
  nome: string;

  @Column({ length: 100, nullable: true })
  fantasia: string;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ length: 14, nullable: true })
  cnpj: string;

  @Column({ type: 'char', length: 11, nullable: true })
  cpf: string;

  @Column({ length: 8, nullable: true })
  cep: string;

  @Column({ length: 100, nullable: true })
  endereco: string;

  @Column({ length: 100, nullable: true })
  complemento: string;

  @Column({ length: 50, nullable: true })
  bairro: string;

  @Column({ length: 50, nullable: true })
  municipio: string;

  @Column({ type: 'char', length: 2, nullable: true })
  uf: string;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
