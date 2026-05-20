import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
  ManyToOne,
  OneToMany,
  JoinColumn,
} from 'typeorm';
import { Audited } from '../../admin/decorators/audited.decorator';
import { Filial } from '../../master-data/entities/filial.entity';
import { Vendedor } from './vendedor.entity';
import { CondicaoPagamento } from './condicao-pagamento.entity';
import { TabelaPreco } from './tabela-preco.entity';
import { ClienteContato } from './cliente-contato.entity';

@Audited()
@Entity('cliente')
export class Cliente {
  @PrimaryGeneratedColumn()
  id: number;

  @OneToMany(() => ClienteContato, (contato) => contato.cliente, {
    cascade: true,
  })
  contatos: ClienteContato[];

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @ManyToOne(() => Filial)
  @JoinColumn({ name: 'filial_id' })
  filial: Filial;

  @Column({ name: 'cod_erp', length: 10, nullable: true, unique: true })
  codErp: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ type: 'char', length: 1, nullable: true })
  tipo: string;

  @Column({ length: 100 })
  razao: string;

  @Column({ name: 'tipo_cliente_id', type: 'integer', nullable: true })
  tipoClienteId: number;

  @Column({ length: 50, nullable: true })
  fantasia: string;

  @Column({ length: 100, nullable: true })
  endereco: string;

  @Column({ length: 50, nullable: true })
  complemento: string;

  @Column({ length: 50, nullable: true })
  bairro: string;

  @Column({ type: 'char', length: 2, nullable: true })
  uf: string;

  @Column({ length: 50, nullable: true })
  municipio: string;

  @Column({ name: 'municipio_id', type: 'integer', nullable: true })
  municipioId: number;

  @Column({ length: 8, nullable: true })
  cep: string;

  @Column({ length: 20, nullable: true })
  telefone1: string;

  @Column({ length: 20, nullable: true })
  telefone2: string;

  @Column({ type: 'char', length: 20, nullable: true })
  fax: string;

  @Column({ length: 20, nullable: true })
  celular: string;

  @Column({ name: 'celular2', type: 'char', length: 20, nullable: true })
  celular2: string;

  @Column({ length: 30, nullable: true })
  contato: string;

  @Column({ name: 'cnpj_cpf', length: 20, nullable: true })
  cnpjCpf: string;

  @Column({ length: 20, nullable: true })
  ie: string;

  @Column({ type: 'char', length: 20, nullable: true })
  im: string;

  @Column({ type: 'char', length: 1, nullable: true })
  contribuinte: string;

  @Column({ length: 20, nullable: true })
  rg: string;

  @Column({ type: 'date', nullable: true })
  nascimento: Date;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ name: 'vendedor_id', type: 'integer', nullable: true })
  vendedorId: number;

  @ManyToOne(() => Vendedor)
  @JoinColumn({ name: 'vendedor_id' })
  vendedor: Vendedor;

  @Column({ name: 'condicao_pagamento_id', type: 'integer', nullable: true })
  condicaoPagamentoId: number;

  @ManyToOne(() => CondicaoPagamento)
  @JoinColumn({ name: 'condicao_pagamento_id' })
  condicaoPagamento: CondicaoPagamento;

  @Column({ name: 'tabela_preco_id', type: 'integer', nullable: true })
  tabelaPrecoId: number;

  @ManyToOne(() => TabelaPreco)
  @JoinColumn({ name: 'tabela_preco_id' })
  tabelaPreco: TabelaPreco;

  @Column({ name: 'primeira_compra', type: 'date', nullable: true })
  primeiraCompra: Date;

  @Column({ name: 'ultima_compra', type: 'date', nullable: true })
  ultimaCompra: Date;

  @Column({ name: 'data_cadastro', type: 'date', nullable: true })
  dataCadastro: Date;

  @Column({ type: 'char', length: 1, nullable: true })
  sinc: string;

  @Column({ name: 'destaca_ie', type: 'char', length: 1, nullable: true })
  destacaIe: string;

  @Column({ name: 'seguimento_id', type: 'integer', nullable: true })
  seguimentoId: number;

  @Column({ type: 'char', length: 100, nullable: true })
  site: string;

  @Column({ type: 'text', nullable: true })
  obs: string;

  @Column({ name: 'filial_cadastro', type: 'integer', nullable: true })
  filialCadastro: number;

  @Column({ type: 'char', length: 1, nullable: true })
  cliente: string;

  @Column({ type: 'float', nullable: true })
  latitude: number;

  @Column({ name: 'log_int', type: 'text', nullable: true })
  logInt: string;

  @Column({ type: 'float', nullable: true })
  longitude: number;

  @Column({ type: 'float', nullable: true })
  limite: number;

  @Column({ name: 'vencimento_limite', type: 'date', nullable: true })
  vencimentoLimite: Date;

  @Column({ type: 'char', length: 1, nullable: true })
  risco: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @Column({ type: 'char', length: 1, nullable: true })
  carteira: string;

  @Column({ name: 'obs_bloqueio', type: 'text', nullable: true })
  obsBloqueio: string;

  @Column({ name: 'dt_bloqueio', type: 'timestamp', nullable: true })
  dtBloqueio: Date;

  @Column({ name: 'motivo_bloqueio', type: 'char', length: 1, nullable: true })
  motivoBloqueio: string;

  @Column({ name: 'motivo_bloqueio_id', type: 'integer', nullable: true })
  motivoBloqueioId: number;

  @Column({ name: 'dt_reativacao', type: 'date', nullable: true })
  dtReativacao: Date;

  @Column({ name: 'obs_reativacao', type: 'text', nullable: true })
  obsReativacao: string;

  @Column({ name: 'ultima_visita', type: 'timestamp', nullable: true })
  ultimaVisita: Date;

  @Column({ name: 'ultimo_atendimento', type: 'timestamp', nullable: true })
  ultimoAtendimento: Date;

  @Column({ name: 'regiao_cliente_id', type: 'integer', nullable: true })
  regiaoClienteId: number;

  @Column({ name: 'reg_ativo', type: 'char', length: 1, nullable: true })
  regAtivo: string;

  @Column({ name: 'dt_revisao', type: 'timestamp', nullable: true })
  dtRevisao: Date;

  @Column({ name: 'situacao_cadastral_id', type: 'integer', nullable: true })
  situacaoCadastralId: number;

  @Column({ name: 'data_rfb', type: 'date', nullable: true })
  dataRfb: Date;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
