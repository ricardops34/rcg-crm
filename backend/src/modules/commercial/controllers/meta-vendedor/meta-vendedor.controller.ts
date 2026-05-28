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
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { MetaVendedorService } from '../../services/meta-vendedor/meta-vendedor.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';

@ApiTags('Commercial / Metas')
@ApiBearerAuth()
@Controller('commercial/metas')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('MetaList')
export class MetaVendedorController {
  constructor(private readonly metaService: MetaVendedorService) {}

  @Get()
  async findAll(
    @Query('page') page: number = 1,
    @Query('limit') limit: number = 10,
    @Query('ano') ano?: string,
    @Query('mes') mes?: string,
    @Query('vendedorId') vendedorId?: number,
    @Query('order') order?: string,
  ) {
    const [items, total] = await this.metaService.findAll(page, limit, {
      ano,
      mes,
      vendedorId: vendedorId ? Number(vendedorId) : undefined,
      order,
    });
    return { items, total };
  }

  @Get('suggestion')
  async getSuggestion(
    @Query('vendedorId') vendedorId: number,
    @Query('month') month: string,
    @Query('year') year: string,
  ) {
    return {
      suggestion: await this.metaService.getSuggestion(vendedorId, month, year),
    };
  }

  @Get(':id')
  async findOne(@Param('id', ParseIntPipe) id: number) {
    return this.metaService.findOne(id);
  }

  @Post()
  async create(@Body() data: any) {
    return this.metaService.save(data);
  }

  @Put(':id')
  async update(@Param('id', ParseIntPipe) id: number, @Body() data: any) {
    return this.metaService.save({ ...data, id });
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    await this.metaService.remove(id);
    return { success: true };
  }
}
