import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('system_group')
export class SystemGroup {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100, nullable: true })
  name: string;

  @Column({ length: 36, nullable: true })
  uuid: string;

  @Column({ length: 50, nullable: true })
  role: string;
}
