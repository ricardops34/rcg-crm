import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('notasaida_xml')
export class NotaSaidaXml {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'nota_saida_id', type: 'integer', nullable: true })
  notaSaidaId: number;

  @Column({ name: 'xml_sig', type: 'text', nullable: true })
  xmlSig: string;

  @Column({ name: 'xml_tss', type: 'text', nullable: true })
  xmlTss: string;

  @Column({ name: 'xml_cancelamento', type: 'text', nullable: true })
  xmlCancelamento: string;

  @Column({ name: 'nota_fiscal', length: 9, nullable: true })
  notaFiscal: string;

  @Column({ name: 'serie_fiscal', length: 3, nullable: true })
  serieFiscal: string;

  @Column({ length: 100, nullable: true })
  chave: string;

  @Column({ length: 100, nullable: true })
  protocolo: string;

  @Column({ length: 2, nullable: true })
  modelo: string;

  @Column({ length: 14, nullable: true })
  destinatario: string;

  @Column({ length: 14, nullable: true })
  remetente: string;

  @Column({ length: 1, nullable: true })
  situcao: string;

  @Column({ name: 'situcao_cancelamento', length: 1, nullable: true })
  situcaoCancelamento: string;

  @Column({ name: 'situcao_email', length: 1, nullable: true })
  situcaoEmail: string;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ name: 'data_nfe', type: 'date', nullable: true })
  dataNfe: Date;

  @Column({ name: 'hora_nfe', type: 'time', nullable: true })
  horaNfe: string;

  @Column({ length: 4, nullable: true })
  ano: string;

  @Column({ length: 2, nullable: true })
  mes: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
