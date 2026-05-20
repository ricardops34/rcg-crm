import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('venda_mes_cliente')
export class VendaMesCliente {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'filial_id', type: 'integer', nullable: true })
  filialId: number;

  @Column({ name: 'empresa_id', type: 'integer', nullable: true })
  empresaId: number;

  @Column({ name: 'cliente_id', type: 'integer' })
  clienteId: number;

  @Column({ type: 'char', length: 4 })
  ano: string;

  @Column({ type: 'float', default: 0 })
  janeiro: number;

  @Column({ type: 'float', default: 0 })
  fevereiro: number;

  @Column({ type: 'char', length: 2, nullable: true })
  mes: string;

  @Column({ type: 'float', default: 0 })
  marco: number;

  @Column({ type: 'float', default: 0 })
  abril: number;

  @Column({ type: 'float', default: 0 })
  maio: number;

  @Column({ type: 'float', default: 0 })
  junho: number;

  @Column({ type: 'float', default: 0 })
  julho: number;

  @Column({ type: 'float', default: 0 })
  agosto: number;

  @Column({ type: 'float', default: 0 })
  setembro: number;

  @Column({ type: 'float', default: 0 })
  outubro: number;

  @Column({ type: 'float', default: 0 })
  novembro: number;

  @Column({ type: 'float', default: 0 })
  dezembro: number;

  @Column({ name: 'cliente_nome', length: 100, nullable: true })
  clienteNome: string;
}
