import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity('tabela_preco')
export class TabelaPreco {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'empresa_id', type: 'integer', nullable: true })
  empresaId: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ name: 'cod_erp', length: 3 })
  codErp: string;

  @Column({ length: 50 })
  descricao: string;

  @Column({ type: 'char', length: 1, nullable: true })
  status: string;

  @Column({ name: 'dt_inicio', type: 'date', nullable: true })
  dtInicio: Date;

  @Column({ name: 'dt_fim', type: 'date', nullable: true })
  dtFim: Date;

  @Column({ type: 'char', length: 1, nullable: true })
  utiliza: string;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @CreateDateColumn({ name: 'dt_inclusao' })
  dtInclusao: Date;

  @UpdateDateColumn({ name: 'dt_alteracao' })
  dtAlteracao: Date;
}
