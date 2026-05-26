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

    // Verificação de Gestão blindada (Admin, Gerente, Supervisor ou superusuário ID 1)
    const isGerente = 
      req.user?.userId === 1 ||
      req.user?.username?.toLowerCase() === 'admin' ||
      req.user?.roles?.some((r: string) => ['ADMIN', 'GERENTE', 'SUPERVISOR', 'ADMINISTRADOR'].includes(r.toUpperCase()));

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

    // Regra estrita de Gestão blindada (Admin, Supervisor, Gerente ou superusuário ID 1)
    const isGerente = 
      req.user?.userId === 1 ||
      req.user?.username?.toLowerCase() === 'admin' ||
      req.user?.roles?.some((r: string) => ['ADMIN', 'GERENTE', 'SUPERVISOR', 'ADMINISTRADOR'].includes(r.toUpperCase()));

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
    @Query('diasDe') diasDe?: string,
    @Query('diasAte') diasAte?: string,
    @Query('situacao') situacao?: string,
    @Query('search') search?: string,
    @Query('cliente_nome') cliente_nome?: string,
    @Query('fantasia') fantasia?: string,
  ) {
    const y = parseInt(year) || new Date().getFullYear();
    const vQuery = vendedor_id || vendedorId;
    let vId = vQuery ? parseInt(vQuery) : undefined;

    // Regra de segurança blindada: Usuários superadministradores (ID 1), login 'admin' ou com perfil ADMIN listam todos os clientes.
    // Demais usuários são restritos à sua carteira (vendedor associado), e este filtro é obrigatório se nenhum vendedor for especificado.
    const isGerente = 
      req.user?.userId === 1 || 
      req.user?.username?.toLowerCase() === 'admin' || 
      req.user?.roles?.some((r: string) => ['ADMIN', 'GERENTE', 'SUPERVISOR', 'ADMINISTRADOR'].includes(r.toUpperCase()));

    if (!vId && req.user && !isGerente) {
      vId =
        (await this.analyticsService.getVendedorIdByUser(req.user.userId)) ||
        -999; // ID seguro inexistente para evitar vazamento caso o usuário não tenha vendedor associado
    }

    const items = await this.analyticsService.getMvcData(y, vId, {
      dias: dias ? parseInt(dias) : undefined,
      diasDe: diasDe ? parseInt(diasDe) : undefined,
      diasAte: diasAte ? parseInt(diasAte) : undefined,
      situacao,
      search,
      cliente_nome,
      fantasia,
    });

    const mappedItems = items.map((item: any) => ({
      ...item,
      $rowColor: item.difference < 0 ? '#FFF9A7' : undefined,
    }));

    return { items: mappedItems, hasNext: false };
  }
}
