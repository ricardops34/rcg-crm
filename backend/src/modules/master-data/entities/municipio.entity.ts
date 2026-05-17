import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('municipio')
export class Municipio {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 10, nullable: true })
  codErp: string;

  @Column({ length: 100 })
  descricao: string;

  @Column({ name: 'codigo_ibge', length: 10, nullable: true })
  codigoIbge: string;

  @Column({ name: 'estado_id', type: 'integer', nullable: true })
  estadoId: number;
}
