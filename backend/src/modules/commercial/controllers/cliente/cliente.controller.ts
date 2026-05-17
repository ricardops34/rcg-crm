import { Controller, Get, Param, Query, UseGuards } from '@nestjs/common';
import { ClienteService } from '../../services/cliente/cliente.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('commercial/clientes')
@UseGuards(JwtAuthGuard)
export class ClienteController {
  constructor(private readonly clienteService: ClienteService) {}

  @Get()
  async findAll(@Query('page') page: number = 1, @Query('limit') limit: number = 10) {
    const [items, total] = await this.clienteService.findAll(page, limit);
    return {
      items,
      total,
      hasNext: total > page * limit
    };
  }

  @Get(':id')
  async findOne(@Param('id') id: number) {
    return this.clienteService.findOne(id);
  }
}
