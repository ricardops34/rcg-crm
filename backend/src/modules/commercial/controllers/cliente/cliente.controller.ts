import {
  Controller,
  Get,
  Post,
  Patch,
  Delete,
  Param,
  Body,
  Query,
  UseGuards,
  ParseIntPipe,
} from '@nestjs/common';
import { ClienteService } from '../../services/cliente/cliente.service';
import { ClienteDetailsService } from '../../services/cliente/cliente-details.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../admin/guards/permissions.guard';
import { ControllerName } from '../../../admin/decorators/controller-name.decorator';

@Controller('commercial/clientes')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('ClienteList')
export class ClienteController {
  constructor(
    private readonly clienteService: ClienteService,
    private readonly detailsService: ClienteDetailsService,
  ) {}

  @Get()
  async findAll(
    @Query('page') page: number = 1,
    @Query('limit') limit: number = 10,
  ) {
    const [items, total] = await this.clienteService.findAll(page, limit);
    return {
      items,
      total,
      hasNext: total > page * limit,
    };
  }

  @Get(':id')
  async findOne(@Param('id', ParseIntPipe) id: number) {
    return this.clienteService.findOne(id);
  }

  @Get(':id/comodato')
  async getComodato(@Param('id', ParseIntPipe) id: number) {
    return this.detailsService.getComodato(id);
  }

  @Get(':id/mix')
  async getMix(@Param('id', ParseIntPipe) id: number) {
    return this.detailsService.getMix(id);
  }

  @Get(':id/financeiro')
  async getFinanceiro(@Param('id', ParseIntPipe) id: number) {
    return this.detailsService.getFinanceiro(id);
  }

  @Get(':id/notas')
  async getNotas(@Param('id', ParseIntPipe) id: number) {
    return this.detailsService.getNotasFiscais(id);
  }

  @Get(':id/atendimentos')
  async getAtendimentos(@Param('id', ParseIntPipe) id: number) {
    return this.detailsService.getAtendimentos(id);
  }

  @Get(':id/sugestoes')
  async getPurchaseSuggestion(@Param('id', ParseIntPipe) id: number) {
    return this.detailsService.getPurchaseSuggestion(id);
  }

  @Post()
  async create(@Body() data: any) {
    return this.clienteService.create(data);
  }

  @Patch(':id')
  async update(@Param('id', ParseIntPipe) id: number, @Body() data: any) {
    return this.clienteService.update(id, data);
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    return this.clienteService.remove(id);
  }
}
