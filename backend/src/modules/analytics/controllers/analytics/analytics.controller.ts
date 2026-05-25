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
    @Query('vendedor_id') vendedor_id?: string,
    @Query('dias') dias?: string,
    @Query('situacao') situacao?: string,
    @Query('search') search?: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    const vQuery = vendedor_id || vendedorId;
    let vId = vQuery ? parseInt(vQuery) : undefined;

    // Regra estrita: Admin, Supervisor e Gerente
    const isGerente = 
      req.user?.roles?.includes('ADMIN') || 
      req.user?.roles?.includes('SUPERVISOR') || 
      req.user?.roles?.includes('GERENTE');

    if (!vId && req.user && !isGerente) {
      vId =
        (await this.analyticsService.getVendedorIdByUser(req.user.userId)) ||
        undefined;
    }

    return this.analyticsService.getMvcData(y, vId, {
      dias: dias ? parseInt(dias) : undefined,
      situacao,
      search,
    });
  }

  @Get('mvc/table')
  @RequirePermission('MvcList')
  @ApiOperation({ summary: 'Obtém os dados da Média de Venda do Cliente (MVC) no formato de tabela do PO-UI' })
  async getMvcTable(
    @Req() req: any,
    @Query('year') year: string,
    @Query('vendedorId') vendedorId?: string,
    @Query('vendedor_id') vendedor_id?: string,
    @Query('dias') dias?: string,
    @Query('situacao') situacao?: string,
    @Query('search') search?: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    const vQuery = vendedor_id || vendedorId;
    let vId = vQuery ? parseInt(vQuery) : undefined;

    // Regra estrita: Admin, Supervisor e Gerente
    const isGerente = 
      req.user?.roles?.includes('ADMIN') || 
      req.user?.roles?.includes('SUPERVISOR') || 
      req.user?.roles?.includes('GERENTE');

    if (!vId && req.user && !isGerente) {
      vId =
        (await this.analyticsService.getVendedorIdByUser(req.user.userId)) ||
        undefined;
    }

    const items = await this.analyticsService.getMvcData(y, vId, {
      dias: dias ? parseInt(dias) : undefined,
      situacao,
      search,
    });

    const mappedItems = items.map((item: any) => ({
      ...item,
      $rowColor: item.difference < 0 ? '#FFF9A7' : undefined,
    }));

    return { items: mappedItems, hasNext: false };
  }
}
