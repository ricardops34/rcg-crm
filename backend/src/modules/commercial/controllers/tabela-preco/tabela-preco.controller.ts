import { Controller, Get, Param, ParseIntPipe, UseGuards } from '@nestjs/common';
import { TabelaPrecoService } from '../../services/tabela-preco/tabela-preco.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('tabela-preco')
@UseGuards(JwtAuthGuard)
export class TabelaPrecoController {
  constructor(private readonly tabelaPrecoService: TabelaPrecoService) {}

  @Get()
  findAll() {
    return this.tabelaPrecoService.findAll();
  }

  @Get(':id')
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.tabelaPrecoService.findOne(id);
  }

  @Get(':id/produto/:produtoId')
  getProductPrice(
    @Param('id', ParseIntPipe) id: number,
    @Param('produtoId', ParseIntPipe) produtoId: number
  ) {
    return this.tabelaPrecoService.getProductPrice(id, produtoId);
  }
}
