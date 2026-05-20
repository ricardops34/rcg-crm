import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('pedido_estado')
export class PedidoEstado {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 50 })
  descricao: string;

  @Column({ length: 1, nullable: true })
  status: string;

  @Column({ length: 1, nullable: true })
  cancela: string;

  @Column({ length: 1, nullable: true })
  fatura: string;

  @Column({ length: 1, nullable: true })
  finaliza: string;

  @Column({ length: 1, nullable: true })
  ordem: string;

  @Column({ name: 'cor', type: 'text', nullable: true })
  cor: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
