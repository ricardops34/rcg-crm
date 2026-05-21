import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Post,
  Put,
  Query,
  UseGuards,
} from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse } from '@nestjs/swagger';
import { TabelaPrecoService } from '../../services/tabela-preco/tabela-preco.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';
import { TabelaPrecoResponseDto, TabelaPrecoItemResponseDto } from '../../dto/tabela-preco.dto';

@ApiTags('Commercial / Tabelas de Preços')
@ApiBearerAuth()
@Controller('commercial/tabelas-precos')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('TabelaPrecoList')
export class TabelaPrecoController {
  constructor(private readonly tabelaPrecoService: TabelaPrecoService) {}

  @Get()
  @ApiOperation({ summary: 'Lista todas as tabelas de preço' })
  @ApiResponse({ status: 200, type: [TabelaPrecoResponseDto] })
  async findAll(
    @Query('page') page: number = 1,
    @Query('limit') limit: number = 100,
  ) {
    const [items, total] = await this.tabelaPrecoService.findAll(page, limit);
    return { items, total };
  }

  @Get(':id')
  @ApiOperation({ summary: 'Busca uma tabela de preço pelo ID' })
  @ApiResponse({ status: 200, type: TabelaPrecoResponseDto })
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.tabelaPrecoService.findOne(id);
  }

  @Get(':id/itens')
  @ApiOperation({ summary: 'Lista os itens (preços) de uma tabela' })
  @ApiResponse({ status: 200, type: [TabelaPrecoItemResponseDto] })
  findItems(@Param('id', ParseIntPipe) id: number) {
    return this.tabelaPrecoService.findItems(id);
  }

  @Get(':id/produto/:produtoId')
  getProductPrice(
    @Param('id', ParseIntPipe) id: number,
    @Param('produtoId', ParseIntPipe) produtoId: number,
  ) {
    return this.tabelaPrecoService.getProductPrice(id, produtoId);
  }

  @Post()
  create(@Body() data: any) {
    return this.tabelaPrecoService.save(data);
  }

  @Put(':id')
  update(@Param('id', ParseIntPipe) id: number, @Body() data: any) {
    return this.tabelaPrecoService.save({ ...data, id });
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    await this.tabelaPrecoService.remove(id);
    return { success: true };
  }
}
