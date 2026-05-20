import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Filial } from '../../master-data/entities/filial.entity';
import { Cliente } from '../../commercial/entities/cliente.entity';
import { Vendedor } from '../../commercial/entities/vendedor.entity';

@Entity('titulo_receber')
export class TituloReceber {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'cliente_id', type: 'integer' })
  clienteId: number;

  @ManyToOne(() => Cliente)
  @JoinColumn({ name: 'cliente_id' })
  cliente: Cliente;

  @Column({ name: 'vendedor_id', type: 'integer', nullable: true })
  vendedorId: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor_id' })
  vendedor: Vendedor;

  @Column({ name: 'natureza_id', type: 'integer', nullable: true })
  naturezaId: number;

  @Column({ type: 'date' })
  emissao: Date;

  @Column({ type: 'char', length: 9 })
  numero: string;

  @Column({ type: 'char', length: 3 })
  parcela: string;

  @Column({ type: 'char', length: 3 })
  prefixo: string;

  @Column({ type: 'char', length: 3, nullable: true })
  tipo: string;

  @Column({ type: 'float', nullable: true })
  saldo: number;

  @Column({ type: 'float', nullable: true })
  valor: number;

  @Column({ type: 'float', nullable: true })
  decrescimo: number;

  @Column({ type: 'float', nullable: true })
  acrescimo: number;

  @Column({ name: 'valor_juros', type: 'float', nullable: true })
  valorJuros: number;

  @Column({ name: 'perc_juros', type: 'float', nullable: true })
  percJuros: number;

  @Column({ name: 'mora_dia', type: 'float', nullable: true })
  moraDia: number;

  @Column({ name: 'taxa_multa', type: 'float', nullable: true })
  taxaMulta: number;

  @Column({ name: 'dt_digitacao', type: 'date', nullable: true })
  dtDigitacao: Date;

  @Column({ type: 'date' })
  vencimento: Date;

  @Column({ name: 'venc_real', type: 'date' })
  vencReal: Date;

  @Column({ name: 'venc_orig', type: 'date', nullable: true })
  vencOrig: Date;

  @Column({ name: 'pedido_id', type: 'integer', nullable: true })
  pedidoId: number;

  @Column({ type: 'char', length: 3, nullable: true })
  banco: string;

  @Column({ type: 'char', length: 10, nullable: true })
  agencia: string;

  @Column({ type: 'char', length: 20, nullable: true })
  conta: string;

  @Column({ name: 'nosso_numero', type: 'char', length: 50, nullable: true })
  nossoNumero: string;

  @Column({ name: 'id_cnab', type: 'char', length: 20, nullable: true })
  idCnab: string;

  @Column({ name: 'cod_barras', type: 'char', length: 50, nullable: true })
  codBarras: string;

  @Column({ name: 'lin_digitavel', length: 50, nullable: true })
  linDigitavel: string;

  @Column({ name: 'forma_pgto', type: 'char', length: 1, nullable: true })
  formaPgto: string;

  @Column({ name: 'controle_bco', type: 'char', length: 2, nullable: true })
  controleBco: string;

  @Column({ name: 'dig_nosso_numero', type: 'char', length: 1, nullable: true })
  digNossoNumero: string;

  @Column({ type: 'char', length: 1, nullable: true })
  impresso: string;

  @Column({ type: 'char', length: 10, nullable: true })
  origem: string;

  @Column({ type: 'text', nullable: true })
  historico: string;

  @Column({ name: 'usr_inclusao', type: 'char', length: 10, nullable: true })
  usrInclusao: string;

  @Column({ name: 'usr_alteracao', type: 'char', length: 10, nullable: true })
  usrAlteracao: string;

  @Column({ name: 'reg_ativo', type: 'char', length: 1, nullable: true })
  regAtivo: string;

  @Column({ type: 'date', nullable: true })
  baixa: Date;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ name: 'e1_recno', type: 'integer', nullable: true })
  e1Recno: number;

  @Column({ name: 'nota_fiscal_id', type: 'integer', nullable: true })
  notaFiscalId: number;

  @Column({ type: 'integer', nullable: true })
  vias: number;

  @Column({ type: 'char', length: 1, nullable: true })
  situacao: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
