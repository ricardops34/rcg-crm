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
import { VendedorService } from '../../services/vendedor/vendedor.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../admin/guards/permissions.guard';
import { ControllerName } from '../../../admin/decorators/controller-name.decorator';

@Controller('commercial/vendedores')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@ControllerName('VendedorList')
export class VendedorController {
  constructor(private readonly vendedorService: VendedorService) {}

  @Get()
  async findAll(
    @Query('page') page: number = 1,
    @Query('limit') limit: number = 10,
  ) {
    const [items, total] = await this.vendedorService.findAll(page, limit);
    return { items, total };
  }

  @Get(':id')
  async findOne(@Param('id', ParseIntPipe) id: number) {
    return this.vendedorService.findOne(id);
  }

  @Post()
  async create(@Body() data: any) {
    return this.vendedorService.save(data);
  }

  @Put(':id')
  async update(@Param('id', ParseIntPipe) id: number, @Body() data: any) {
    return this.vendedorService.save({ ...data, id });
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    await this.vendedorService.remove(id);
    return { success: true };
  }
}
