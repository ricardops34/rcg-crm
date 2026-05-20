import { Entity, Column, PrimaryColumn } from 'typeorm';

@Entity('system_preference')
export class SystemPreference {
  @PrimaryColumn({ length: 255 })
  id: string;

  @Column({ type: 'text', nullable: true })
  preference: string;
}
