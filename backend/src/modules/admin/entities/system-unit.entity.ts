import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('system_unit')
export class SystemUnit {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100, nullable: true })
  name: string;

  @Column({ name: 'connection_name', length: 100, nullable: true })
  connectionName: string;
}
