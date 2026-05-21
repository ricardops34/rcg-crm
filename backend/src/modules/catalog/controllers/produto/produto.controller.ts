import {
  Controller,
  Get,
  Post,
  Put,
  Delete,
  Param,
  Body,
  ParseIntPipe,
  Query,
  UseGuards,
} from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { ProdutoService } from '../../services/produto/produto.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Catálogo / Produtos')
@ApiBearerAuth()
@Controller('produtos')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('ProdutoList')
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

  @Post()
  async create(@Body() data: any) {
    return this.produtoService.save(data);
  }

  @Put(':id')
  async update(@Param('id', ParseIntPipe) id: number, @Body() data: any) {
    return this.produtoService.save({ ...data, id });
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    return this.produtoService.remove(id);
  }
}
