import { Controller, Get, Query, Req, UseGuards } from '@nestjs/common';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../admin/guards/permissions.guard';
import { ControllerName } from '../../admin/decorators/controller-name.decorator';
import { AgendaComercialService } from '../services/agenda-comercial.service';

@Controller('crm/agenda')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('MvcList')
export class AgendaComercialController {
  constructor(private readonly agendaComercialService: AgendaComercialService) {}

  @Get()
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
