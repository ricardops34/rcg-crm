import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn, ManyToOne, JoinColumn, OneToMany } from 'typeorm';
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

  @Column({ name: 'nota_fiscal', length: 9, nullable: true })
  notaFiscal: string;

  @Column({ name: 'serie_fiscal', length: 3, nullable: true })
  serieFiscal: string;

  @Column({ name: 'chave_nfe', length: 100, nullable: true })
  chaveNfe: string;

  @Column({ name: 'dt_emissao', type: 'date', nullable: true })
  dtEmissao: Date;

  @Column({ name: 'vlr_bruto', type: 'float', nullable: true })
  vlrBruto: number;

  @Column({ name: 'vlr_total', type: 'float', nullable: true })
  vlrTotal: number;

  @Column({ name: 'reg_ativo', type: 'char', length: 1, nullable: true })
  regAtivo: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;

  @OneToMany(() => NotaSaidaItem, (item) => item.notaSaida)
  itens: NotaSaidaItem[];
}
