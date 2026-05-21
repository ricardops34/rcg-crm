import {
  Body,
  Controller,
  Get,
  Post,
  Query,
  Req,
  UseGuards,
} from '@nestjs/common';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { AtendimentoService } from '../services/atendimento.service';

@ApiTags('CRM / Atendimentos')
@ApiBearerAuth()
@Controller('crm')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('MvcList')
export class AtendimentoController {
  constructor(private readonly atendimentoService: AtendimentoService) {}

  @Get('tipos')
  async getTipos() {
    return this.atendimentoService.getTipos();
  }

  @Get('atendimentos')
  async findAll(
    @Req() req: any,
    @Query('start') start?: string,
    @Query('end') end?: string,
    @Query('vendedorId') vendedorId?: number,
  ) {
    return this.atendimentoService.findAll(
      start,
      end,
      this.resolveVendedorId(req.user, vendedorId),
    );
  }

  @Post('atendimentos')
  async save(@Req() req: any, @Body() data: any) {
    return this.atendimentoService.save(
      data,
      req.user.userId,
      this.resolveVendedorId(req.user, data.vendedorId),
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
