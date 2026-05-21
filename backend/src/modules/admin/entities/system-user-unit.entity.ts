import { Entity, PrimaryColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { SystemUser } from './system-user.entity';
import { SystemUnit } from './system-unit.entity';

@Entity('system_user_unit')
export class SystemUserUnit {
  @PrimaryColumn()
  id: number;

  @Column({ name: 'system_user_id', type: 'integer' })
  systemUserId: number;

  @ManyToOne(() => SystemUser, (user) => (user as any).userUnits)
  @JoinColumn({ name: 'system_user_id' })
  systemUser: SystemUser;

  @Column({ name: 'system_unit_id', type: 'integer' })
  systemUnitId: number;

  @ManyToOne(() => SystemUnit)
  @JoinColumn({ name: 'system_unit_id' })
  systemUnit: SystemUnit;
}
