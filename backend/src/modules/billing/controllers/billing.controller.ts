import {
  Controller,
  Get,
  Param,
  ParseIntPipe,
  Query,
  UseGuards,
  Header,
  Res,
} from '@nestjs/common';
import { BillingService } from '../services/billing.service';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import type { Response } from 'express';

@Controller('billing')
@UseGuards(JwtAuthGuard)
export class BillingController {
  constructor(private readonly billingService: BillingService) {}

  @Get('notas')
  findAll(@Query() query: any) {
    return this.billingService.findAll(query);
  }

  @Get('notas/comodatos')
  getComodatos(@Query() query: any) {
    return this.billingService.getComodatos(query);
  }

  @Get('notas/:id')
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.billingService.findOne(id);
  }

  @Get('notas/:id/xml')
  @Header('Content-Type', 'application/xml')
  async getXml(@Param('id', ParseIntPipe) id: number) {
    return this.billingService.getXml(id);
  }

  @Get('notas/:id/etiqueta')
  async getEtiqueta(@Param('id', ParseIntPipe) id: number) {
    return this.billingService.getLabelData(id);
  }

  @Get('notas/:id/danfe')
  @Header('Content-Type', 'application/pdf')
  async getDanfe(
    @Param('id', ParseIntPipe) id: number,
    @Res() res: Response,
  ) {
    // TODO: Implementar geração de PDF usando biblioteca NFe
    // Por enquanto, retorna erro de "Não Implementado" ou placeholder
    res.status(501).send('Geração de PDF (DANFE) ainda não implementada no Rebuild. Utilize o download do XML.');
  }
}
