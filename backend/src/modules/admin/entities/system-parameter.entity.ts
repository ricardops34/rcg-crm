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

  @Column({ name: 'system_parameter', length: 150 })
  systemParameter: string;

  @Column({ name: 'system_type', length: 20 })
  systemType: string;

  @Column({ name: 'system_content', type: 'text', nullable: true })
  systemContent: string | null;

  @Column({ name: 'system_system', length: 1, default: 'N' })
  systemSystem: string;
}
