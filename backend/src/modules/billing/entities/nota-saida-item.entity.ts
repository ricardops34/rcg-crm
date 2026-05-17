import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn, ManyToOne, JoinColumn } from 'typeorm';
import { NotaSaida } from './nota-saida.entity';
import { Produto } from '../../catalog/entities/produto.entity';

@Entity('nota_saida_item')
export class NotaSaidaItem {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'nota_saida_id', type: 'integer' })
  notaSaidaId: number;

  @ManyToOne(() => NotaSaida, (nota) => nota.itens)
  @JoinColumn({ name: 'nota_saida_id' })
  notaSaida: NotaSaida;

  @Column({ name: 'produto_id', type: 'integer', nullable: true })
  produtoId: number;

  @ManyToOne(() => Produto)
  @JoinColumn({ name: 'produto_id' })
  produto: Produto;

  @Column({ length: 4, nullable: true })
  item: string;

  @Column({ type: 'float', nullable: true })
  qtd: number;

  @Column({ name: 'vlr_unitario', type: 'float', nullable: true })
  vlrUnitario: number;

  @Column({ name: 'vlr_total', type: 'float', nullable: true })
  vlrTotal: number;

  @Column({ name: 'perc_comissao', type: 'float', nullable: true })
  percComissao: number;

  @Column({ type: 'float', nullable: true })
  comissao: number;

  @Column({ name: 'reg_ativo', type: 'char', length: 1, nullable: true })
  regAtivo: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
