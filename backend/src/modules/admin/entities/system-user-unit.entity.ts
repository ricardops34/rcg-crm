import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { SystemUser } from './system-user.entity';
import { SystemUnit } from './system-unit.entity';

@Entity('system_user_unit')
export class SystemUserUnit {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'system_user_id' })
  systemUserId: number;

  @ManyToOne(() => SystemUser)
  @JoinColumn({ name: 'system_user_id' })
  systemUser: SystemUser;

  @Column({ name: 'system_unit_id' })
  systemUnitId: number;

  @ManyToOne(() => SystemUnit)
  @JoinColumn({ name: 'system_unit_id' })
  systemUnit: SystemUnit;
}
