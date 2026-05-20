import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('atendimento_tipo')
export class AtendimentoTipo {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'cod_erp', length: 10, nullable: true })
  codErp: string;

  @Column({ length: 50, nullable: true })
  descricao: string;

  @Column({ type: 'text', nullable: true })
  cor: string;

  @Column({ length: 100, nullable: true })
  icone: string;

  @Column({ type: 'char', length: 1, nullable: true })
  retorno: string;

  @Column({ type: 'char', length: 1, nullable: true })
  editar: string;

  @Column({ type: 'char', length: 1, nullable: true })
  excluir: string;

  @Column({ type: 'char', length: 1, nullable: true })
  atendimento: string;

  @Column({ type: 'char', length: 1, nullable: true })
  venda: string;

  @Column({ type: 'char', length: 1, nullable: true })
  cadastro: string;

  @Column({ type: 'char', length: 1, nullable: true })
  cobranca: string;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}
