import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('estado')
export class Estado {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 2 })
  codErp: string;

  @Column({ length: 2 })
  sigla: string;

  @Column({ length: 100 })
  descricao: string;

  @Column({ name: 'codigo_ibge', length: 10, nullable: true })
  codigoIbge: string;
}
