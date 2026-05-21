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
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { ClienteService } from '../../services/cliente/cliente.service';
import { ClienteDetailsService } from '../../services/cliente/cliente-details.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';
import { CreateClienteDto, UpdateClienteDto, ClienteResponseDto, PaginatedClienteResponseDto } from '../../dto/cliente.dto';

@ApiTags('Commercial / Clientes')
@ApiBearerAuth()
@Controller('commercial/clientes')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('ClienteList')
export class ClienteController {
  constructor(
    private readonly clienteService: ClienteService,
    private readonly detailsService: ClienteDetailsService,
  ) {}

  @Get()
  @ApiOperation({ summary: 'Lista clientes com paginação (padrão PO-UI)' })
  @ApiResponse({ status: 200, type: PaginatedClienteResponseDto })
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
  @ApiOperation({ summary: 'Busca detalhes completos de um cliente pelo ID' })
  @ApiResponse({ status: 200, type: ClienteResponseDto })
  async findOne(@Param('id', ParseIntPipe) id: number) {
    return this.clienteService.findOne(id);
  }

  @Post()
  @ApiOperation({ summary: 'Cadastra um novo cliente' })
  @ApiBody({ type: CreateClienteDto })
  @ApiResponse({ status: 201, type: ClienteResponseDto })
  async create(@Body() data: CreateClienteDto) {
    return this.clienteService.create(data);
  }

  @Patch(':id')
  @ApiOperation({ summary: 'Atualiza dados de um cliente' })
  @ApiBody({ type: UpdateClienteDto })
  @ApiResponse({ status: 200, type: ClienteResponseDto })
  async update(@Param('id', ParseIntPipe) id: number, @Body() data: UpdateClienteDto) {
    return this.clienteService.update(id, data);
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    return this.clienteService.remove(id);
  }
}
