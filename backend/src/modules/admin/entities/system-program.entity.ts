import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('system_program')
export class SystemProgram {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100, nullable: true })
  name: string;

  @Column({ length: 100, nullable: true })
  controller: string;

  @Column({ type: 'text', nullable: true })
  actions: string;
}
