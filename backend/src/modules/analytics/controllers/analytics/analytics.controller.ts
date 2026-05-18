import { Controller, Get, Query, UseGuards, Req } from '@nestjs/common';
import { AnalyticsService } from '../../services/analytics/analytics.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../admin/guards/permissions.guard';
import { ControllerName } from '../../../admin/decorators/controller-name.decorator';

@Controller('analytics')
@UseGuards(JwtAuthGuard, PermissionsGuard)
export class AnalyticsController {
  constructor(private readonly analyticsService: AnalyticsService) {}

  @Get('dashboard')
  @ControllerName('DashboardVendedor')
  async getDashboard(
    @Query('year') year: string,
    @Query('month') month: string,
    @Query('vendedorId') vendedorId?: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    const m = parseInt(month) || new Date().getMonth() + 1;
    const vId = vendedorId ? parseInt(vendedorId) : undefined;

    return this.analyticsService.getDashboardStats(y, m, vId);
  }

  @Get('mvc')
  @ControllerName('MvcList')
  async getMvc(
    @Query('year') year: string,
    @Query('vendedorId') vendedorId: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    const vId = parseInt(vendedorId);

    return this.analyticsService.getMvcData(y, vId);
  }
}
