import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { SystemUser } from './system-user.entity';
import { SystemUnit } from './system-unit.entity';

@Entity('system_user_units')
export class SystemUserUnit {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'system_users_id', type: 'integer' })
  systemUsersId: number;

  @ManyToOne(() => SystemUser, (user) => (user as any).userUnits)
  @JoinColumn({ name: 'system_users_id' })
  systemUser: SystemUser;

  @Column({ name: 'system_unit_id', type: 'integer' })
  systemUnitId: number;

  @ManyToOne(() => SystemUnit)
  @JoinColumn({ name: 'system_unit_id' })
  systemUnit: SystemUnit;
}
