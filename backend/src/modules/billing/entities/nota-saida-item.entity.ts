import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
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

  @Column({ name: 'tes_id', type: 'integer', nullable: true })
  tesId: number;

  @Column({ name: 'vlr_tabela', type: 'float', nullable: true })
  vlrTabela: number;

  @Column({ name: 'vlr_bruto', type: 'float', nullable: true })
  vlrBruto: number;

  @Column({ name: 'base_icms', type: 'float', nullable: true })
  baseIcms: number;

  @Column({ name: 'aliq_icms', type: 'float', nullable: true })
  aliqIcms: number;

  @Column({ name: 'vlr_icms', type: 'float', nullable: true })
  vlrIcms: number;

  @Column({ name: 'base_ipi', type: 'float', nullable: true })
  baseIpi: number;

  @Column({ name: 'aliq_ipi', type: 'float', nullable: true })
  aliqIpi: number;

  @Column({ name: 'vlr_ipi', type: 'float', nullable: true })
  vlrIpi: number;

  @Column({ name: 'vlr_total', type: 'float', nullable: true })
  vlrTotal: number;

  @Column({ name: 'vlr_dev', type: 'float', nullable: true })
  vlrDev: number;

  @Column({ name: 'qtd_dev', type: 'float', nullable: true })
  qtdDev: number;

  @Column({ name: 'perc_desconto', type: 'float', nullable: true })
  percDesconto: number;

  @Column({ name: 'vlr_desconto', type: 'float', nullable: true })
  vlrDesconto: number;

  @Column({ type: 'float', nullable: true })
  peso: number;

  @Column({ name: 'pedido_item_id', type: 'integer', nullable: true })
  pedidoItemId: number;

  @Column({ name: 'reg_ativo', type: 'char', length: 1, nullable: true })
  regAtivo: string;

  @Column({ length: 3, nullable: true })
  tes: string;

  @Column({ type: 'char', length: 1, nullable: true })
  estoque: string;

  @Column({ type: 'char', length: 1, nullable: true })
  financeiro: string;

  @Column({ length: 4, nullable: true })
  ano: string;

  @Column({ length: 2, nullable: true })
  mes: string;

  @Column({ name: 'cliente_id', type: 'integer', nullable: true })
  clienteId: number;

  @Column({ name: 'vendedor1_id', type: 'integer', nullable: true })
  vendedor1Id: number;

  @Column({ name: 'vendedor2_id', type: 'integer', nullable: true })
  vendedor2Id: number;

  @Column({ name: 'dt_emissao', type: 'date', nullable: true })
  dtEmissao: Date;

  @Column({ type: 'char', length: 4, nullable: true })
  cfop: string;

  @Column({ name: 'perc_comissao', type: 'float', nullable: true })
  percComissao: number;

  @Column({ type: 'float', nullable: true })
  comissao: number;

  @Column({ type: 'char', length: 1, nullable: true })
  comodato: string;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ name: 'dt_inclusao', type: 'timestamp', nullable: true })
  dtInclusao: Date;

  @Column({ name: 'dt_alteracao', type: 'timestamp', nullable: true })
  dtAlteracao: Date;
}

