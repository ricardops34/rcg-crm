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
    @Req() req: any,
    @Query('year') year: string,
    @Query('month') month: string,
    @Query('vendedorId') vendedorId?: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    const m = parseInt(month) || new Date().getMonth() + 1;
    let vId = vendedorId ? parseInt(vendedorId) : undefined;

    if (!vId && req.user) {
      vId =
        (await this.analyticsService.getVendedorIdByUser(req.user.userId)) ||
        undefined;
    }

    return this.analyticsService.getDashboardStats(y, m, vId);
  }

  @Get('mvc')
  @ControllerName('MvcList')
  async getMvc(
    @Req() req: any,
    @Query('year') year: string,
    @Query('vendedorId') vendedorId?: string,
    @Query('estadoId') estadoId?: string,
    @Query('municipioId') municipioId?: string,
    @Query('dias') dias?: string,
    @Query('situacao') situacao?: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    let vId = vendedorId ? parseInt(vendedorId) : undefined;

    if (!vId && req.user) {
      vId =
        (await this.analyticsService.getVendedorIdByUser(req.user.userId)) ||
        undefined;
    }

    return this.analyticsService.getMvcData(y, vId, {
      estadoId: estadoId ? parseInt(estadoId) : undefined,
      municipioId: municipioId ? parseInt(municipioId) : undefined,
      dias: dias ? parseInt(dias) : undefined,
      situacao,
    });
  }
}
