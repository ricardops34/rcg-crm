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
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { NegociacaoService } from '../services/negociacao.service';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';
import { CreateNegociacaoDto, NegociacaoResponseDto } from '../dto/negociacao.dto';

@ApiTags('Financeiro / Negociações')
@ApiBearerAuth()
@Controller('finance/negociacoes')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('NegociacaoList')
export class NegociacaoController {
  constructor(private readonly negociacaoService: NegociacaoService) {}

  @Get('inadimplentes')
  @ApiOperation({ summary: 'Lista clientes com títulos vencidos' })
  async getDelinquentClients(@Req() req: any) {
    const vendedorId = req.user.isGerente ? undefined : req.user.vendedorId;
    return this.negociacaoService.getDelinquentClients(vendedorId);
  }

  @Get('titulos-vencidos/:clienteId')
  @ApiOperation({ summary: 'Obtém títulos vencidos de um cliente específico' })
  async getOverdueTitles(@Param('clienteId', ParseIntPipe) clienteId: number) {
    return this.negociacaoService.getOverdueTitles(clienteId);
  }

  @Post()
  @ApiOperation({ summary: 'Cria uma nova proposta de renegociação' })
  @ApiBody({ type: CreateNegociacaoDto })
  @ApiResponse({ status: 201, type: NegociacaoResponseDto })
  async create(@Body() data: CreateNegociacaoDto, @Req() req: any) {
    return this.negociacaoService.createNegotiation({
      clienteId: data.clienteId,
      tituloIds: data.titulos,
      observacao: data.observacao || '',
      vendedorId: req.user.vendedorId || data.vendedorId,
    });
  }

  @Get()
  @ApiOperation({ summary: 'Lista todas as negociações realizadas' })
  @ApiResponse({ status: 200, type: [NegociacaoResponseDto] })
  async findAll() {
    return this.negociacaoService.findAll();
  }
}
