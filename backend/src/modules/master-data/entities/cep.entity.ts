import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('cep')
export class Cep {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 8, unique: true })
  cep: string;

  @Column({ name: 'estado_id', type: 'integer', nullable: true })
  estadoId: number;

  @Column({ name: 'cidade_id', type: 'integer', nullable: true })
  cidadeId: number;

  @Column({ length: 200, nullable: true })
  logradouro: string;

  @Column({ length: 200, nullable: true })
  endereco: string;

  @Column({ length: 100, nullable: true })
  bairro: string;

  @Column({ length: 100, nullable: true })
  cidade: string;

  @Column({ length: 2, nullable: true })
  uf: string;

  @Column({ name: 'municipio_id', type: 'integer', nullable: true })
  municipioId: number;

  @Column({ length: 100, nullable: true })
  longitude: string;

  @Column({ length: 100, nullable: true })
  latitude: string;

  @Column({ length: 50, nullable: true })
  service: string;

  @Column({ name: 'dt_inclusao', type: 'timestamp', nullable: true })
  dtInclusao: Date;

  @Column({ name: 'dt_alteracao', type: 'timestamp', nullable: true })
  dtAlteracao: Date;
}
