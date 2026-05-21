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
import { TabelaPrecoService } from '../../services/tabela-preco/tabela-preco.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../admin/guards/permissions.guard';
import { ControllerName } from '../../../admin/decorators/controller-name.decorator';

@Controller('commercial/tabelas-precos')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('TabelaPrecoList')
export class TabelaPrecoController {
  constructor(private readonly tabelaPrecoService: TabelaPrecoService) {}

  @Get()
  async findAll(
    @Query('page') page: number = 1,
    @Query('limit') limit: number = 100,
  ) {
    const [items, total] = await this.tabelaPrecoService.findAll(page, limit);
    return { items, total };
  }

  @Get(':id')
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.tabelaPrecoService.findOne(id);
  }

  @Get(':id/itens')
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
