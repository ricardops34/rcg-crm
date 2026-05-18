import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { SystemGroup } from './system-group.entity';
import { SystemProgram } from './system-program.entity';

@Entity('system_group_program')
export class SystemGroupProgram {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'system_group_id' })
  systemGroupId: number;

  @ManyToOne(() => SystemGroup)
  @JoinColumn({ name: 'system_group_id' })
  systemGroup: SystemGroup;

  @Column({ name: 'system_program_id' })
  systemProgramId: number;

  @ManyToOne(() => SystemProgram)
  @JoinColumn({ name: 'system_program_id' })
  systemProgram: SystemProgram;

  @Column({ type: 'text', nullable: true })
  actions: string;
}
