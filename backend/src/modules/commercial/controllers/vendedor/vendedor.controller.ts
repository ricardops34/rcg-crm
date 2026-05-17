import { Controller, Get, Post, Body, Param, Query, UseGuards } from '@nestjs/common';
import { VendedorService } from '../../services/vendedor/vendedor.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';

@Controller('commercial/vendedores')
@UseGuards(JwtAuthGuard)
export class VendedorController {
  constructor(private readonly vendedorService: VendedorService) {}

  @Get()
  async findAll(@Query('page') page: number = 1, @Query('limit') limit: number = 10) {
    const [items, total] = await this.vendedorService.findAll(page, limit);
    return { items, total };
  }

  @Get(':id')
  async findOne(@Param('id') id: number) {
    return this.vendedorService.findOne(id);
  }

  @Post()
  async save(@Body() data: any) {
    return this.vendedorService.save(data);
  }
}
