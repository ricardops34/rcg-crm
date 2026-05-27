import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('system_unit')
export class SystemUnit {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ length: 100, nullable: true })
  name: string;

  @Column({ name: 'connection_name', length: 100, nullable: true })
  connectionName: string;

  @Column({ type: 'text', nullable: true })
  logo: string | null;

  @Column({ type: 'text', nullable: true })
  favicon: string | null;

  @Column({ name: 'limite_disco_mb', type: 'integer', default: 1000 })
  limiteDiscoMb: number;
}
