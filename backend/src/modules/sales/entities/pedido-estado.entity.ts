import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('pedido_estado')
export class PedidoEstado {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 2, nullable: true })
  codErp: string;

  @Column({ type: 'char', length: 100, nullable: true })
  descricao: string;

  @Column({ length: 10, nullable: true })
  cor: string;

  @Column({ name: 'cor_texto', length: 10, nullable: true })
  corTexto: string;
}
