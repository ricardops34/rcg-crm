import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('tabela_preco_item')
export class TabelaPrecoItem {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ type: 'integer', nullable: true })
  item: number;

  @Column({ name: 'tabela_preco_id', type: 'integer' })
  tabelaPrecoId: number;

  @Column({ name: 'produto_id', type: 'integer' })
  produtoId: number;

  @Column({ type: 'float', nullable: true })
  preco: number;

  @Column({ length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
