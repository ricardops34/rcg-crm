import {
  Controller,
  Get,
  Post,
  Body,
  Param,
  ParseIntPipe,
  Query,
  UseGuards,
  Req,
} from '@nestjs/common';
import { NegociacaoService } from '../services/negociacao.service';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';

@Controller('finance/negociacoes')
@UseGuards(JwtAuthGuard)
export class NegociacaoController {
  constructor(private readonly negociacaoService: NegociacaoService) {}

  @Get('inadimplentes')
  async getDelinquentClients(@Req() req: any) {
    const vendedorId = req.user.isGerente ? undefined : req.user.vendedorId;
    return this.negociacaoService.getDelinquentClients(vendedorId);
  }

  @Get('titulos-vencidos/:clienteId')
  async getOverdueTitles(@Param('clienteId', ParseIntPipe) clienteId: number) {
    return this.negociacaoService.getOverdueTitles(clienteId);
  }

  @Post()
  async create(@Body() data: any, @Req() req: any) {
    return this.negociacaoService.createNegotiation({
      ...data,
      vendedorId: req.user.vendedorId || data.vendedorId,
    });
  }

  @Get()
  async findAll() {
    return this.negociacaoService.findAll();
  }
}
