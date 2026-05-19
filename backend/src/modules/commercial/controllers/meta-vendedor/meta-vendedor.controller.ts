import {
  Controller,
  Get,
  Post,
  Body,
  Param,
  Query,
  UseGuards,
} from '@nestjs/common';
import { MetaVendedorService } from '../../services/meta-vendedor/meta-vendedor.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('commercial/metas')
@UseGuards(JwtAuthGuard)
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
  async findOne(@Param('id') id: number) {
    return this.metaService.findOne(id);
  }

  @Post()
  async save(@Body() data: any) {
    return this.metaService.save(data);
  }
}
