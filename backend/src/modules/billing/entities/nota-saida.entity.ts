import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
  OneToMany,
} from 'typeorm';
import { Filial } from '../../master-data/entities/filial.entity';
import { Cliente } from '../../commercial/entities/cliente.entity';
import { Vendedor } from '../../commercial/entities/vendedor.entity';
import { NotaSaidaItem } from './nota-saida-item.entity';

@Entity('nota_saida')
export class NotaSaida {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'cliente_id', type: 'integer', nullable: true })
  clienteId: number;

  @ManyToOne(() => Cliente)
  @JoinColumn({ name: 'cliente_id' })
  cliente: Cliente;

  @Column({ name: 'fornecedor_id', type: 'integer', nullable: true })
  fornecedorId: number;

  @Column({ name: 'nota_fiscal', length: 9, nullable: true })
  notaFiscal: string;

  @Column({ name: 'serie_fiscal', length: 3, nullable: true })
  serieFiscal: string;

  @Column({ name: 'especie_fiscal', length: 10, nullable: true })
  especieFiscal: string;

  @Column({ name: 'condicao_id', type: 'integer', nullable: true })
  condicaoId: number;

  @Column({ name: 'numero_titulo', length: 9, nullable: true })
  numeroTitulo: string;

  @Column({ name: 'prefixo_titulo', length: 6, nullable: true })
  prefixoTitulo: string;

  @Column({ name: 'dt_emissao', type: 'date', nullable: true })
  dtEmissao: Date;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ type: 'char', length: 1, nullable: true })
  comodato: string;

  @Column({ name: 'vlr_bruto', type: 'float', nullable: true })
  vlrBruto: number;

  @Column({ name: 'vlr_icms', type: 'float', nullable: true })
  vlrIcms: number;

  @Column({ name: 'base_icms', type: 'float', nullable: true })
  baseIcms: number;

  @Column({ name: 'vlr_ipi', type: 'float', nullable: true })
  vlrIpi: number;

  @Column({ name: 'base_ipi', type: 'float', nullable: true })
  baseIpi: number;

  @Column({ name: 'vlr_mercadoria', type: 'float', nullable: true })
  vlrMercadoria: number;

  @Column({ name: 'vlr_desconto', type: 'float', nullable: true })
  vlrDesconto: number;

  @Column({ name: 'vlr_comodato', type: 'float', nullable: true })
  vlrComodato: number;

  @Column({ name: 'vlr_itens', type: 'float', nullable: true })
  vlrItens: number;

  @Column({ name: 'vlr_devolucao', type: 'float', nullable: true })
  vlrDevolucao: number;

  @Column({ length: 6, nullable: true })
  transportadora: string;

  @Column({ name: 'tp_frete', type: 'char', length: 1, nullable: true })
  tpFrete: string;

  @Column({ name: 'vlr_frete', type: 'float', nullable: true })
  vlrFrete: number;

  @Column({ name: 'vendedor1_id', type: 'integer', nullable: true })
  vendedor1Id: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor1_id' })
  vendedor1: Vendedor;

  @Column({ name: 'vendedor2_id', type: 'integer', nullable: true })
  vendedor2Id: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor2_id' })
  vendedor2: Vendedor;

  @Column({ name: 'chave_nfe', length: 100, nullable: true })
  chaveNfe: string;

  @Column({ name: 'dt_nfe', type: 'date', nullable: true })
  dtNfe: Date;

  @Column({ name: 'hr_nfe', length: 10, nullable: true })
  hrNfe: string;

  @Column({ name: 'mensagem_nf', type: 'text', nullable: true })
  mensagemNf: string;

  @Column({ name: 'numero_origem', length: 6, nullable: true })
  numeroOrigem: string;

  @Column({ name: 'serie_origem', length: 3, nullable: true })
  serieOrigem: string;

  @Column({ length: 6, nullable: true })
  intermediador: string;

  @Column({ name: 'reg_ativo', type: 'char', length: 1, nullable: true })
  regAtivo: string;

  @Column({ length: 2, nullable: true })
  mes: string;

  @Column({ length: 4, nullable: true })
  ano: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ name: 'date_danfe', type: 'timestamp', nullable: true })
  dateDanfe: Date;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;

  @OneToMany(() => NotaSaidaItem, (item) => item.notaSaida)
  itens: NotaSaidaItem[];
}

