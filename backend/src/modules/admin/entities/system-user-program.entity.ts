import { Entity, PrimaryColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { SystemUser } from './system-user.entity';
import { SystemProgram } from './system-program.entity';

@Entity('system_user_program')
export class SystemUserProgram {
  @PrimaryColumn()
  id: number;

  @Column({ name: 'system_user_id', type: 'integer' })
  systemUserId: number;

  @ManyToOne(() => SystemUser)
  @JoinColumn({ name: 'system_user_id' })
  systemUser: SystemUser;

  @Column({ name: 'system_program_id', type: 'integer' })
  systemProgramId: number;

  @ManyToOne(() => SystemProgram)
  @JoinColumn({ name: 'system_program_id' })
  systemProgram: SystemProgram;
}
