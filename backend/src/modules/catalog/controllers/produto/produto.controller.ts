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
  UseInterceptors,
  UploadedFile,
} from '@nestjs/common';
import { FileInterceptor } from '@nestjs/platform-express';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { ProdutoService } from '../../services/produto/produto.service';
import { MulterFile } from '../../../admin/services/upload.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';
import { CreateProdutoDto, UpdateProdutoDto, ProdutoResponseDto, PaginatedProdutoResponseDto } from '../../dto/produto.dto';

@ApiTags('Catálogo / Produtos')
@ApiBearerAuth()
@Controller('produtos')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('ProdutoList')
export class ProdutoController {
  constructor(private readonly produtoService: ProdutoService) {}

  @Get()
  @ApiOperation({ summary: 'Lista produtos com filtros e paginação (padrão PO-UI)' })
  @ApiResponse({ status: 200, type: PaginatedProdutoResponseDto })
  async findAll(@Query() query: any) {
    const [items, total] = await this.produtoService.findAll(query);
    return {
      items,
      total,
      hasNext: total > (query.page || 1) * (query.limit || 10),
    };
  }

  @Get('categorias')
  @ApiOperation({ summary: 'Obtém lista de categorias de produtos' })
  findCategorias() {
    return this.produtoService.findCategorias();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Busca detalhes de um produto pelo ID' })
  @ApiResponse({ status: 200, type: ProdutoResponseDto })
  async findOne(@Param('id', ParseIntPipe) id: number) {
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

  @Post(':id/imagens')
  @ApiOperation({ summary: 'Adiciona uma foto à galeria do produto' })
  @UseInterceptors(FileInterceptor('file'))
  async adicionarImagem(
    @Param('id', ParseIntPipe) id: number,
    @UploadedFile() file: MulterFile,
  ) {
    return this.produtoService.adicionarImagem(id, file);
  }

  @Put('imagens/:imagemId/principal')
  @ApiOperation({ summary: 'Define uma foto específica como a principal do produto' })
  async definirPrincipal(@Param('imagemId', ParseIntPipe) imagemId: number) {
    return this.produtoService.definirPrincipal(imagemId);
  }

  @Delete('imagens/:imagemId')
  @ApiOperation({ summary: 'Remove uma foto da galeria do produto' })
  async removerImagem(@Param('imagemId', ParseIntPipe) imagemId: number) {
    return this.produtoService.removerImagem(imagemId);
  }
}
