import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  ManyToOne,
  JoinColumn,
} from 'typeorm';
import { Negociacao } from './negociacao.entity';
import { TituloReceber } from './titulo-receber.entity';

@Entity('negociacao_titulo_receber')
export class NegociacaoTituloReceber {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'negociacao_id', type: 'integer' })
  negociacaoId: number;

  @ManyToOne(() => Negociacao, (n) => n.titulos)
  @JoinColumn({ name: 'negociacao_id' })
  negociacao: Negociacao;

  @Column({ name: 'titulo_receber_id', type: 'integer' })
  tituloReceberId: number;

  @ManyToOne(() => TituloReceber)
  @JoinColumn({ name: 'titulo_receber_id' })
  tituloReceber: TituloReceber;
}
