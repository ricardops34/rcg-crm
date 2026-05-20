import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { SystemUser } from './system-user.entity';
import { SystemGroup } from './system-group.entity';

@Entity('system_user_groups')
export class SystemUserGroup {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'system_users_id' })
  systemUsersId: number;

  @ManyToOne(() => SystemUser)
  @JoinColumn({ name: 'system_users_id' })
  systemUser: SystemUser;

  @Column({ name: 'system_group_id' })
  systemGroupId: number;

  @ManyToOne(() => SystemGroup)
  @JoinColumn({ name: 'system_group_id' })
  systemGroup: SystemGroup;
}
