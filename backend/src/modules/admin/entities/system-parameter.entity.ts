import { Column, Entity, JoinColumn, ManyToOne, PrimaryGeneratedColumn } from 'typeorm';
import { SystemUnit } from './system-unit.entity';

@Entity('system_parameter')
export class SystemParameter {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number | null;

  @ManyToOne(() => SystemUnit, { nullable: true })
  @JoinColumn({ name: 'system_unit_id' })
  systemUnit?: SystemUnit | null;

  @Column({ name: 'parameter', length: 150 })
  parameter: string;

  @Column({ name: 'type', length: 20 })
  type: string;

  @Column({ name: 'content', type: 'text', nullable: true })
  content: string | null;

  @Column({ name: 'system', length: 1, default: 'N' })
  system: string;

  @Column({ name: 'description', type: 'text', nullable: true })
  description: string | null;
}
