import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { TabelaPreco } from './tabela-preco.entity';
import { Produto } from '../../catalog/entities/produto.entity';

@Entity('tabela_preco_item')
export class TabelaPrecoItem {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ type: 'integer', nullable: true })
  item: number;

  @Column({ name: 'tabela_preco_id', type: 'integer' })
  tabelaPrecoId: number;

  @ManyToOne(() => TabelaPreco)
  @JoinColumn({ name: 'tabela_preco_id' })
  tabelaPreco: TabelaPreco;

  @Column({ name: 'produto_id', type: 'integer' })
  produtoId: number;

  @ManyToOne(() => Produto)
  @JoinColumn({ name: 'produto_id' })
  produto: Produto;

  @Column({ type: 'float', nullable: true })
  preco: number;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
