import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AnalyticsService } from './services/analytics/analytics.service';
import { AnalyticsController } from './controllers/analytics/analytics.controller';
import { Vendedor } from '../commercial/entities/vendedor.entity';

@Module({
  imports: [TypeOrmModule.forFeature([Vendedor])],
  providers: [AnalyticsService],
  controllers: [AnalyticsController],
})
export class AnalyticsModule {}
