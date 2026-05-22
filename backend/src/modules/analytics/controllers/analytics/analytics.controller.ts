import { Controller, Get, Query, UseGuards, Req } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse } from '@nestjs/swagger';
import { AnalyticsService } from '../../services/analytics/analytics.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';
import { DashboardStatsDto, MvcItemDto } from '../../dto/analytics.dto';

@ApiTags('BI / Analytics')
@ApiBearerAuth()
@Controller('analytics')
@UseGuards(JwtAuthGuard, PermissionsGuard)
export class AnalyticsController {
  constructor(private readonly analyticsService: AnalyticsService) {}

  @Get('dashboard')
  @RequirePermission('DashboardVendedor')
  @ApiOperation({ summary: 'Obtém estatísticas gerais de vendas e metas para o dashboard' })
  @ApiResponse({ status: 200, type: DashboardStatsDto })
  async getDashboard(
    @Req() req: any,
    @Query('year') year: string,
    @Query('month') month: string,
    @Query('vendedorId') vendedorId?: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    const m = parseInt(month) || new Date().getMonth() + 1;
    let vId = vendedorId ? parseInt(vendedorId) : undefined;

    const isGerente = req.user?.roles?.includes('ADMIN') || req.user?.roles?.includes('GERENTE');

    if (!vId && req.user && !isGerente) {
      vId =
        (await this.analyticsService.getVendedorIdByUser(req.user.userId)) ||
        undefined;
    }

    return this.analyticsService.getDashboardStats(y, m, vId);
  }

  @Get('mvc')
  @RequirePermission('MvcList')
  @ApiOperation({ summary: 'Obtém os dados da Média de Venda do Cliente (MVC)' })
  @ApiResponse({ status: 200, type: [MvcItemDto] })
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

    const isGerente = req.user?.roles?.includes('ADMIN') || req.user?.roles?.includes('GERENTE');

    if (!vId && req.user && !isGerente) {
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
