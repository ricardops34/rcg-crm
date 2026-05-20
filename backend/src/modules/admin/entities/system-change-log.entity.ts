import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
} from 'typeorm';

@Entity('system_change_log')
export class SystemChangeLog {
  @PrimaryGeneratedColumn()
  id: number;

  @CreateDateColumn({ name: 'log_time', nullable: true })
  logTime: Date;

  @Column({ length: 100, nullable: true })
  login: string;

  @Column({ length: 100 })
  tablename: string;

  @Column({ length: 100, nullable: true })
  primarykey: string;

  @Column({ length: 100, nullable: true })
  pkvalue: string;

  @Column({ length: 20 })
  operation: string;

  @Column({ length: 100, nullable: true })
  columnname: string;

  @Column({ type: 'text', nullable: true })
  oldvalue: string;

  @Column({ type: 'text', nullable: true })
  newvalue: string;

  @Column({ name: 'access_ip', length: 45, nullable: true })
  accessIp: string;

  @Column({ name: 'transaction_id', length: 100, nullable: true })
  transactionId: string;
}
