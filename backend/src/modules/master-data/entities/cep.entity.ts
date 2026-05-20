import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('cep')
export class Cep {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 8, unique: true })
  cep: string;

  @Column({ length: 200, nullable: true })
  logradouro: string;

  @Column({ length: 100, nullable: true })
  bairro: string;

  @Column({ length: 100, nullable: true })
  cidade: string;

  @Column({ length: 2, nullable: true })
  uf: string;

  @Column({ name: 'municipio_id', type: 'integer', nullable: true })
  municipioId: number;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
