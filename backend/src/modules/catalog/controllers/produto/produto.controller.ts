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
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { ProdutoService } from '../../services/produto/produto.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';
import { CreateProdutoDto, UpdateProdutoDto, ProdutoResponseDto } from '../../dto/produto.dto';

@ApiTags('Catálogo / Produtos')
@ApiBearerAuth()
@Controller('produtos')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('ProdutoList')
export class ProdutoController {
  constructor(private readonly produtoService: ProdutoService) {}

  @Get()
  @ApiOperation({ summary: 'Lista produtos com filtros' })
  @ApiResponse({ status: 200, type: [ProdutoResponseDto] })
  findAll(@Query() query: any) {
    return this.produtoService.findAll(query);
  }

  @Get('categorias')
  @ApiOperation({ summary: 'Obtém lista de categorias de produtos' })
  findCategorias() {
    return this.produtoService.findCategorias();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Busca um produto pelo ID' })
  @ApiResponse({ status: 200, type: ProdutoResponseDto })
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.produtoService.findOne(id);
  }

  @Post()
  @ApiOperation({ summary: 'Cadastra um novo produto' })
  @ApiBody({ type: CreateProdutoDto })
  @ApiResponse({ status: 201, type: ProdutoResponseDto })
  async create(@Body() data: CreateProdutoDto) {
    return this.produtoService.save(data);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um produto' })
  @ApiBody({ type: UpdateProdutoDto })
  @ApiResponse({ status: 200, type: ProdutoResponseDto })
  async update(@Param('id', ParseIntPipe) id: number, @Body() data: UpdateProdutoDto) {
    return this.produtoService.save({ ...data, id });
  }


  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    return this.produtoService.remove(id);
  }
}
