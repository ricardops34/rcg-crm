import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { SystemUser } from './system-user.entity';
import { SystemGroup } from './system-group.entity';

@Entity('system_user_group')
export class SystemUserGroup {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'system_user_id' })
  systemUserId: number;

  @ManyToOne(() => SystemUser)
  @JoinColumn({ name: 'system_user_id' })
  systemUser: SystemUser;

  @Column({ name: 'system_group_id' })
  systemGroupId: number;

  @ManyToOne(() => SystemGroup)
  @JoinColumn({ name: 'system_group_id' })
  systemGroup: SystemGroup;
}
