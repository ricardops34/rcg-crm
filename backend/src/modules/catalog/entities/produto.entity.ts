import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn, ManyToOne, JoinColumn } from 'typeorm';
import { Filial } from '../../master-data/entities/filial.entity';
import { Categoria } from './categoria.entity';
import { SubCategoria } from './sub-categoria.entity';

@Entity('produto')
export class Produto {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'cod_erp', length: 30 })
  codErp: string;

  @Column({ length: 100 })
  descricao: string;

  @Column({ type: 'char', length: 2, nullable: true })
  tipo: string;

  @Column({ type: 'char', length: 2, nullable: true })
  um: string;

  @Column({ name: 'categoria_id', type: 'integer', nullable: true })
  categoriaId: number;

  @ManyToOne(() => Categoria)
  @JoinColumn({ name: 'categoria_id' })
  categoria: Categoria;

  @Column({ name: 'sub_categoria_id', type: 'integer', nullable: true })
  subCategoriaId: number;

  @ManyToOne(() => SubCategoria)
  @JoinColumn({ name: 'sub_categoria_id' })
  subCategoria: SubCategoria;

  @Column({ name: 'fabricante_id', type: 'integer', nullable: true })
  fabricanteId: number;

  @Column({ name: 'armazem_id', type: 'integer', nullable: true })
  armazemId: number;

  @Column({ name: 'codigo_barras', length: 30, nullable: true })
  codigoBarras: string;

  @Column({ name: 'codigo_fabricante', type: 'char', length: 60, nullable: true })
  codigoFabricante: string;

  @Column({ name: 'qtd_embalagem', type: 'float', nullable: true })
  qtdEmbalagem: number;

  @Column({ length: 200, nullable: true })
  observacao: string;

  @Column({ length: 200, nullable: true })
  foto: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ type: 'char', length: 20, nullable: true })
  ncm: string;

  @Column({ type: 'char', length: 2, nullable: true })
  origem: string;

  @Column({ name: 'peso_bruto', type: 'float', nullable: true })
  pesoBruto: number;

  @Column({ type: 'float', nullable: true })
  peso: number;

  @Column({ length: 20, nullable: true })
  marca: string;

  @Column({ name: 'te_id', type: 'integer', nullable: true })
  teId: number;

  @Column({ name: 'ts_id', type: 'integer', nullable: true })
  tsId: number;

  @Column({ type: 'char', length: 1, nullable: true })
  sinc: string;

  @Column({ name: 'ponto_pedido', type: 'float', nullable: true })
  pontoPedido: number;

  @Column({ name: 'estoque_seguranca', type: 'float', nullable: true })
  estoqueSeguranca: number;

  @Column({ name: 'dt_ult_compra', type: 'date', nullable: true })
  dtUltCompra: Date;

  @Column({ name: 'ult_preco', type: 'float', nullable: true })
  ultPreco: number;

  @Column({ type: 'text', nullable: true })
  informacoesTecnicas: string;

  @Column({ name: 'dados_tecnicos', type: 'text', nullable: true })
  dadosTecnicos: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
