import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('notasaida_xml')
export class NotaSaidaXml {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'nota_saida_id', type: 'integer' })
  notaSaidaId: number;

  @Column({ type: 'text', nullable: true })
  xml: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
