import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('tipo_cliente')
export class TipoCliente {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 6, nullable: true })
  codErp: string;

  @Column({ length: 50, nullable: true })
  descricao: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;
}
