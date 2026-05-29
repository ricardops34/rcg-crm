import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  ManyToOne,
  OneToMany,
  JoinColumn,
} from 'typeorm';
import { SystemProgram } from './system-program.entity';
import { SystemUnit } from './system-unit.entity';
import { SystemUserGroup } from './system-user-group.entity';
import { SystemUserUnit } from './system-user-unit.entity';
import { SystemUserProgram } from './system-user-program.entity';

@Entity('system_users')
export class SystemUser {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100, nullable: true })
  name: string;

  @Column({ length: 100, nullable: true })
  login: string;

  @Column({ length: 100, nullable: true })
  password: string;

  @Column({ length: 100, nullable: true })
  email: string;

  @Column({ name: 'frontpage_id', type: 'integer', nullable: true })
  frontpageId: number;

  @ManyToOne(() => SystemProgram)
  @JoinColumn({ name: 'frontpage_id' })
  frontpage: SystemProgram;

  @Column({ name: 'system_unit_id', type: 'integer', nullable: true })
  systemUnitId: number;

  @ManyToOne(() => SystemUnit)
  @JoinColumn({ name: 'system_unit_id' })
  systemUnit: SystemUnit;

  @Column({ type: 'char', length: 1, nullable: true })
  active: string;

  @Column({
    name: 'accepted_term_policy',
    type: 'char',
    length: 1,
    nullable: true,
  })
  acceptedTermPolicy: string;

  @Column({ name: 'accepted_term_policy_at', type: 'text', nullable: true })
  acceptedTermPolicyAt: string;

  @Column({
    name: 'two_factor_enabled',
    type: 'char',
    length: 1,
    nullable: true,
  })
  twoFactorEnabled: string;

  @Column({ name: 'two_factor_type', length: 100, nullable: true })
  twoFactorType: string;

  @Column({ name: 'two_factor_secret', length: 255, nullable: true })
  twoFactorSecret: string;

  @Column({ name: 'current_session_id', length: 255, nullable: true })
  currentSessionId: string;

  @Column({ type: 'text', nullable: true })
  avatar: string;

  @Column({ type: 'date', nullable: true })
  birthday: string;

  @Column({ name: 'force_password_change', type: 'char', length: 1, nullable: true, default: 'N' })
  forcePasswordChange: string;

  @Column({ name: 'failed_login_attempts', type: 'integer', default: 0 })
  failedLoginAttempts: number;

  @Column({ name: 'locked_until', type: 'timestamp', nullable: true })
  lockedUntil: Date | null;

  @OneToMany(() => SystemUserGroup, (ug) => ug.systemUser)
  userGroups: SystemUserGroup[];

  @OneToMany(() => SystemUserUnit, (uu) => uu.systemUser)
  userUnits: SystemUserUnit[];

  @OneToMany(() => SystemUserProgram, (up) => up.systemUser)
  userPrograms: SystemUserProgram[];
}
