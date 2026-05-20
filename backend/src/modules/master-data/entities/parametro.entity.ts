import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('parametro')
export class Parametro {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ length: 50 })
  parametro: string;

  @Column({ length: 100, nullable: true })
  conteudo: string;

  @Column({ type: 'char', length: 1 })
  tipo: string;
}
