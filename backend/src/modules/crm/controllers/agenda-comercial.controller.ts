import { Controller, Get, Query, Req, UseGuards } from '@nestjs/common';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse } from '@nestjs/swagger';
import { AgendaComercialService } from '../services/agenda-comercial.service';
import { AgendaResponseDto } from '../dto/agenda.dto';

@ApiTags('CRM / Agenda')
@ApiBearerAuth()
@Controller('crm/agenda')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('MvcList')
export class AgendaComercialController {
  constructor(private readonly agendaComercialService: AgendaComercialService) {}

  @Get()
  @ApiOperation({ summary: 'Obtém eventos de agenda (Vendas e Atendimentos) para um período' })
  @ApiResponse({ status: 200, type: AgendaResponseDto })
  async getAgenda(
    @Req() req: any,
    @Query('view') view: 'month' | 'week' | 'day' = 'month',
    @Query('date') date?: string,
    @Query('vendedorId') vendedorId?: number,
    @Query('atendimentoTipoId') atendimentoTipoId?: number,
    @Query('start') start?: string,
    @Query('end') end?: string,
  ) {
    return this.agendaComercialService.getAgenda(
      view,
      date || new Date().toISOString().slice(0, 10),
      this.resolveVendedorId(req.user, vendedorId),
      atendimentoTipoId ? Number(atendimentoTipoId) : undefined,
      start,
      end,
    );
  }

  private resolveVendedorId(user: any, vendedorId?: number) {
    if (user?.isGerente) {
      return vendedorId;
    }

    if (
      vendedorId &&
      user?.managedVendedorIds?.includes(Number(vendedorId))
    ) {
      return Number(vendedorId);
    }

    return user?.vendedorId || vendedorId;
  }
}
