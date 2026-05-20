import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('regiao_cliente')
export class RegiaoCliente {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 10, nullable: true })
  codErp: string;

  @Column({ type: 'text', nullable: true })
  descricao: string;

  @Column({ type: 'text', nullable: true })
  cor: string;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
