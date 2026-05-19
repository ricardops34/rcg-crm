import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { SystemModule } from './system-module.entity';

@Entity('system_program')
export class SystemProgram {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100, nullable: true })
  name: string;

  @Column({ length: 100, nullable: true })
  controller: string;

  @Column({ name: 'system_module_id', type: 'integer', nullable: true })
  systemModuleId: number;

  @ManyToOne(() => SystemModule)
  @JoinColumn({ name: 'system_module_id' })
  systemModule: SystemModule;

  @Column({ type: 'integer', default: 0 })
  order: number;

  @Column({ length: 100, nullable: true })
  icon: string;

  @Column({ type: 'text', nullable: true })
  actions: string;
}
