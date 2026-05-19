import {
  Controller,
  Get,
  Param,
  ParseIntPipe,
  Query,
  UseGuards,
} from '@nestjs/common';
import { ProdutoService } from '../../services/produto/produto.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('produtos')
@UseGuards(JwtAuthGuard)
export class ProdutoController {
  constructor(private readonly produtoService: ProdutoService) {}

  @Get()
  findAll(@Query() query: any) {
    return this.produtoService.findAll(query);
  }

  @Get('categorias')
  findCategorias() {
    return this.produtoService.findCategorias();
  }

  @Get(':id')
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.produtoService.findOne(id);
  }
}
