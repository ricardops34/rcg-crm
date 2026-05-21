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
import { MetaVendedorService } from '../../services/meta-vendedor/meta-vendedor.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../admin/guards/permissions.guard';
import { ControllerName } from '../../../admin/decorators/controller-name.decorator';

@Controller('commercial/metas')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('MetaList')
export class MetaVendedorController {
  constructor(private readonly metaService: MetaVendedorService) {}

  @Get()
  async findAll(
    @Query('page') page: number = 1,
    @Query('limit') limit: number = 10,
  ) {
    const [items, total] = await this.metaService.findAll(page, limit);
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
