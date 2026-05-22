import {
  Controller,
  Get,
  Post,
  Body,
  UseGuards,
  Req,
} from '@nestjs/common';
import { CrmService } from '../services/crm.service';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';

@Controller('crm')
@UseGuards(JwtAuthGuard)
export class CrmController {
  constructor(private readonly crmService: CrmService) {}

  @Get('atendimentos')
  async findAll() {
    return this.crmService.findAll();
  }

  @Get('tipos')
  async getTipos() {
    return this.crmService.getTipos();
  }

  @Post('atendimentos')
  async save(@Body() data: any, @Req() req: any) {
    return this.crmService.save({
      ...data,
      vendedorId: req.user.vendedorId || data.vendedorId,
    });
  }
}
