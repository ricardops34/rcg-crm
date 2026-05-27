import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  ManyToOne,
  JoinColumn,
  CreateDateColumn,
  UpdateDateColumn,
} from 'typeorm';
import { Produto } from './produto.entity';

@Entity('produto_imagem')
export class ProdutoImagem {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'system_unit_id', type: 'integer', default: 1 })
  systemUnitId: number;

  @Column({ name: 'produto_id', type: 'integer' })
  produtoId: number;

  @Column({ length: 255 })
  caminho: string;

  @Column({ type: 'char', length: 1, default: 'N' })
  principal: string; // 'S' para Destaque/Principal, 'N' para Galeria comum

  @Column({ type: 'integer', default: 0 })
  ordem: number;

  @CreateDateColumn({
    name: 'dt_inclusao',
    type: 'timestamp',
    default: () => 'CURRENT_TIMESTAMP',
  })
  dtInclusao: Date;

  @UpdateDateColumn({
    name: 'dt_alteracao',
    type: 'timestamp',
    default: () => 'CURRENT_TIMESTAMP',
    onUpdate: 'CURRENT_TIMESTAMP',
  })
  dtAlteracao: Date;

  @ManyToOne(() => Produto, (produto) => produto.imagens, {
    onDelete: 'CASCADE',
  })
  @JoinColumn({ name: 'produto_id' })
  produto: Produto;
}
