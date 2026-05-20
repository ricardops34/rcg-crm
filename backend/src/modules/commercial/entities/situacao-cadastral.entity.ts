import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('situacao_cadastral')
export class SituacaoCadastral {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100 })
  descricao: string;

  @Column({ name: 'motivo_bloqueio_id', type: 'integer', nullable: true })
  motivoBloqueioId: number;

  @CreateDateColumn({ name: 'dt_inclusao', nullable: true })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao', nullable: true })
  dtAlteracao: Date;
}

