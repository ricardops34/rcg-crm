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
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { VendedorService } from '../../services/vendedor/vendedor.service';
import { JwtAuthGuard } from '../../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../../auth/guards/permissions.guard';
import { RequirePermission } from '../../../auth/decorators/permissions.decorator';
import { CreateVendedorDto, UpdateVendedorDto, VendedorResponseDto, PaginatedVendedorResponseDto } from '../../dto/vendedor.dto';

@ApiTags('Commercial / Vendedores')
@ApiBearerAuth()
@Controller('commercial/vendedores')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('VendedorList')
export class VendedorController {
  constructor(private readonly vendedorService: VendedorService) {}

  @Get()
  @ApiOperation({ summary: 'Lista vendedores com paginação (padrão PO-UI)' })
  @ApiResponse({ status: 200, type: PaginatedVendedorResponseDto })
  async findAll(
    @Query('page') page: number = 1,
    @Query('limit') limit: number = 10,
    @Query('status') status?: string,
    @Query('dashboard') dashboard?: string,
    @Query('supervisor') supervisor?: string,
    @Query('order') order?: string,
  ) {
    const [items, total] = await this.vendedorService.findAll(page, limit, {
      status,
      dashboard,
      supervisor,
      order,
    });
    return { items, total, hasNext: total > page * limit };
  }

  @Get(':id')
  @ApiOperation({ summary: 'Busca detalhes de um vendedor pelo ID' })
  @ApiResponse({ status: 200, type: VendedorResponseDto })
  async findOne(@Param('id', ParseIntPipe) id: number) {
    return this.vendedorService.findOne(id);
  }

  @Post()
  @ApiOperation({ summary: 'Cadastra um novo vendedor' })
  @ApiBody({ type: CreateVendedorDto })
  @ApiResponse({ status: 201, type: VendedorResponseDto })
  async create(@Body() data: CreateVendedorDto) {
    return this.vendedorService.save(data);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um vendedor' })
  @ApiBody({ type: UpdateVendedorDto })
  @ApiResponse({ status: 200, type: VendedorResponseDto })
  async update(@Param('id', ParseIntPipe) id: number, @Body() data: UpdateVendedorDto) {
    return this.vendedorService.save({ ...data, id });
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number) {
    await this.vendedorService.remove(id);
    return { success: true };
  }
}
