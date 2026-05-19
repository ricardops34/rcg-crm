import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('system_module')
export class SystemModule {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100 })
  name: string;

  @Column({ length: 100, nullable: true })
  icon: string;

  @Column({ type: 'integer', default: 0 })
  order: number;
}
