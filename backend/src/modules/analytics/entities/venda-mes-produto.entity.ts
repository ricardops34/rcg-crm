import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('venda_mes_produto')
export class VendaMesProduto {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ name: 'empresa_id', type: 'integer', nullable: true })
  empresaId: number;

  @Column({ name: 'produto_id', type: 'integer' })
  produtoId: number;

  @Column({ type: 'char', length: 4 })
  ano: string;

  @Column({ type: 'float', default: 0 })
  janeiro: number;

  @Column({ type: 'float', default: 0 })
  fevereiro: number;

  @Column({ type: 'float', default: 0 })
  marco: number;

  @Column({ type: 'float', default: 0 })
  abril: number;

  @Column({ type: 'float', default: 0 })
  maio: number;

  @Column({ type: 'float', default: 0 })
  junho: number;

  @Column({ type: 'float', default: 0 })
  julho: number;

  @Column({ type: 'float', default: 0 })
  agosto: number;

  @Column({ type: 'float', default: 0 })
  setembro: number;

  @Column({ type: 'float', default: 0 })
  outubro: number;

  @Column({ type: 'float', default: 0 })
  novembro: number;

  @Column({ type: 'float', default: 0 })
  dezembro: number;

  @Column({ name: 'produto_nome', length: 100, nullable: true })
  produtoNome: string;
}
